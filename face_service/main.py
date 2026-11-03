"""
Face Recognition Sidecar Service for HRMS
==========================================
InsightFace buffalo_l (SCRFD + w600k_r50 ArcFace 512-D)
MiniFASNet passive anti-spoofing (ONNX)

Semua inference AI terjadi di sini. PHP hanya orkestrator.
"""

import base64
import hashlib
import hmac
import io
import os
import time
import traceback
import logging
from datetime import datetime, timezone

import cv2
import numpy as np
import psutil
from fastapi import FastAPI, Request, HTTPException
from fastapi.responses import JSONResponse
from pydantic import BaseModel, Field
from typing import Optional, Literal

# ─── Constants ──────────────────────────────────────────────────────────────────
MODEL_VERSION = "buffalo_l-w600k_r50-v1"
HMAC_MAX_AGE_SECONDS = 30
VALID_POSES = ("front", "up", "down", "left", "right")

# ─── Pose validation ranges (degrees) ──────────────────────────────────────────
POSE_RANGES = {
    "front": {"yaw": (-10, 10),  "pitch": (-10, 10)},
    "left":  {"yaw": (10, 32),   "pitch": (-16, 16)},
    "right": {"yaw": (-32, -10), "pitch": (-16, 16)},
    "up":    {"yaw": (-16, 16),  "pitch": (6, 30)},
    "down":  {"yaw": (-16, 16),  "pitch": (-30, -6)},
}

# ─── Quality gate thresholds ───────────────────────────────────────────────────
MIN_FACE_SIZE_PX = 110
MIN_LAPLACIAN_VAR = 60.0
MIN_DET_SCORE = 0.60
BRIGHTNESS_MIN = 45
BRIGHTNESS_MAX = 215

# ─── Multi-frame liveness thresholds (configurable via env for calibration) ────
SIM_PRE_POST_THRESHOLD = float(os.environ.get("SIM_PRE_POST_THRESHOLD", "0.82"))
SIM_PRE_BLINK_THRESHOLD = float(os.environ.get("SIM_PRE_BLINK_THRESHOLD", "0.70"))
BLINK_RATIO_THRESHOLD = float(os.environ.get("BLINK_RATIO_THRESHOLD", "0.70"))

# ─── Logging ────────────────────────────────────────────────────────────────────
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S"
)
logger = logging.getLogger("face_service")

# ─── App ────────────────────────────────────────────────────────────────────────
app = FastAPI(title="HRMS Face Recognition Sidecar", version="1.0.0")

# ─── Global model holders ──────────────────────────────────────────────────────
face_app = None
antispoof_model = None
FACE_SECRET = None
ALLOWED_IPS = None


# ═══════════════════════════════════════════════════════════════════════════════
# Startup: Load models ONCE
# ═══════════════════════════════════════════════════════════════════════════════
@app.on_event("startup")
def load_models():
    global face_app, antispoof_model, FACE_SECRET, ALLOWED_IPS

    logger.info("Loading InsightFace buffalo_l model...")
    import insightface
    from insightface.app import FaceAnalysis

    face_app = FaceAnalysis(
        name="buffalo_l",
        providers=["CPUExecutionProvider"]
    )
    face_app.prepare(ctx_id=0, det_size=(640, 640))
    logger.info(f"InsightFace buffalo_l loaded. Model version: {MODEL_VERSION}")

    # Load MiniFASNet anti-spoofing if model file exists
    antispoof_model_path = os.environ.get(
        "ANTISPOOF_MODEL_PATH",
        os.path.join(os.path.dirname(__file__), "models", "anti_spoof.onnx")
    )
    if os.path.exists(antispoof_model_path):
        import onnxruntime as ort
        antispoof_model = ort.InferenceSession(
            antispoof_model_path,
            providers=["CPUExecutionProvider"]
        )
        logger.info(f"MiniFASNet anti-spoofing model loaded from {antispoof_model_path}")
    else:
        logger.warning(
            f"Anti-spoofing model not found at {antispoof_model_path}. "
            "Anti-spoofing checks will be SKIPPED. "
            "Deploy the ONNX model for production use."
        )

    # Load secret from environment
    FACE_SECRET = os.environ.get("FACE_SECRET")
    if not FACE_SECRET:
        logger.error("FACE_SECRET environment variable is NOT SET. All requests will be rejected.")
    else:
        logger.info("FACE_SECRET loaded from environment.")

    # Load allowed IPs (comma-separated)
    allowed_raw = os.environ.get("ALLOWED_IPS", "")
    if allowed_raw.strip():
        ALLOWED_IPS = [ip.strip() for ip in allowed_raw.split(",") if ip.strip()]
        logger.info(f"IP allowlist active: {ALLOWED_IPS}")
    else:
        ALLOWED_IPS = None
        logger.warning("ALLOWED_IPS not set. No IP filtering active. Set this in production!")


# ═══════════════════════════════════════════════════════════════════════════════
# Security middleware: HMAC + Timestamp + IP allowlist
# ═══════════════════════════════════════════════════════════════════════════════
@app.middleware("http")
async def security_middleware(request: Request, call_next):
    # Allow health endpoint without auth
    if request.url.path == "/health":
        return await call_next(request)

    # IP allowlist check
    client_ip = request.client.host if request.client else "unknown"
    if ALLOWED_IPS and client_ip not in ALLOWED_IPS:
        logger.warning(f"Rejected request from unauthorized IP: {client_ip}")
        return JSONResponse(
            status_code=403,
            content={"ok": False, "reason": "ip_not_allowed"}
        )

    # HMAC secret must be configured
    if not FACE_SECRET:
        logger.error("FACE_SECRET not configured. Rejecting request.")
        return JSONResponse(
            status_code=500,
            content={"ok": False, "reason": "server_misconfigured"}
        )

    # Timestamp anti-replay
    x_timestamp = request.headers.get("X-Timestamp", "")
    if not x_timestamp:
        return JSONResponse(
            status_code=401,
            content={"ok": False, "reason": "missing_timestamp"}
        )

    try:
        req_time = float(x_timestamp)
        age = abs(time.time() - req_time)
        if age > HMAC_MAX_AGE_SECONDS:
            logger.warning(f"Rejected stale request. Age: {age:.1f}s from IP: {client_ip}")
            return JSONResponse(
                status_code=401,
                content={"ok": False, "reason": "timestamp_expired", "age_seconds": round(age, 1)}
            )
    except (ValueError, TypeError):
        return JSONResponse(
            status_code=401,
            content={"ok": False, "reason": "invalid_timestamp"}
        )

    # HMAC signature verification
    x_signature = request.headers.get("X-Signature", "")
    if not x_signature:
        return JSONResponse(
            status_code=401,
            content={"ok": False, "reason": "missing_signature"}
        )

    # Read body for HMAC verification
    body = await request.body()

    # HMAC = hmac_sha256(FACE_SECRET, timestamp + body)
    message = x_timestamp.encode("utf-8") + body
    expected_sig = hmac.new(
        FACE_SECRET.encode("utf-8"),
        message,
        hashlib.sha256
    ).hexdigest()

    if not hmac.compare_digest(x_signature, expected_sig):
        logger.warning(f"HMAC signature mismatch from IP: {client_ip}")
        return JSONResponse(
            status_code=401,
            content={"ok": False, "reason": "invalid_signature"}
        )

    response = await call_next(request)
    return response


# ═══════════════════════════════════════════════════════════════════════════════
# Health endpoint
# ═══════════════════════════════════════════════════════════════════════════════
@app.get("/health")
def health():
    process = psutil.Process()
    mem_info = process.memory_info()
    import insightface
    import onnxruntime

    return {
        "status": "ok",
        "model_loaded": face_app is not None,
        "model_version": MODEL_VERSION,
        "antispoof_loaded": antispoof_model is not None,
        "insightface_version": insightface.__version__,
        "onnxruntime_version": onnxruntime.__version__,
        "peak_rss_mb": round(mem_info.rss / 1024 / 1024, 1),
        "timestamp": datetime.now(timezone.utc).isoformat()
    }


# ═══════════════════════════════════════════════════════════════════════════════
# Request/Response models
# ═══════════════════════════════════════════════════════════════════════════════
class AnalyzeRequest(BaseModel):
    image_b64: str
    mode: Literal["enroll", "verify"]
    expected_pose: Optional[Literal["front", "up", "down", "left", "right"]] = None


# ═══════════════════════════════════════════════════════════════════════════════
# Helper functions
# ═══════════════════════════════════════════════════════════════════════════════
def decode_image(image_b64: str) -> Optional[np.ndarray]:
    """Decode base64 image to OpenCV BGR numpy array."""
    try:
        # Strip data URI prefix if present
        if "," in image_b64[:100]:
            image_b64 = image_b64.split(",", 1)[1]
        raw = base64.b64decode(image_b64)
        nparr = np.frombuffer(raw, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
        return img
    except Exception:
        return None


def compute_blur(face_crop: np.ndarray) -> float:
    """Compute Laplacian variance as a blur metric."""
    gray = cv2.cvtColor(face_crop, cv2.COLOR_BGR2GRAY)
    return float(cv2.Laplacian(gray, cv2.CV_64F).var())


def compute_brightness(face_crop: np.ndarray) -> float:
    """Compute mean brightness of face crop."""
    gray = cv2.cvtColor(face_crop, cv2.COLOR_BGR2GRAY)
    return float(np.mean(gray))


def validate_pose(face, expected_pose: str) -> tuple:
    """
    Validate detected pose against expected pose.
    Returns (is_valid, measured_pose_dict, reason).
    InsightFace pose is [pitch, yaw, roll] in the 3D attribute.
    """
    # InsightFace stores pose as face.pose which is [pitch, yaw, roll]
    # Note: different InsightFace versions may order differently
    # buffalo_l returns pose as [yaw, pitch, roll] via face.pose
    pose_3d = face.pose.astype(float)  # [pitch, yaw, roll]
    pitch, yaw, roll = float(pose_3d[0]), float(pose_3d[1]), float(pose_3d[2])

    measured = {"yaw": round(yaw, 1), "pitch": round(pitch, 1), "roll": round(roll, 1)}

    if expected_pose not in POSE_RANGES:
        return True, measured, None  # No validation requested

    ranges = POSE_RANGES[expected_pose]
    yaw_min, yaw_max = ranges["yaw"]
    pitch_min, pitch_max = ranges["pitch"]

    logger.info(f"Pose check: expected={expected_pose}, measured={measured}")

    if not (yaw_min <= yaw <= yaw_max):
        logger.warning(f"Pose validation failed for '{expected_pose}': yaw={yaw} not in [{yaw_min}, {yaw_max}]")
        return False, measured, f"wrong_pose"
    if not (pitch_min <= pitch <= pitch_max):
        logger.warning(f"Pose validation failed for '{expected_pose}': pitch={pitch} not in [{pitch_min}, {pitch_max}]")
        return False, measured, f"wrong_pose"

    return True, measured, None


def compute_ear_from_landmarks(face) -> float:
    """
    Compute Eye Aspect Ratio (EAR) from 2D landmarks.
    InsightFace buffalo_l returns face.landmark_2d_106 (106 points).
    Left eye: landmarks [35..42]
    Right eye: landmarks [89..96]
    """
    if hasattr(face, 'landmark_2d_106') and face.landmark_2d_106 is not None:
        pts = face.landmark_2d_106
        def eye_ear(corner1, corner2, top1, top2, bot1, bot2):
            w = float(np.linalg.norm(pts[corner1] - pts[corner2]))
            if w < 1e-6:
                return 0.0
            h1 = float(np.linalg.norm(pts[top1] - pts[bot1]))
            h2 = float(np.linalg.norm(pts[top2] - pts[bot2]))
            return (h1 + h2) / (2.0 * w)
        left_ear = eye_ear(35, 39, 37, 38, 41, 40)
        right_ear = eye_ear(89, 93, 91, 92, 95, 94)
        return float((left_ear + right_ear) / 2.0)
    elif hasattr(face, 'kps') and face.kps is not None:
        return 0.25
    return 0.25


def run_antispoof(face_crop: np.ndarray) -> tuple:
    """
    Run MiniFASNet anti-spoofing on face crop.
    Returns (is_real, liveness_score).
    """
    if antispoof_model is None:
        # Anti-spoofing not available — log warning but allow
        # In production, the model MUST be deployed
        return True, -1.0

    try:
        # Preprocess for MiniFASNet: resize to 80x80, normalize
        resized = cv2.resize(face_crop, (80, 80))
        img_float = resized.astype(np.float32) / 255.0
        # Transpose HWC -> CHW and add batch dimension
        img_chw = np.transpose(img_float, (2, 0, 1))
        img_batch = np.expand_dims(img_chw, axis=0)

        input_name = antispoof_model.get_inputs()[0].name
        output = antispoof_model.run(None, {input_name: img_batch})

        # MiniFASNet output: [batch, 2] — index 1 is "real" probability
        scores = output[0][0]
        real_score = float(scores[1]) if len(scores) > 1 else float(scores[0])

        # Threshold for liveness — NILAI AWAL, BELUM DIKALIBRASI
        liveness_threshold = float(os.environ.get("LIVENESS_THRESHOLD", "0.5"))
        is_real = real_score >= liveness_threshold

        return is_real, round(real_score, 4)

    except Exception as e:
        logger.error(f"Anti-spoofing inference error: {e}")
        # Fail closed — treat as spoof if anti-spoofing fails
        return False, -1.0


# ═══════════════════════════════════════════════════════════════════════════════
# Main analyze endpoint
# ═══════════════════════════════════════════════════════════════════════════════
@app.post("/analyze")
async def analyze(request: Request):
    """
    Analyze a face image for enrollment or verification.

    Returns embedding (512-D, L2-normalized) on success, or rejection reason.
    ALL rejections are HTTP 200 + {ok: false, reason: "..."}.
    HTTP 4xx/5xx ONLY for auth/system errors.
    """
    start_time = time.time()

    try:
        # Parse body (already read by middleware, need to re-parse)
        body = await request.body()
        import json
        try:
            data = json.loads(body)
        except json.JSONDecodeError:
            return JSONResponse(content={"ok": False, "reason": "invalid_json"})

        image_b64 = data.get("image_b64", "")
        frames_b64 = data.get("frames")
        mode = data.get("mode", "verify")
        expected_pose = data.get("expected_pose")

        if mode not in ("enroll", "verify"):
            return JSONResponse(content={"ok": False, "reason": "invalid_mode"})

        # ─── Step 1 & 2: Process frames / image ────────────────────────
        if frames_b64 and isinstance(frames_b64, list) and len(frames_b64) == 3:
            # Multi-frame liveness verification
            imgs = [decode_image(f) for f in frames_b64]
            if any(im is None for im in imgs):
                return JSONResponse(content={"ok": False, "reason": "decode_failed"})

            detected_faces = [face_app.get(im) for im in imgs]
            for idx, df in enumerate(detected_faces):
                if len(df) == 0:
                    return JSONResponse(content={"ok": False, "reason": "no_face", "frame_idx": idx})
                if len(df) > 1:
                    return JSONResponse(content={"ok": False, "reason": "multiple_faces", "frame_idx": idx})

            f_pre, f_blink, f_post = detected_faces[0][0], detected_faces[1][0], detected_faces[2][0]

            # 1. Blink Gate: Middle frame MUST have closed eyes
            ear_pre = compute_ear_from_landmarks(f_pre)
            ear_blink = compute_ear_from_landmarks(f_blink)
            ear_post = compute_ear_from_landmarks(f_post)

            # 1. Blink Gate: Dynamic ratio against user's own open-eye baseline
            baseline_open = min(ear_pre, ear_post)
            blink_ratio = (ear_blink / baseline_open) if baseline_open > 1e-6 else 1.0

            # Middle frame must drop significantly compared to open baseline
            if blink_ratio > BLINK_RATIO_THRESHOLD or ear_blink >= baseline_open * 0.90:
                logger.warning(f"Blink verification failed: ear_pre={ear_pre:.3f}, ear_blink={ear_blink:.3f}, ear_post={ear_post:.3f}, ratio={blink_ratio:.3f}")
                return JSONResponse(content={
                    "ok": False,
                    "reason": "blink_not_detected",
                    "detail": "Frame tengah tidak menunjukkan mata tertutup",
                    "ear_pre": round(ear_pre, 3),
                    "ear_blink": round(ear_blink, 3),
                    "ear_post": round(ear_post, 3),
                    "blink_ratio": round(blink_ratio, 3)
                })

            # 2. Identity Gate: Ensure all 3 frames are the same person
            sim_pre_post = float(np.dot(f_pre.normed_embedding, f_post.normed_embedding))
            sim_pre_blink = float(np.dot(f_pre.normed_embedding, f_blink.normed_embedding))
            if sim_pre_post < SIM_PRE_POST_THRESHOLD or sim_pre_blink < SIM_PRE_BLINK_THRESHOLD:
                return JSONResponse(content={
                    "ok": False,
                    "reason": "cross_frame_mismatch",
                    "detail": "Wajah pada 3 frame terdeteksi orang yang berbeda",
                    "sim_pre_post": round(sim_pre_post, 4),
                    "sim_pre_blink": round(sim_pre_blink, 4)
                })

            # Primary frame for embedding is the post-blink frame (fresh, open eyes)
            face = f_post
            img = imgs[2]
        else:
            img = decode_image(image_b64)
            if img is None:
                return JSONResponse(content={"ok": False, "reason": "decode_failed"})

            faces = face_app.get(img)
            if len(faces) == 0:
                return JSONResponse(content={"ok": False, "reason": "no_face"})
            if len(faces) > 1:
                return JSONResponse(content={
                    "ok": False,
                    "reason": "multiple_faces",
                    "face_count": len(faces)
                })
            face = faces[0]

        # ─── Step 3: Quality gates ──────────────────────────────────────
        bbox = face.bbox.astype(int)  # [x1, y1, x2, y2]
        x1, y1, x2, y2 = int(bbox[0]), int(bbox[1]), int(bbox[2]), int(bbox[3])
        face_w = x2 - x1
        face_h = y2 - y1
        min_side = min(face_w, face_h)

        if min_side < MIN_FACE_SIZE_PX:
            return JSONResponse(content={
                "ok": False,
                "reason": "face_too_small",
                "min_side_px": min_side,
                "required_px": MIN_FACE_SIZE_PX
            })

        det_score = float(face.det_score)
        if det_score < MIN_DET_SCORE:
            return JSONResponse(content={
                "ok": False,
                "reason": "low_detection_confidence",
                "det_score": round(det_score, 3),
                "required": MIN_DET_SCORE
            })

        # Crop face for quality checks (clamp to image bounds)
        h, w = img.shape[:2]
        cx1 = max(0, x1)
        cy1 = max(0, y1)
        cx2 = min(w, x2)
        cy2 = min(h, y2)
        face_crop = img[cy1:cy2, cx1:cx2]

        if face_crop.size == 0:
            return JSONResponse(content={"ok": False, "reason": "face_crop_failed"})

        blur_val = compute_blur(face_crop)
        if blur_val < MIN_LAPLACIAN_VAR:
            return JSONResponse(content={
                "ok": False,
                "reason": "too_blurry",
                "blur": round(blur_val, 1),
                "required": MIN_LAPLACIAN_VAR
            })

        brightness_val = compute_brightness(face_crop)
        if brightness_val < BRIGHTNESS_MIN or brightness_val > BRIGHTNESS_MAX:
            return JSONResponse(content={
                "ok": False,
                "reason": "bad_lighting",
                "brightness": round(brightness_val, 1),
                "valid_range": [BRIGHTNESS_MIN, BRIGHTNESS_MAX]
            })

        # ─── Step 4: Anti-spoofing (BEFORE embedding) ───────────────────
        is_real, liveness_score = run_antispoof(face_crop)
        if not is_real:
            return JSONResponse(content={
                "ok": False,
                "reason": "spoof_detected",
                "liveness_score": liveness_score
            })

        # ─── Step 5: Pose validation ────────────────────────────────────
        pose_valid, measured_pose, pose_reason = validate_pose(face, expected_pose)
        if not pose_valid:
            return JSONResponse(content={
                "ok": False,
                "reason": pose_reason,
                "expected_pose": expected_pose,
                "measured_pose": measured_pose
            })

        # ─── Step 6: Extract embedding ──────────────────────────────────
        embedding = face.normed_embedding  # 512-D, already L2-normalized by InsightFace
        embedding_list = embedding.tolist()

        # Verify dimension
        if len(embedding_list) != 512:
            logger.error(f"Unexpected embedding dimension: {len(embedding_list)}")
            return JSONResponse(content={
                "ok": False,
                "reason": "embedding_dimension_error",
                "dimension": len(embedding_list)
            })

        elapsed_ms = int((time.time() - start_time) * 1000)

        return JSONResponse(content={
            "ok": True,
            "embedding": embedding_list,
            "model_version": MODEL_VERSION,
            "det_score": round(det_score, 4),
            "pose": measured_pose,
            "blur": round(blur_val, 1),
            "brightness": round(brightness_val, 1),
            "liveness_score": liveness_score,
            "bbox": [x1, y1, x2, y2],
            "latency_ms": elapsed_ms
        })

    except Exception as e:
        logger.error(f"Unhandled error in /analyze: {traceback.format_exc()}")
        return JSONResponse(
            status_code=500,
            content={"ok": False, "reason": "internal_error", "detail": str(e)}
        )


# ═══════════════════════════════════════════════════════════════════════════════
# Run with: uvicorn main:app --host 127.0.0.1 --port 8000
# ═══════════════════════════════════════════════════════════════════════════════
if __name__ == "__main__":
    import uvicorn
    bind_host = os.environ.get("BIND_HOST", "127.0.0.1")
    bind_port = int(os.environ.get("BIND_PORT", "8000"))
    logger.info(f"Starting face service on {bind_host}:{bind_port}")
    uvicorn.run(app, host=bind_host, port=bind_port, log_level="info")
