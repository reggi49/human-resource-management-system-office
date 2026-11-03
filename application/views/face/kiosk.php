<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Presensi | <?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0F172A; --card:#1E293B; --green:#10B981; --red:#EF4444; --yellow:#F59E0B; --cyan:#38BDF8; --indigo:#6366F1; --text:#F8FAFC; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; overflow:hidden; user-select:none; }
        .kiosk-header { padding:14px 32px; background:rgba(30,41,59,.85); backdrop-filter:blur(12px); border-bottom:1px solid rgba(255,255,255,.08); display:flex; align-items:center; justify-content:space-between; }
        .kiosk-brand { font-size:17px; font-weight:800; display:flex; align-items:center; gap:10px; }
        .kiosk-brand-icon { width:34px; height:34px; border-radius:10px; background:var(--indigo); display:flex; align-items:center; justify-content:center; font-size:16px; color:#fff; }
        .live-clock { font-family:'Inter',sans-serif; font-size:18px; font-weight:800; background:rgba(255,255,255,.05); padding:5px 16px; border-radius:50px; border:1px solid rgba(255,255,255,.08); }
        .kiosk-body { display:flex; height:calc(100vh - 64px); padding:20px 32px; gap:20px; }
        .video-container { flex:1; position:relative; background:#000; border-radius:20px; overflow:hidden; border:3px solid rgba(255,255,255,.1); }
        .video-container video { width:100%; height:100%; object-fit:cover; transform:scaleX(-1); }
        .video-container canvas { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; }
        .blink-instruction { position:absolute; bottom:20px; left:50%; transform:translateX(-50%); font-size:16px; font-weight:700; padding:10px 28px; border-radius:14px; backdrop-filter:blur(10px); text-align:center; z-index:10; transition:.3s; }
        .blink-instruction.searching { background:rgba(245,158,11,.2); border:2px solid var(--yellow); color:var(--yellow); }
        .blink-instruction.ready { background:rgba(245,158,11,.2); border:2px solid var(--yellow); color:var(--yellow); }
        .blink-instruction.blink-ok { background:rgba(16,185,129,.2); border:2px solid var(--green); color:var(--green); }
        .blink-instruction.sending { background:rgba(16,185,129,.2); border:2px solid var(--green); color:var(--green); }
        .state-badge { position:absolute; top:16px; left:16px; padding:6px 14px; border-radius:50px; font-size:11px; font-weight:700; z-index:10; backdrop-filter:blur(8px); }
        .state-badge.yellow { background:rgba(245,158,11,.2); border:1px solid var(--yellow); color:var(--yellow); }
        .state-badge.green { background:rgba(16,185,129,.2); border:1px solid var(--green); color:var(--green); }
        
        .sidebar { width:380px; display:flex; flex-direction:column; gap:16px; }
        .result-card { background:var(--card); border-radius:16px; padding:18px 16px; border:1px solid rgba(255,255,255,.08); flex:1; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; min-height:170px; }
        .result-card .result-icon { font-size:36px; margin-bottom:6px; line-height:1; }
        .result-card .result-name { font-size:18px; font-weight:800; margin-bottom:2px; letter-spacing:-0.2px; line-height:1.3; }
        .result-card .result-dept { font-size:11.5px; color:#94A3B8; margin-bottom:4px; }
        .result-card .result-time { font-size:26px; font-weight:800; color:var(--cyan); margin:4px 0 8px 0; font-family:'Inter',sans-serif; }
        .result-card .result-status { font-size:11.5px; font-weight:600; padding:6px 14px; border-radius:10px; line-height:1.4; max-width:92%; margin:4px auto 0; display:inline-flex; align-items:center; justify-content:center; gap:6px; }
        .result-card .result-status.success { background:rgba(16,185,129,.14); color:#34D399; border:1px solid rgba(16,185,129,.3); }
        .result-card .result-status.warning { background:rgba(245,158,11,.14); color:#FBBF24; border:1px solid rgba(245,158,11,.3); }
        .result-card .result-status.error { background:rgba(239,68,68,.14); color:#F87171; border:1px solid rgba(239,68,68,.3); }

        .log-list { background:var(--card); border-radius:16px; padding:16px; border:1px solid rgba(255,255,255,.08); }
        .log-list-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; padding-bottom:6px; border-bottom:1px solid rgba(255,255,255,0.08); }
        .badge-recent-count { background:rgba(56,189,248,0.12); color:var(--cyan); border:1px solid rgba(56,189,248,0.25); font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:20px; }
        .log-item { display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:10px; margin-bottom:6px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05); font-size:12px; transition:all .2s; }
        .log-item img { width:34px; height:34px; border-radius:50%; object-fit:cover; border:1.5px solid var(--cyan); flex-shrink:0; }
        .log-info { flex:1; min-width:0; }
        .log-name { font-weight:700; font-size:12px; color:#F8FAFC; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .log-dept { color:#94A3B8; font-size:10.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .log-meta { text-align:right; flex-shrink:0; }
        .log-time { font-size:11px; font-weight:700; color:var(--cyan); margin-bottom:2px; font-family:'Inter',sans-serif; }
        .badge-status { font-size:9.5px; font-weight:700; padding:2px 7px; border-radius:10px; }
        .badge-status.in { background:rgba(16,185,129,0.15); color:#34D399; border:1px solid rgba(16,185,129,0.3); }
        .badge-status.out { background:rgba(245,158,11,0.15); color:#FBBF24; border:1px solid rgba(245,158,11,0.3); }

        /* Tablet Portrait & Mobile Stacked Layout */
        @media (max-width: 1024px) and (orientation: portrait), (max-width: 991px) {
            body {
                overflow-y: auto !important;
                overflow-x: hidden !important;
                height: auto !important;
                min-height: 100vh;
            }
            .kiosk-header {
                padding: 12px 18px;
            }
            .kiosk-brand {
                font-size: 15px;
            }
            .kiosk-brand-icon {
                width: 32px;
                height: 32px;
                font-size: 15px;
            }
            .live-clock {
                font-size: 15px;
                padding: 5px 14px;
            }
            .kiosk-body {
                flex-direction: column !important;
                height: auto !important;
                min-height: calc(100vh - 60px);
                padding: 14px 14px 28px 14px;
                gap: 16px;
            }
            .video-container {
                width: 100% !important;
                flex: none !important;
                height: 48vh !important;
                min-height: 340px !important;
                max-height: 480px !important;
                border-radius: 18px;
            }
            .blink-instruction {
                bottom: 16px;
                padding: 8px 20px;
                font-size: 14px;
                max-width: 90%;
            }
            .sidebar {
                width: 100% !important;
                flex: none !important;
                gap: 14px;
            }
            .result-card {
                border-radius: 16px;
                padding: 18px 16px !important;
                flex: none !important;
            }
            .log-list {
                max-height: none !important;
                overflow-y: visible !important;
                border-radius: 16px;
                padding: 14px !important;
            }
            .log-list .log-item:nth-child(n+6) {
                display: none !important;
            }
            .log-item {
                padding: 8px 10px !important;
                margin-bottom: 6px !important;
                border-radius: 10px !important;
            }
            .log-item img {
                width: 34px !important;
                height: 34px !important;
            }
        }

        /* Tablet Landscape Layout */
        @media (min-width: 768px) and (max-width: 1200px) and (orientation: landscape) {
            .kiosk-header {
                padding: 10px 24px;
            }
            .kiosk-body {
                padding: 12px 20px;
                gap: 16px;
                height: calc(100vh - 56px);
            }
            .sidebar {
                width: 340px;
                gap: 10px;
            }
            .result-card {
                padding: 14px 12px !important;
            }
            .result-card .result-icon { font-size: 30px; margin-bottom: 4px; }
            .result-card .result-name { font-size: 16px; }
            .result-card .result-time { font-size: 22px; margin: 2px 0 6px 0; }
            .log-list {
                padding: 12px !important;
                max-height: calc(100vh - 310px);
                overflow-y: auto;
            }
            .log-list .log-item:nth-child(n+6) {
                display: none !important;
            }
        }

        /* PIN modal */
        .pin-overlay { display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,.7); z-index:100; justify-content:center; align-items:center; }
        .pin-overlay.show { display:flex; }
        .pin-box { background:var(--card); border-radius:20px; padding:32px; width:400px; text-align:center; border:1px solid rgba(255,255,255,.1); }
        .pin-box input { width:100%; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.05); color:#fff; font-size:16px; margin:8px 0; text-align:center; }
        .pin-box .btn-pin { width:100%; padding:12px; border:none; border-radius:10px; font-weight:700; font-size:14px; cursor:pointer; margin-top:8px; }
        .pin-box .btn-verify { background:var(--indigo); color:#fff; }
        .pin-box .btn-cancel { background:rgba(255,255,255,.05); color:#94A3B8; }
        <?php if (isset($face_mode) && $face_mode === '1to1'): ?>
        .nik-prompt { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:var(--card); border-radius:20px; padding:32px; text-align:center; border:1px solid rgba(255,255,255,.1); z-index:20; }
        .nik-prompt input { width:240px; padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.05); color:#fff; font-size:18px; text-align:center; margin:12px 0; }
        <?php endif; ?>
    </style>
</head>
<body>
    <div class="kiosk-header">
        <div class="kiosk-brand">
            <div class="kiosk-brand-icon"><i class="fa-solid fa-fingerprint"></i></div>
            <?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS'; ?> • Kiosk Presensi
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="<?php echo base_url('face/register'); ?>" style="color:var(--cyan);text-decoration:none;font-size:12px;font-weight:600;">
                <i class="fa-solid fa-user-plus mr-1"></i>Registrasi
            </a>
            <div class="live-clock" id="live-clock">--:--:--</div>
        </div>
    </div>

    <div class="kiosk-body">
        <div class="video-container" id="video-container">
            <video id="kiosk-video" autoplay playsinline muted></video>
            <canvas id="kiosk-canvas"></canvas>
            <div class="state-badge yellow" id="state-badge">MENCARI WAJAH</div>
            <div class="blink-instruction searching" id="blink-instruction">Posisikan wajah Anda di depan kamera</div>
        </div>

        <div class="sidebar">
            <div class="result-card" id="result-card">
                <div class="result-icon">👤</div>
                <div style="font-size:13px;color:#94A3B8;">Menunggu presensi...</div>
            </div>
            <div class="log-list">
                <div class="log-list-header">
                    <div style="font-weight:700;font-size:13px;display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-clock-rotate-left" style="color:var(--cyan);"></i> 5 Presensi Terakhir
                    </div>
                    <span class="badge-recent-count">5 Terbaru</span>
                </div>
                <div id="recent-log-container">
                    <?php if (!empty($today_logs)): foreach ($today_logs as $log): 
                        $emp_photo = !empty($log->em_image) ? base_url('assets/images/users/'.$log->em_image) : base_url('assets/images/users/user.png');
                        $emp_name = trim($log->first_name.' '.$log->last_name);
                        $is_out = (!empty($log->signout_time) && $log->signout_time !== '00:00:00');
                        $time_str = $is_out ? substr($log->signout_time, 0, 5) : (!empty($log->signin_time) ? substr($log->signin_time, 0, 5) : '--:--');
                        $is_today = (date('Y-m-d', strtotime($log->atten_date)) === date('Y-m-d'));
                        $date_prefix = $is_today ? '' : date('d/m ', strtotime($log->atten_date));
                    ?>
                    <div class="log-item">
                        <img src="<?php echo $emp_photo; ?>" onerror="this.src='<?php echo base_url('assets/images/users/user.png'); ?>';">
                        <div class="log-info">
                            <div class="log-name"><?php echo $emp_name; ?></div>
                            <div class="log-dept"><?php echo $log->dep_name ?: '-'; ?> • <?php echo $log->em_code ?: $log->emp_id; ?></div>
                        </div>
                        <div class="log-meta">
                            <div class="log-time"><?php echo $date_prefix . $time_str; ?></div>
                            <?php if ($is_out): ?>
                                <span class="badge-status out">KELUAR</span>
                            <?php else: ?>
                                <span class="badge-status in">MASUK</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                    <div id="empty-log-placeholder" style="text-align:center;padding:20px 10px;color:#64748B;font-size:12px;">
                        <i class="fa-solid fa-clipboard-user mb-2" style="font-size:24px;opacity:0.4;"></i>
                        <div>Belum ada presensi tercatat.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- PIN Fallback Modal -->
    <div class="pin-overlay" id="pin-overlay">
        <div class="pin-box">
            <h5 style="font-weight:700;margin-bottom:4px;">Verifikasi NIK + PIN</h5>
            <p style="font-size:12px;color:#94A3B8;">Wajah tidak dapat diverifikasi. Gunakan NIK dan PIN sebagai alternatif.</p>
            <input type="text" id="pin-nik" placeholder="NIK Karyawan" maxlength="64">
            <input type="password" id="pin-pin" placeholder="PIN" maxlength="16">
            <button class="btn-pin btn-verify" onclick="submitPin()"><i class="fa-solid fa-check mr-1"></i>Verifikasi</button>
            <button class="btn-pin btn-cancel mt-2" onclick="closePin()">Batal</button>
            <div id="pin-msg" style="margin-top:8px;font-size:12px;display:none;"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module">
    import { FaceLandmarker, FilesetResolver } from 'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/vision_bundle.mjs';

    // ═══════════════════════════════════════════════════════════════════
    // STATE MACHINE
    // ═══════════════════════════════════════════════════════════════════
    // SEARCHING → READY → BLINK_OK → SENDING
    // Any time face disappears → reset to SEARCHING

    const STATE = { SEARCHING: 'SEARCHING', READY: 'READY', BLINK_OK: 'BLINK_OK', SENDING: 'SENDING' };
    let currentState = STATE.SEARCHING;
    let faceLandmarker = null;
    let videoStream = null;
    let currentNonce = null;

    // Blink detection state
    let eyesClosed = false;
    let eyeCloseTime = 0;
    let frameBeforeBlink = null;
    let frameAtBlink = null;
    let frameAfterBlink = null;
    let isSending = false;

    const BLINK_CLOSE_THRESHOLD = 0.5;
    const BLINK_OPEN_THRESHOLD = 0.20;
    const BLINK_MIN_MS = 80;
    const BLINK_MAX_MS = 500;

    // Clock
    setInterval(() => {
        const now = new Date();
        document.getElementById('live-clock').textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
    }, 1000);

    // Init
    async function init() {
        const filesetResolver = await FilesetResolver.forVisionTasks('https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/wasm');
        faceLandmarker = await FaceLandmarker.createFromOptions(filesetResolver, {
            baseOptions: { modelAssetPath: 'https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task', delegate: 'GPU' },
            outputFaceBlendshapes: true,
            outputFacialTransformationMatrixes: true,
            runningMode: 'VIDEO',
            numFaces: 2, // Detect up to 2 to enforce single-face rule
        });

        const video = document.getElementById('kiosk-video');
        videoStream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480, facingMode: 'user' } });
        video.srcObject = videoStream;
        await video.play();
        
        // Pre-fetch nonce
        await refreshNonce();
        
        detectLoop();
    }
    init();

    async function refreshNonce() {
        try {
            const res = await $.post('<?php echo base_url("face/api/challenge"); ?>', { purpose: 'verify' });
            if (res.success) currentNonce = res.data.nonce;
        } catch(e) { console.error('Nonce fetch failed'); }
    }

    function setState(newState) {
        currentState = newState;
        const badge = document.getElementById('state-badge');
        const instr = document.getElementById('blink-instruction');
        const borderEl = document.getElementById('video-container');

        switch(newState) {
            case STATE.SEARCHING:
                badge.textContent = 'MENCARI WAJAH';
                badge.className = 'state-badge yellow';
                instr.textContent = 'Posisikan wajah Anda di depan kamera';
                instr.className = 'blink-instruction searching';
                borderEl.style.borderColor = 'rgba(255,255,255,.1)';
                break;
            case STATE.READY:
                badge.textContent = 'WAJAH TERDETEKSI';
                badge.className = 'state-badge yellow';
                instr.textContent = '👁️ KEDIPKAN MATA untuk memulai presensi';
                instr.className = 'blink-instruction ready';
                borderEl.style.borderColor = '#F59E0B';
                break;
            case STATE.BLINK_OK:
                badge.textContent = 'KEDIP VALID ✓';
                badge.className = 'state-badge green';
                instr.textContent = 'Kedipan terverifikasi — mengirim...';
                instr.className = 'blink-instruction blink-ok';
                borderEl.style.borderColor = '#10B981';
                break;
            case STATE.SENDING:
                badge.textContent = 'MEMPROSES';
                badge.className = 'state-badge green';
                instr.textContent = 'Memproses presensi...';
                instr.className = 'blink-instruction sending';
                borderEl.style.borderColor = '#10B981';
                break;
        }
    }

    function captureFrame(video) {
        const c = document.createElement('canvas');
        c.width = video.videoWidth;
        c.height = video.videoHeight;
        const ctx = c.getContext('2d');
        ctx.drawImage(video, 0, 0); // NOT mirrored — raw frame for server
        return c.toDataURL('image/jpeg', 0.85);
    }

    function resetBlinkState() {
        eyesClosed = false;
        eyeCloseTime = 0;
        frameBeforeBlink = null;
        frameAtBlink = null;
        frameAfterBlink = null;
    }

    function detectLoop() {
        const video = document.getElementById('kiosk-video');
        const canvas = document.getElementById('kiosk-canvas');
        const ctx = canvas.getContext('2d');

        if (!faceLandmarker || !video.videoWidth) {
            requestAnimationFrame(detectLoop);
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const result = faceLandmarker.detectForVideo(video, performance.now());

        // ─── Multi-face rejection ───────────────────────────────────
        if (!result.faceLandmarks || result.faceLandmarks.length === 0) {
            // No face → reset to SEARCHING
            if (currentState !== STATE.SENDING) {
                setState(STATE.SEARCHING);
                resetBlinkState();
            }
            requestAnimationFrame(detectLoop);
            return;
        }

        if (result.faceLandmarks.length > 1) {
            // Multiple faces → reset, NEVER proceed
            setState(STATE.SEARCHING);
            resetBlinkState();
            document.getElementById('blink-instruction').textContent = '⚠️ Hanya boleh satu orang di depan kamera';
            requestAnimationFrame(detectLoop);
            return;
        }

        // ─── Single face detected ───────────────────────────────────
        const landmarks = result.faceLandmarks[0];
        const blendshapes = result.faceBlendshapes?.[0]?.categories || [];

        // Draw face bounding box (mirrored)
        ctx.save();
        ctx.scale(-1, 1);
        ctx.translate(-canvas.width, 0);

        let minX=1, maxX=0, minY=1, maxY=0;
        landmarks.forEach(p => { if(p.x<minX)minX=p.x; if(p.x>maxX)maxX=p.x; if(p.y<minY)minY=p.y; if(p.y>maxY)maxY=p.y; });
        const bx=minX*canvas.width, by=minY*canvas.height, bw=(maxX-minX)*canvas.width, bh=(maxY-minY)*canvas.height;

        const isGreen = currentState === STATE.BLINK_OK || currentState === STATE.SENDING;
        ctx.strokeStyle = isGreen ? '#10B981' : '#F59E0B';
        ctx.lineWidth = 3;
        ctx.strokeRect(bx, by, bw, bh);
        ctx.restore();

        // ─── Blink detection using blendshapes ──────────────────────
        if (currentState === STATE.SENDING || isSending) {
            requestAnimationFrame(detectLoop);
            return;
        }

        let blinkLeft = 0, blinkRight = 0;
        blendshapes.forEach(bs => {
            if (bs.categoryName === 'eyeBlinkLeft') blinkLeft = bs.score;
            if (bs.categoryName === 'eyeBlinkRight') blinkRight = bs.score;
        });
        const blinkAvg = (blinkLeft + blinkRight) / 2;

        if (currentState === STATE.SEARCHING) {
            // Check if face is suitable (centered, good size)
            const faceSize = Math.min(bw, bh);
            if (faceSize > 80) { // Face big enough
                setState(STATE.READY);
                // Store pre-blink frame
                frameBeforeBlink = captureFrame(video);
            }
        }

        if (currentState === STATE.READY) {
            // Detect eye closure
            if (!eyesClosed && blinkAvg > BLINK_CLOSE_THRESHOLD) {
                eyesClosed = true;
                eyeCloseTime = Date.now();
                frameAtBlink = captureFrame(video); // Eyes closed frame
            }

            // Detect eye re-opening
            if (eyesClosed && blinkAvg < BLINK_OPEN_THRESHOLD) {
                const duration = Date.now() - eyeCloseTime;
                if (duration >= BLINK_MIN_MS && duration <= BLINK_MAX_MS) {
                    // VALID BLINK!
                    frameAfterBlink = captureFrame(video); // Eyes open after blink — THIS is the main frame
                    setState(STATE.BLINK_OK);
                    // Send immediately
                    sendVerification();
                } else {
                    // Too long (eyes held shut) or too short — reject
                    eyesClosed = false;
                    eyeCloseTime = 0;
                    if (duration > BLINK_MAX_MS) {
                        document.getElementById('blink-instruction').textContent = '⚠️ Mata terlalu lama terpejam. Kedipkan secara alami.';
                    }
                }
            }
        }

        requestAnimationFrame(detectLoop);
    }

    async function sendVerification() {
        if (isSending) return;
        isSending = true;
        setState(STATE.SENDING);

        if (!currentNonce) await refreshNonce();

        try {
            const postData = {
                image_b64: frameAfterBlink, // Frame AFTER blink = eyes open, fresh
                nonce: currentNonce,
            };

            <?php if (isset($face_mode) && $face_mode === '1to1'): ?>
            // In 1:1 mode, em_code would be provided by a NIK input step
            // For now, use 1:N
            <?php endif; ?>

            const res = await $.post('<?php echo base_url("face/api/verify"); ?>', postData);

            if (res.success) {
                showResult(res.data, res.message, true);
            } else {
                if (res.offer_pin) {
                    showPinDialog();
                } else {
                    showResult(null, res.message, false);
                }
            }
        } catch(e) {
            showResult(null, 'Gagal terhubung ke server.', false);
        }

        // Reset after 5 seconds for next person
        setTimeout(() => {
            isSending = false;
            resetBlinkState();
            setState(STATE.SEARCHING);
            currentNonce = null;
            refreshNonce();
            clearResult();
        }, 5000);
    }

    function showResult(data, message, success) {
        const card = document.getElementById('result-card');
        if (success && data) {
            const isWarning = data.action === 'ALREADY_CHECKED_IN';
            const icon = isWarning ? 'ℹ️' : (data.is_late ? '⏰' : '✅');
            const statusClass = isWarning ? 'warning' : 'success';
            const statusIcon = isWarning ? '<i class="fa-solid fa-circle-info mr-1"></i>' : '<i class="fa-solid fa-circle-check mr-1"></i>';

            card.innerHTML = `
                <div class="result-icon">${icon}</div>
                <div class="result-name">${data.employee_name || ''}</div>
                <div class="result-dept">${data.department || ''} • ${data.em_code || ''}</div>
                <div class="result-time">${data.time || ''}</div>
                <div class="result-status ${statusClass}">${statusIcon}<span>${message}</span></div>
            `;

            // If actual attendance record was created (not duplicate restriction warning), add to 5 recent list
            if (!isWarning) {
                addRecentLogItem(data);
            }
        } else {
            card.innerHTML = `
                <div class="result-icon">❌</div>
                <div class="result-status error"><i class="fa-solid fa-circle-xmark mr-1"></i><span>${message}</span></div>
            `;
        }
    }

    function addRecentLogItem(data) {
        const container = document.getElementById('recent-log-container');
        if (!container) return;

        const placeholder = document.getElementById('empty-log-placeholder');
        if (placeholder) placeholder.remove();

        const isOut = data.attendance_type === 'out' || data.action === 'CHECK_OUT';
        const badgeHtml = isOut 
            ? '<span class="badge-status out">KELUAR</span>'
            : '<span class="badge-status in">MASUK</span>';

        const photoUrl = data.photo_url || '<?php echo base_url("assets/images/users/user.png"); ?>';
        const timeStr = data.time ? data.time.substring(0, 5) : '';

        const itemHtml = `
            <div class="log-item">
                <img src="${photoUrl}" onerror="this.src='<?php echo base_url("assets/images/users/user.png"); ?>';">
                <div class="log-info">
                    <div class="log-name">${data.employee_name || ''}</div>
                    <div class="log-dept">${data.department || ''} • ${data.em_code || ''}</div>
                </div>
                <div class="log-meta">
                    <div class="log-time">${timeStr}</div>
                    ${badgeHtml}
                </div>
            </div>
        `;

        container.insertAdjacentHTML('afterbegin', itemHtml);

        // Keep strictly 5 items
        while (container.children.length > 5) {
            container.removeChild(container.lastElementChild);
        }
    }

    function clearResult() {
        document.getElementById('result-card').innerHTML = `
            <div class="result-icon">👤</div>
            <div style="font-size:13px;color:#94A3B8;">Menunggu presensi...</div>
        `;
    }

    // ─── PIN fallback ───────────────────────────────────────────────
    window.showPinDialog = function() {
        document.getElementById('pin-overlay').classList.add('show');
        document.getElementById('pin-nik').value = '';
        document.getElementById('pin-pin').value = '';
        document.getElementById('pin-msg').style.display = 'none';
        document.getElementById('pin-nik').focus();
    };

    window.closePin = function() {
        document.getElementById('pin-overlay').classList.remove('show');
    };

    window.submitPin = async function() {
        const nik = document.getElementById('pin-nik').value.trim();
        const pin = document.getElementById('pin-pin').value.trim();
        if (!nik || !pin) {
            document.getElementById('pin-msg').textContent = 'NIK dan PIN wajib diisi.';
            document.getElementById('pin-msg').style.display = 'block';
            document.getElementById('pin-msg').style.color = '#EF4444';
            return;
        }

        if (!currentNonce) await refreshNonce();

        try {
            const res = await $.post('<?php echo base_url("face/api/verify-pin"); ?>', {
                em_code: nik,
                pin: pin,
                image_b64: frameAfterBlink || '',
                nonce: currentNonce || '',
            });

            if (res.success) {
                closePin();
                showResult(res.data, res.message, true);
                setTimeout(() => {
                    isSending = false;
                    resetBlinkState();
                    setState(STATE.SEARCHING);
                    refreshNonce();
                    clearResult();
                }, 5000);
            } else {
                document.getElementById('pin-msg').textContent = res.message;
                document.getElementById('pin-msg').style.display = 'block';
                document.getElementById('pin-msg').style.color = '#EF4444';
            }
        } catch(e) {
            document.getElementById('pin-msg').textContent = 'Gagal terhubung ke server.';
            document.getElementById('pin-msg').style.display = 'block';
        }
    };
    </script>
</body>
</html>
