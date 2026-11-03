"""
HRMS Face Recognition — Calibration Tool
==========================================
Analyzes real-world face data to determine optimal threshold and margin.

Usage:
    python tools/calibrate.py --photos-dir /path/to/labeled_photos

Expected directory structure:
    photos_dir/
        EMP001/
            front.jpg
            left.jpg
            right.jpg
            ...
        EMP002/
            front.jpg
            ...

Output:
    - Same-person vs different-person score distributions
    - FAR/FRR curve
    - Recommended threshold
    - Recommended minimum margin
    - Confusion pairs (most similar different people)
"""

import argparse
import os
import sys
import json
import itertools
from pathlib import Path

import numpy as np

# Try importing matplotlib for plots
try:
    import matplotlib.pyplot as plt
    HAS_PLOT = True
except ImportError:
    HAS_PLOT = False
    print("WARNING: matplotlib not available. Plots will be skipped.")


def load_embeddings(photos_dir: str, sidecar_url: str, secret: str):
    """
    Send each photo to the sidecar and collect embeddings.
    Returns dict: {em_code: [embedding1, embedding2, ...]}
    """
    import requests
    import hashlib
    import hmac
    import base64
    import time

    embeddings = {}
    
    for emp_dir in sorted(Path(photos_dir).iterdir()):
        if not emp_dir.is_dir():
            continue
        em_code = emp_dir.name
        embeddings[em_code] = []
        
        for photo in sorted(emp_dir.glob("*.jpg")) + sorted(emp_dir.glob("*.png")):
            with open(photo, "rb") as f:
                raw = f.read()
            image_b64 = base64.b64encode(raw).decode()
            
            body = json.dumps({"image_b64": image_b64, "mode": "verify"})
            timestamp = str(int(time.time()))
            message = timestamp.encode() + body.encode()
            signature = hmac.new(secret.encode(), message, hashlib.sha256).hexdigest()
            
            resp = requests.post(
                f"{sidecar_url}/analyze",
                data=body,
                headers={
                    "Content-Type": "application/json",
                    "X-Signature": signature,
                    "X-Timestamp": timestamp,
                },
                timeout=30,
            )
            
            result = resp.json()
            if result.get("ok"):
                embeddings[em_code].append({
                    "file": str(photo),
                    "embedding": result["embedding"],
                })
                print(f"  ✓ {photo.name} → {em_code}")
            else:
                print(f"  ✗ {photo.name} → {result.get('reason', 'unknown')}")
    
    return embeddings


def cosine_sim(a, b):
    a = np.array(a, dtype=np.float64)
    b = np.array(b, dtype=np.float64)
    return float(np.dot(a, b) / (np.linalg.norm(a) * np.linalg.norm(b) + 1e-10))


def analyze(embeddings: dict):
    """Compute same-person and different-person score distributions."""
    same_scores = []
    diff_scores = []
    confusion_pairs = []

    em_codes = list(embeddings.keys())
    
    # Same-person pairs
    for em_code, entries in embeddings.items():
        vecs = [e["embedding"] for e in entries]
        for i in range(len(vecs)):
            for j in range(i + 1, len(vecs)):
                score = cosine_sim(vecs[i], vecs[j])
                same_scores.append(score)
    
    # Different-person pairs (sample to limit computation)
    for i in range(len(em_codes)):
        for j in range(i + 1, len(em_codes)):
            code_a = em_codes[i]
            code_b = em_codes[j]
            vecs_a = [e["embedding"] for e in embeddings[code_a]]
            vecs_b = [e["embedding"] for e in embeddings[code_b]]
            
            for va in vecs_a:
                for vb in vecs_b:
                    score = cosine_sim(va, vb)
                    diff_scores.append(score)
                    confusion_pairs.append((code_a, code_b, score))
    
    return same_scores, diff_scores, confusion_pairs


def compute_far_frr(same_scores, diff_scores, thresholds):
    """Compute FAR and FRR at each threshold."""
    fars = []
    frrs = []
    same = np.array(same_scores)
    diff = np.array(diff_scores)
    
    for t in thresholds:
        frr = float(np.mean(same < t))  # Genuine rejected (score < threshold)
        far = float(np.mean(diff >= t))  # Impostor accepted (score >= threshold)
        frrs.append(frr)
        fars.append(far)
    
    return fars, frrs


def main():
    parser = argparse.ArgumentParser(description="HRMS Face Recognition Calibration")
    parser.add_argument("--photos-dir", required=True, help="Directory with labeled photos (em_code/photo.jpg)")
    parser.add_argument("--sidecar-url", default="http://127.0.0.1:8000", help="Face sidecar URL")
    parser.add_argument("--secret", default=os.environ.get("FACE_SECRET", ""), help="HMAC secret")
    parser.add_argument("--output", default="calibration_results.json", help="Output JSON file")
    args = parser.parse_args()

    if not args.secret:
        print("ERROR: FACE_SECRET not set. Use --secret or FACE_SECRET env var.")
        sys.exit(1)

    print(f"Loading photos from {args.photos_dir}...")
    embeddings = load_embeddings(args.photos_dir, args.sidecar_url, args.secret)
    
    total_employees = len(embeddings)
    total_embeddings = sum(len(v) for v in embeddings.values())
    print(f"\nLoaded {total_embeddings} embeddings from {total_employees} employees.")
    
    if total_employees < 2:
        print("ERROR: Need at least 2 employees for calibration.")
        sys.exit(1)

    print("\nAnalyzing score distributions...")
    same_scores, diff_scores, confusion_pairs = analyze(embeddings)
    
    print(f"\n{'='*60}")
    print(f" CALIBRATION RESULTS")
    print(f"{'='*60}")
    print(f" Same-person pairs:   {len(same_scores)}")
    print(f"   Mean: {np.mean(same_scores):.4f}  Std: {np.std(same_scores):.4f}")
    print(f"   Min:  {np.min(same_scores):.4f}  Max: {np.max(same_scores):.4f}")
    print(f" Diff-person pairs:   {len(diff_scores)}")
    print(f"   Mean: {np.mean(diff_scores):.4f}  Std: {np.std(diff_scores):.4f}")
    print(f"   Min:  {np.min(diff_scores):.4f}  Max: {np.max(diff_scores):.4f}")
    
    # FAR/FRR analysis
    thresholds = np.arange(0.10, 0.80, 0.01)
    fars, frrs = compute_far_frr(same_scores, diff_scores, thresholds)
    
    # Find EER (Equal Error Rate)
    eer_idx = np.argmin(np.abs(np.array(fars) - np.array(frrs)))
    eer_threshold = float(thresholds[eer_idx])
    eer_value = (fars[eer_idx] + frrs[eer_idx]) / 2
    
    # Recommended threshold: slightly above EER threshold for safety
    recommended_threshold = round(eer_threshold + 0.02, 2)
    
    # Margin analysis
    confusion_pairs.sort(key=lambda x: x[2], reverse=True)
    top_confusion = confusion_pairs[:20]
    
    margins = []
    for em_code, entries in embeddings.items():
        vecs = [e["embedding"] for e in entries]
        all_other_vecs = []
        for other_code, other_entries in embeddings.items():
            if other_code != em_code:
                all_other_vecs.extend([e["embedding"] for e in other_entries])
        
        for v in vecs:
            same_best = max(cosine_sim(v, v2) for v2 in vecs if v2 is not v) if len(vecs) > 1 else 0
            diff_best = max(cosine_sim(v, v2) for v2 in all_other_vecs) if all_other_vecs else 0
            margins.append(same_best - diff_best)
    
    recommended_margin = round(max(np.percentile(margins, 5), 0.04), 2)
    
    print(f"\n EER: {eer_value:.4f} at threshold {eer_threshold:.2f}")
    print(f" Recommended threshold: {recommended_threshold}")
    print(f" Recommended min_margin: {recommended_margin}")
    
    print(f"\n Top confusion pairs (most similar different people):")
    for a, b, s in top_confusion[:10]:
        print(f"   {a} ↔ {b}: {s:.4f}")
    
    # Save results
    results = {
        "total_employees": total_employees,
        "total_embeddings": total_embeddings,
        "same_person_stats": {
            "count": len(same_scores),
            "mean": round(float(np.mean(same_scores)), 4),
            "std": round(float(np.std(same_scores)), 4),
            "min": round(float(np.min(same_scores)), 4),
            "max": round(float(np.max(same_scores)), 4),
        },
        "diff_person_stats": {
            "count": len(diff_scores),
            "mean": round(float(np.mean(diff_scores)), 4),
            "std": round(float(np.std(diff_scores)), 4),
            "min": round(float(np.min(diff_scores)), 4),
            "max": round(float(np.max(diff_scores)), 4),
        },
        "eer_threshold": eer_threshold,
        "eer_value": round(eer_value, 4),
        "recommended_threshold": recommended_threshold,
        "recommended_min_margin": recommended_margin,
        "top_confusion_pairs": [
            {"a": a, "b": b, "score": round(s, 4)} for a, b, s in top_confusion[:20]
        ],
    }
    
    with open(args.output, "w") as f:
        json.dump(results, f, indent=2)
    print(f"\nResults saved to {args.output}")
    
    # Plot if available
    if HAS_PLOT:
        fig, axes = plt.subplots(1, 2, figsize=(14, 5))
        
        axes[0].hist(same_scores, bins=50, alpha=0.7, label="Same Person", color="#10B981")
        axes[0].hist(diff_scores, bins=50, alpha=0.7, label="Different Person", color="#EF4444")
        axes[0].axvline(recommended_threshold, color="#6366F1", linestyle="--", label=f"Threshold={recommended_threshold}")
        axes[0].set_xlabel("Cosine Similarity")
        axes[0].set_ylabel("Count")
        axes[0].set_title("Score Distribution")
        axes[0].legend()
        
        axes[1].plot(thresholds, fars, label="FAR (False Accept)", color="#EF4444")
        axes[1].plot(thresholds, frrs, label="FRR (False Reject)", color="#F59E0B")
        axes[1].axvline(eer_threshold, color="#6366F1", linestyle="--", alpha=0.5, label=f"EER={eer_value:.4f}")
        axes[1].set_xlabel("Threshold")
        axes[1].set_ylabel("Rate")
        axes[1].set_title("FAR / FRR Curve")
        axes[1].legend()
        
        plt.tight_layout()
        plt.savefig("calibration_plot.png", dpi=150)
        print("Plot saved to calibration_plot.png")


if __name__ == "__main__":
    main()
