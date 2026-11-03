<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Wajah | <?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg:#0F172A; --card:#1E293B; --accent:#6366F1; --green:#10B981; --red:#EF4444; --cyan:#38BDF8; --text:#F8FAFC; }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
        .top-bar { padding:16px 30px; background:rgba(30,41,59,.9); backdrop-filter:blur(12px); border-bottom:1px solid rgba(255,255,255,.08); display:flex; align-items:center; justify-content:space-between; }
        .top-bar h5 { font-weight:700; margin:0; }
        .main-wrap { display:flex; height:calc(100vh - 60px); }
        .sidebar-list { width:340px; background:var(--card); border-right:1px solid rgba(255,255,255,.06); overflow-y:auto; padding:16px; }
        .search-box { width:100%; padding:10px 14px; border-radius:10px; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.05); color:#fff; margin-bottom:12px; font-size:14px; }
        .emp-item { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:10px; cursor:pointer; transition:.2s; margin-bottom:4px; }
        .emp-item:hover { background:rgba(99,102,241,.15); }
        .emp-item.active { background:rgba(99,102,241,.25); border:1px solid var(--accent); }
        .emp-item img { width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,.1); }
        .emp-name { font-weight:600; font-size:13px; }
        .emp-dept { font-size:11px; color:#94A3B8; }
        .face-badge { font-size:10px; padding:2px 8px; border-radius:20px; font-weight:700; }
        .face-badge.yes { background:rgba(16,185,129,.15); color:var(--green); }
        .face-badge.no { background:rgba(239,68,68,.15); color:var(--red); }
        .content-area { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px; }
        .video-box { position:relative; width:500px; height:400px; background:#000; border-radius:20px; overflow:hidden; border:3px solid rgba(255,255,255,.1); }
        .video-box video { width:100%; height:100%; object-fit:cover; transform:scaleX(-1); }
        .video-box canvas { position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; }
        .pose-guide { position:absolute; bottom:16px; left:50%; transform:translateX(-50%); background:rgba(15,23,42,.85); backdrop-filter:blur(8px); padding:10px 24px; border-radius:12px; text-align:center; font-weight:600; font-size:14px; border:1px solid rgba(255,255,255,.1); }
        .progress-steps { display:flex; gap:8px; margin:20px 0; }
        .step-dot { width:48px; height:6px; border-radius:3px; background:rgba(255,255,255,.1); transition:.3s; }
        .step-dot.done { background:var(--green); }
        .step-dot.active { background:var(--accent); animation:pulse 1s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.5;} }
        .pose-label { font-size:12px; font-weight:700; color:var(--cyan); text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; }
        .btn-enroll { background:var(--accent); border:none; color:#fff; padding:12px 32px; border-radius:12px; font-weight:700; font-size:14px; cursor:pointer; margin-top:16px; }
        .btn-enroll:disabled { opacity:.4; cursor:not-allowed; }
        .consent-box { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:12px; padding:16px; margin:16px 0; max-width:500px; font-size:12px; color:#94A3B8; }
        .status-msg { margin-top:12px; padding:10px 20px; border-radius:10px; font-size:13px; font-weight:600; text-align:center; }
        .status-msg.success { background:rgba(16,185,129,.15); color:var(--green); }
        .status-msg.error { background:rgba(239,68,68,.15); color:var(--red); }
        .status-msg.info { background:rgba(56,189,248,.1); color:var(--cyan); }
    </style>
</head>
<body>
    <div class="top-bar">
        <h5><i class="fa-solid fa-user-shield mr-2"></i>Registrasi Biometrik Wajah</h5>
        <div>
            <a href="<?php echo base_url('face'); ?>" class="btn btn-sm btn-outline-light mr-2"><i class="fa-solid fa-camera mr-1"></i>Kiosk</a>
            <a href="<?php echo base_url('face/admin/logs'); ?>" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-list mr-1"></i>Logs</a>
        </div>
    </div>
    <div class="main-wrap">
        <div class="sidebar-list">
            <input type="text" class="search-box" placeholder="Cari karyawan..." id="search-emp" oninput="filterEmployees()">
            <div id="emp-list">
                <?php foreach ($employees_list as $emp): ?>
                <div class="emp-item" data-emcode="<?php echo $emp->em_code; ?>" data-name="<?php echo trim($emp->first_name.' '.$emp->last_name); ?>" onclick="selectEmployee(this)">
                    <img src="<?php echo !empty($emp->em_image) ? base_url('assets/images/users/'.$emp->em_image) : base_url('assets/images/users/user.png'); ?>" alt="">
                    <div style="flex:1">
                        <div class="emp-name"><?php echo trim($emp->first_name.' '.$emp->last_name); ?></div>
                        <div class="emp-dept"><?php echo $emp->em_code; ?> • <?php echo $emp->dep_name ?: '-'; ?></div>
                    </div>
                    <span class="face-badge <?php echo $emp->face_enrolled ? 'yes' : 'no'; ?>">
                        <?php echo $emp->face_enrolled ? '✓ Terdaftar' : '✗ Belum'; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="content-area" id="content-area">
            <div id="state-idle" style="text-align:center">
                <i class="fa-solid fa-user-plus" style="font-size:48px;color:var(--accent);opacity:.5;"></i>
                <h5 class="mt-3">Pilih Karyawan</h5>
                <p style="color:#94A3B8;font-size:13px;">Pilih karyawan dari daftar di sebelah kiri untuk memulai registrasi wajah.</p>
            </div>
            <div id="state-enroll" style="display:none;">
                <div id="selected-emp-bar" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <img id="sel-photo" src="" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--accent);">
                    <div>
                        <div id="sel-name" style="font-weight:700;font-size:16px;"></div>
                        <div id="sel-detail" style="font-size:12px;color:#94A3B8;"></div>
                    </div>
                </div>

                <div class="consent-box">
                    <input type="checkbox" id="consent-check"> 
                    <label for="consent-check" style="cursor:pointer;">
                        Saya menyetujui pengumpulan dan pemrosesan data biometrik wajah saya untuk keperluan presensi, 
                        sesuai dengan UU Perlindungan Data Pribadi No. 27/2022. Data akan disimpan selama masa kerja 
                        dan dapat dihapus atas permintaan.
                    </label>
                </div>

                <div class="pose-label" id="pose-label">POSE: DEPAN</div>
                <div class="progress-steps" id="progress-steps">
                    <div class="step-dot active" data-pose="front"></div>
                    <div class="step-dot" data-pose="left"></div>
                    <div class="step-dot" data-pose="right"></div>
                    <div class="step-dot" data-pose="up"></div>
                    <div class="step-dot" data-pose="down"></div>
                </div>

                <div class="video-box" id="video-box">
                    <video id="reg-video" autoplay playsinline muted></video>
                    <canvas id="reg-canvas"></canvas>
                    <div class="pose-guide" id="pose-guide">Hadapkan wajah ke depan</div>
                </div>

                <div id="status-msg" class="status-msg info" style="display:none;max-width:500px;"></div>
                <button class="btn-enroll" id="btn-finalize" disabled onclick="finalizeEnrollment()">
                    <i class="fa-solid fa-check-double mr-1"></i> Selesaikan Registrasi
                </button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module">
    // ═══════════════════════════════════════════════════════════════════════
    // MediaPipe Face Landmarker (blendshapes + transformation matrix)
    // REPLACES face-api.js. NO embedding computation in browser.
    // ═══════════════════════════════════════════════════════════════════════
    import { FaceLandmarker, FilesetResolver } from 'https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/vision_bundle.mjs';

    let faceLandmarker = null;
    let videoStream = null;
    let animFrameId = null;
    let selectedEmCode = null;
    let currentPoseIdx = 0;
    let poseStableStart = 0;
    const STABLE_DURATION_MS = 800;

    const POSES = [
        { key:'front', label:'DEPAN',  guide:'Hadapkan wajah lurus ke depan', yaw:[-10,10], pitch:[-10,10] },
        { key:'left',  label:'KIRI',   guide:'Tolehkan wajah ke KIRI Anda', yaw:[12,28], pitch:[-15,15] },
        { key:'right', label:'KANAN',  guide:'Tolehkan wajah ke KANAN Anda', yaw:[-28,-12], pitch:[-15,15] },
        { key:'up',    label:'ATAS',   guide:'Dongakkan wajah sedikit ke atas', yaw:[-15,15], pitch:[8,25] },
        { key:'down',  label:'BAWAH',  guide:'Tundukkan wajah sedikit ke bawah', yaw:[-15,15], pitch:[-25,-8] },
    ];
    let completedPoses = new Set();

    // Init MediaPipe
    async function initFaceLandmarker() {
        const filesetResolver = await FilesetResolver.forVisionTasks('https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@latest/wasm');
        faceLandmarker = await FaceLandmarker.createFromOptions(filesetResolver, {
            baseOptions: { modelAssetPath: 'https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task', delegate: 'GPU' },
            outputFaceBlendshapes: true,
            outputFacialTransformationMatrixes: true,
            runningMode: 'VIDEO',
            numFaces: 1,
        });
        console.log('FaceLandmarker initialized');
    }
    initFaceLandmarker();

    // Expose functions to global scope
    window.selectEmployee = function(el) {
        document.querySelectorAll('.emp-item').forEach(e => e.classList.remove('active'));
        el.classList.add('active');
        selectedEmCode = el.dataset.emcode;
        const name = el.dataset.name;
        const img = el.querySelector('img').src;
        const detail = el.querySelector('.emp-dept').textContent;

        document.getElementById('sel-photo').src = img;
        document.getElementById('sel-name').textContent = name;
        document.getElementById('sel-detail').textContent = detail;
        document.getElementById('state-idle').style.display = 'none';
        document.getElementById('state-enroll').style.display = 'block';
        document.getElementById('consent-check').checked = false;

        resetEnrollment();
        startCamera();
    };

    window.filterEmployees = function() {
        const q = document.getElementById('search-emp').value.toLowerCase();
        document.querySelectorAll('.emp-item').forEach(el => {
            const name = el.dataset.name.toLowerCase();
            const code = el.dataset.emcode.toLowerCase();
            el.style.display = (name.includes(q) || code.includes(q)) ? '' : 'none';
        });
    };

    function resetEnrollment() {
        currentPoseIdx = 0;
        completedPoses.clear();
        poseStableStart = 0;
        updatePoseUI();
        document.getElementById('btn-finalize').disabled = true;
        hideStatus();
    }

    function updatePoseUI() {
        const pose = POSES[currentPoseIdx];
        document.getElementById('pose-label').textContent = `POSE: ${pose.label}`;
        document.getElementById('pose-guide').textContent = pose.guide;

        document.querySelectorAll('.step-dot').forEach((dot, i) => {
            dot.classList.remove('active', 'done');
            if (completedPoses.has(POSES[i].key)) dot.classList.add('done');
            if (i === currentPoseIdx && !completedPoses.has(pose.key)) dot.classList.add('active');
        });
    }

    async function startCamera() {
        if (videoStream) {
            videoStream.getTracks().forEach(t => t.stop());
        }
        const video = document.getElementById('reg-video');
        videoStream = await navigator.mediaDevices.getUserMedia({ video: { width:640, height:480, facingMode:'user' } });
        video.srcObject = videoStream;
        await video.play();
        detectLoop();
    }

    function getYawPitchFromMatrix(matrix) {
        // 4x4 transformation matrix from MediaPipe — row-major
        // Tilt UP is positive pitch, Tilt DOWN is negative pitch
        const m = matrix.data || matrix;
        const r00 = m[0], r10 = m[1], r20 = m[2];
        const r01 = m[4], r11 = m[5], r21 = m[6];
        const r02 = m[8], r12 = m[9], r22 = m[10];

        const pitch = Math.asin(Math.max(-1, Math.min(1, r12))) * (180 / Math.PI);
        const yaw = Math.atan2(r02, r22) * (180 / Math.PI);
        const roll = Math.atan2(r10, r11) * (180 / Math.PI);
        return { yaw, pitch, roll };
    }

    function detectLoop() {
        const video = document.getElementById('reg-video');
        const canvas = document.getElementById('reg-canvas');
        const ctx = canvas.getContext('2d');

        if (!faceLandmarker || !video.videoWidth) {
            animFrameId = requestAnimationFrame(detectLoop);
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const result = faceLandmarker.detectForVideo(video, performance.now());

        if (result.faceLandmarks && result.faceLandmarks.length === 1) {
            const landmarks = result.faceLandmarks[0];
            // Draw face oval
            ctx.save();
            ctx.scale(-1, 1);
            ctx.translate(-canvas.width, 0);

            // Get bounding box from landmarks
            let minX = 1, maxX = 0, minY = 1, maxY = 0;
            landmarks.forEach(p => {
                if (p.x < minX) minX = p.x;
                if (p.x > maxX) maxX = p.x;
                if (p.y < minY) minY = p.y;
                if (p.y > maxY) maxY = p.y;
            });

            const bx = minX * canvas.width, by = minY * canvas.height;
            const bw = (maxX - minX) * canvas.width, bh = (maxY - minY) * canvas.height;

            // Get pose from transformation matrix
            let yaw = 0, pitch = 0;
            if (result.facialTransformationMatrixes && result.facialTransformationMatrixes.length > 0) {
                const angles = getYawPitchFromMatrix(result.facialTransformationMatrixes[0]);
                yaw = angles.yaw;
                pitch = angles.pitch;
            }

            const pose = POSES[currentPoseIdx];
            const inRange = yaw >= pose.yaw[0] && yaw <= pose.yaw[1] && pitch >= pose.pitch[0] && pitch <= pose.pitch[1];

            if (inRange && !completedPoses.has(pose.key)) {
                ctx.strokeStyle = '#10B981';
                ctx.lineWidth = 3;

                if (poseStableStart === 0) poseStableStart = Date.now();
                const elapsed = Date.now() - poseStableStart;
                const progress = Math.min(elapsed / STABLE_DURATION_MS, 1);

                // Draw progress arc
                ctx.beginPath();
                ctx.arc(bx + bw / 2, by - 12, 12, -Math.PI / 2, -Math.PI / 2 + progress * Math.PI * 2);
                ctx.stroke();

                if (elapsed >= STABLE_DURATION_MS) {
                    // Capture! Send to server
                    captureAndEnroll(video, pose.key);
                    poseStableStart = 0;
                }
            } else {
                ctx.strokeStyle = inRange ? '#10B981' : '#F59E0B';
                ctx.lineWidth = 2;
                poseStableStart = 0;
            }

            ctx.strokeRect(bx, by, bw, bh);
            ctx.restore();

            // Live Angle HUD (rendered in non-mirrored coordinate space)
            ctx.save();
            ctx.font = '600 13px system-ui, -apple-system, sans-serif';
            ctx.fillStyle = inRange ? '#10B981' : '#E2E8F0';
            ctx.shadowColor = 'rgba(0,0,0,0.85)';
            ctx.shadowBlur = 4;
            ctx.fillText(`Yaw: ${yaw > 0 ? '+' : ''}${yaw.toFixed(0)}°  |  Pitch: ${pitch > 0 ? '+' : ''}${pitch.toFixed(0)}°`, 16, 28);
            ctx.restore();
        } else {
            poseStableStart = 0;
        }

        animFrameId = requestAnimationFrame(detectLoop);
    }

    async function captureAndEnroll(video, poseKey) {
        if (completedPoses.has(poseKey)) return;
        if (!document.getElementById('consent-check').checked) {
            showStatus('Centang persetujuan data biometrik terlebih dahulu.', 'error');
            return;
        }

        // Capture UN-MIRRORED frame
        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        const cctx = captureCanvas.getContext('2d');
        cctx.drawImage(video, 0, 0); // NOT mirrored
        const imageB64 = captureCanvas.toDataURL('image/jpeg', 0.90);

        showStatus(`Mengirim pose ${poseKey}...`, 'info');

        try {
            // Get nonce
            const nonceRes = await $.post('<?php echo base_url("face/api/challenge"); ?>', { purpose: 'enroll', em_code: selectedEmCode });
            if (!nonceRes.success) { showStatus('Gagal mendapat nonce.', 'error'); return; }

            // Send to server
            const enrollRes = await $.post('<?php echo base_url("face/api/enroll"); ?>', {
                em_code: selectedEmCode,
                pose: poseKey,
                image_b64: imageB64,
                nonce: nonceRes.data.nonce,
            });

            if (enrollRes.success) {
                completedPoses.add(poseKey);
                showStatus(`✓ Pose ${poseKey} berhasil!`, 'success');

                // Move to next pose
                if (currentPoseIdx < POSES.length - 1) {
                    currentPoseIdx++;
                    updatePoseUI();
                } else {
                    document.getElementById('btn-finalize').disabled = false;
                    showStatus('Semua 5 pose terekam! Klik "Selesaikan Registrasi" untuk mengaktifkan.', 'success');
                }
                updatePoseUI();
            } else {
                let errMsg = enrollRes.message || 'Gagal';
                if (enrollRes.detail && enrollRes.detail.measured_pose) {
                    const mp = enrollRes.detail.measured_pose;
                    errMsg += ` (Terbaca Yaw: ${mp.yaw}°, Pitch: ${mp.pitch}°)`;
                }
                showStatus(`✗ ${errMsg} — ulangi pose ini.`, 'error');
                poseStableStart = 0;
            }
        } catch (e) {
            showStatus('Gagal terhubung ke server.', 'error');
            poseStableStart = 0;
        }
    }

    window.finalizeEnrollment = async function() {
        if (completedPoses.size < 5) {
            showStatus('Lengkapi semua 5 pose terlebih dahulu.', 'error');
            return;
        }

        document.getElementById('btn-finalize').disabled = true;
        showStatus('Mengaktifkan template...', 'info');

        try {
            const res = await $.post('<?php echo base_url("face/api/enroll/finalize"); ?>', { em_code: selectedEmCode });
            if (res.success) {
                showStatus('✅ ' + res.message, 'success');
                // Update sidebar badge
                const item = document.querySelector(`.emp-item[data-emcode="${selectedEmCode}"]`);
                if (item) {
                    const badge = item.querySelector('.face-badge');
                    badge.className = 'face-badge yes';
                    badge.textContent = '✓ Terdaftar';
                }
                // Stop camera
                if (videoStream) videoStream.getTracks().forEach(t => t.stop());
                if (animFrameId) cancelAnimationFrame(animFrameId);
            } else {
                showStatus('✗ ' + (res.message || 'Gagal finalize'), 'error');
                document.getElementById('btn-finalize').disabled = false;
            }
        } catch(e) {
            showStatus('Gagal terhubung ke server.', 'error');
            document.getElementById('btn-finalize').disabled = false;
        }
    };

    function showStatus(msg, type) {
        const el = document.getElementById('status-msg');
        el.textContent = msg;
        el.className = 'status-msg ' + type;
        el.style.display = 'block';
    }
    function hideStatus() {
        document.getElementById('status-msg').style.display = 'none';
    }
    </script>
</body>
</html>
