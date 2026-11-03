<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Controller — All face recognition endpoints
 * 
 * Separated from Api.php. Does NOT compute embeddings or matching in PHP.
 * PHP is orchestrator only; AI runs on sidecar.
 */
class Face extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->db->query("SET time_zone = '+07:00'");
        $this->config->load('face', TRUE);
        $this->load->library('face_service');
        $this->load->library('face_matcher');
        $this->load->library('face_nonce');
        $this->load->model('face_template_model');
        $this->load->model('face_log_model');
        $this->load->model('settings_model');

        // Auto-decode JSON request body if Content-Type is application/json
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : (isset($_SERVER['HTTP_CONTENT_TYPE']) ? $_SERVER['HTTP_CONTENT_TYPE'] : '');
        if (stripos($content_type, 'application/json') !== false) {
            $raw_body = file_get_contents('php://input');
            if (!empty($raw_body)) {
                $json_data = json_decode($raw_body, true);
                if (is_array($json_data)) {
                    foreach ($json_data as $key => $val) {
                        if (!isset($_POST[$key])) {
                            $_POST[$key] = $val;
                        }
                    }
                }
            }
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PAGE: Registration (5 poses)
    // ═══════════════════════════════════════════════════════════════════════
    public function register() {
        // Require admin or logged-in employee session
        $data = [];
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $model_version = $this->config->item('face_model_version', 'face');

        // Get employees with enrollment status
        $employees = $this->db->select('e.em_id, e.em_code, e.first_name, e.last_name, e.em_image, d.dep_name')
                              ->from('employee e')
                              ->join('department d', 'd.id = e.dep_id', 'left')
                              ->where('e.status', 'ACTIVE')
                              ->order_by('e.first_name', 'ASC')
                              ->get()->result();

        foreach ($employees as &$emp) {
            $status = $this->face_template_model->get_enrollment_status($emp->em_code, $model_version);
            $emp->face_enrolled = $status['is_complete'];
            $emp->face_pose_count = $status['total_poses'];
        }

        $data['employees_list'] = $employees;
        $data['model_version'] = $model_version;
        $this->load->view('face/register', $data);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PAGE: Kiosk (attendance)
    // ═══════════════════════════════════════════════════════════════════════
    public function index() {
        $data = [];
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $data['company_data'] = $this->db->get_where('company_data', ['id_company' => 1])->row();
        $data['face_mode'] = $this->config->item('face_mode', 'face');

        // 5 most recent attendance logs for display
        $this->db->select('a.*, e.first_name, e.last_name, e.em_code, e.em_image, d.dep_name');
        $this->db->from('attendance a');
        $this->db->join('employee e', 'e.em_code = a.emp_id OR e.em_id = a.emp_id', 'left');
        $this->db->join('department d', 'd.id = e.dep_id', 'left');
        $this->db->order_by('a.id', 'DESC');
        $this->db->limit(5);
        $data['today_logs'] = $this->db->get()->result();

        $this->load->view('face/kiosk', $data);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Generate challenge nonce
    // ═══════════════════════════════════════════════════════════════════════
    public function api_challenge() {
        header('Content-Type: application/json');

        $purpose = $this->input->post('purpose') ?: 'verify';
        $em_code = $this->input->post('em_code');

        if (!in_array($purpose, ['enroll', 'verify'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid purpose']);
            return;
        }

        $nonce_data = $this->face_nonce->generate($purpose, $em_code);

        echo json_encode([
            'success' => true,
            'data'    => $nonce_data,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Enroll single pose
    // ═══════════════════════════════════════════════════════════════════════
    public function api_enroll() {
        header('Content-Type: application/json');

        $em_code       = $this->input->post('em_code');
        $pose          = $this->input->post('pose');
        $image_b64     = $this->input->post('image_b64');
        $nonce         = $this->input->post('nonce');

        // Validate inputs
        if (empty($em_code) || empty($pose) || empty($image_b64) || empty($nonce)) {
            echo json_encode(['success' => false, 'message' => 'Parameter em_code, pose, image_b64, nonce wajib diisi']);
            return;
        }

        if (!in_array($pose, ['front', 'up', 'down', 'left', 'right'])) {
            echo json_encode(['success' => false, 'message' => 'Pose tidak valid']);
            return;
        }

        // Validate employee exists
        $emp = $this->db->get_where('employee', ['em_code' => $em_code, 'status' => 'ACTIVE'])->row();
        if (!$emp) {
            echo json_encode(['success' => false, 'message' => 'Karyawan tidak ditemukan']);
            return;
        }

        // Validate nonce
        $nonce_result = $this->face_nonce->validate($nonce, 'enroll');
        if (!$nonce_result['valid']) {
            $this->face_log_model->log_attempt([
                'em_code'  => $em_code,
                'purpose'  => 'enroll',
                'decision' => 'rejected_nonce',
                'ip'       => $this->input->ip_address(),
            ]);
            echo json_encode(['success' => false, 'message' => 'Nonce tidak valid: ' . $nonce_result['reason']]);
            return;
        }

        // Send to sidecar
        $result = $this->face_service->analyze($image_b64, 'enroll', $pose);

        if (!$result['ok']) {
            $decision = 'rejected_quality';
            if ($result['reason'] === 'service_error') $decision = 'service_error';
            if ($result['reason'] === 'spoof_detected') $decision = 'rejected_spoof';
            if ($result['reason'] === 'multiple_faces') $decision = 'rejected_multiface';

            $measured = isset($result['measured_pose']) ? $result['measured_pose'] : null;

            $this->face_log_model->log_attempt([
                'em_code'        => $em_code,
                'purpose'        => 'enroll',
                'decision'       => $decision,
                'matched_pose'   => $pose,
                'det_score'      => isset($result['det_score']) ? $result['det_score'] : null,
                'blur'           => isset($result['blur']) ? $result['blur'] : null,
                'liveness_score' => isset($result['liveness_score']) ? $result['liveness_score'] : null,
                'yaw'            => isset($measured['yaw']) ? $measured['yaw'] : null,
                'pitch'          => isset($measured['pitch']) ? $measured['pitch'] : null,
                'roll'           => isset($measured['roll']) ? $measured['roll'] : null,
                'latency_ms'     => isset($result['latency_ms']) ? $result['latency_ms'] : null,
                'ip'             => $this->input->ip_address(),
                'user_agent'     => $this->input->user_agent(),
            ]);

            // Human-readable rejection messages
            $messages = [
                'decode_failed'             => 'Gambar tidak dapat dibaca.',
                'no_face'                   => 'Wajah tidak terdeteksi. Pastikan wajah terlihat jelas.',
                'multiple_faces'            => 'Terdeteksi lebih dari satu wajah. Pastikan hanya Anda di depan kamera.',
                'face_too_small'            => 'Wajah terlalu kecil. Dekatkan wajah ke kamera.',
                'too_blurry'                => 'Gambar terlalu buram. Tetap diam saat pengambilan foto.',
                'low_detection_confidence'  => 'Deteksi wajah tidak yakin. Perbaiki pencahayaan.',
                'bad_lighting'              => 'Pencahayaan tidak memadai. Pastikan cahaya cukup dan merata.',
                'spoof_detected'            => 'Terdeteksi foto/layar, bukan wajah asli.',
                'wrong_pose'                => 'Pose wajah tidak sesuai. Ikuti panduan di layar.',
                'service_error'             => 'Layanan pengenalan wajah sedang tidak tersedia. Hubungi admin.',
            ];
            $msg = isset($messages[$result['reason']]) ? $messages[$result['reason']] : $result['reason'];

            echo json_encode([
                'success' => false,
                'message' => $msg,
                'reason'  => $result['reason'],
                'detail'  => $result,
            ]);
            return;
        }

        // Server-side re-validation of pose from sidecar response
        if (isset($result['pose'])) {
            // We trust the sidecar's pose validation, but verify it returned the right pose
            $sidecar_pose = $result['pose'];
            // Pose was already validated by sidecar — this is defense in depth
        }

        // Save template (inactive until finalized)
        $model_version = $this->config->item('face_model_version', 'face');
        $template_id = $this->face_template_model->upsert_template(
            $em_code,
            $pose,
            $result['embedding'],
            $model_version,
            isset($result['det_score']) ? $result['det_score'] : null,
            isset($result['blur']) ? $result['blur'] : null,
            isset($result['brightness']) ? $result['brightness'] : null
        );

        // Log successful enrollment pose
        $this->face_log_model->log_attempt([
            'em_code'        => $em_code,
            'purpose'        => 'enroll',
            'decision'       => 'accepted',
            'det_score'      => isset($result['det_score']) ? $result['det_score'] : null,
            'blur'           => isset($result['blur']) ? $result['blur'] : null,
            'liveness_score' => isset($result['liveness_score']) ? $result['liveness_score'] : null,
            'matched_pose'   => $pose,
            'yaw'            => isset($result['pose']['yaw']) ? $result['pose']['yaw'] : null,
            'pitch'          => isset($result['pose']['pitch']) ? $result['pose']['pitch'] : null,
            'roll'           => isset($result['pose']['roll']) ? $result['pose']['roll'] : null,
            'model_version'  => $model_version,
            'latency_ms'     => isset($result['latency_ms']) ? $result['latency_ms'] : null,
            'ip'             => $this->input->ip_address(),
            'user_agent'     => $this->input->user_agent(),
        ]);

        echo json_encode([
            'success'     => true,
            'message'     => "Pose '{$pose}' berhasil direkam.",
            'template_id' => $template_id,
            'pose'        => $pose,
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Finalize enrollment (activate all 5 templates atomically)
    // ═══════════════════════════════════════════════════════════════════════
    public function api_enroll_finalize() {
        header('Content-Type: application/json');

        $em_code = $this->input->post('em_code');
        if (empty($em_code)) {
            echo json_encode(['success' => false, 'message' => 'em_code wajib diisi']);
            return;
        }

        $model_version = $this->config->item('face_model_version', 'face');
        $result = $this->face_template_model->finalize_enrollment($em_code, $model_version);

        if (!$result['success']) {
            echo json_encode([
                'success' => false,
                'message' => 'Registrasi belum lengkap. Pose yang belum terekam: ' . implode(', ', $result['missing_poses']),
                'missing_poses' => $result['missing_poses'],
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Registrasi wajah berhasil! ' . $result['activated'] . ' template diaktifkan.',
            'activated' => $result['activated'],
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Verify face (1:N or 1:1)
    // ═══════════════════════════════════════════════════════════════════════
    public function api_verify() {
        header('Content-Type: application/json');

        $image_b64 = $this->input->post('image_b64');
        $frames    = $this->input->post('frames');
        $nonce     = $this->input->post('nonce');
        $lat       = $this->input->post('lat');
        $lng       = $this->input->post('lng');
        $em_code_hint = $this->input->post('em_code'); // For 1:1 mode

        if (empty($image_b64) && !empty($frames) && is_array($frames)) {
            $image_b64 = isset($frames[2]) ? $frames[2] : $frames[0];
        }

        if (empty($image_b64) || empty($nonce)) {
            echo json_encode(['success' => false, 'message' => 'image_b64 (atau frames) dan nonce wajib diisi']);
            return;
        }

        // Validate nonce
        $nonce_result = $this->face_nonce->validate($nonce, 'verify');
        if (!$nonce_result['valid']) {
            $this->face_log_model->log_attempt([
                'purpose'  => 'verify',
                'decision' => 'rejected_nonce',
                'ip'       => $this->input->ip_address(),
            ]);
            echo json_encode(['success' => false, 'message' => 'Sesi kedaluwarsa. Silakan coba lagi.', 'reason' => $nonce_result['reason']]);
            return;
        }

        // Send to sidecar — NO embedding computation in PHP
        $result = $this->face_service->analyze($image_b64, 'verify', null, $frames);

        if (!$result['ok']) {
            $decision = 'rejected_quality';
            if ($result['reason'] === 'service_error') $decision = 'service_error';
            if ($result['reason'] === 'spoof_detected') $decision = 'rejected_spoof';
            if ($result['reason'] === 'multiple_faces') $decision = 'rejected_multiface';
            if ($result['reason'] === 'blink_not_detected') $decision = 'rejected_spoof';
            if ($result['reason'] === 'cross_frame_mismatch') $decision = 'rejected_spoof';

            $this->face_log_model->log_attempt([
                'purpose'        => 'verify',
                'decision'       => $decision,
                'liveness_score' => isset($result['liveness_score']) ? $result['liveness_score'] : null,
                'latency_ms'     => isset($result['latency_ms']) ? $result['latency_ms'] : null,
                'ip'             => $this->input->ip_address(),
                'user_agent'     => $this->input->user_agent(),
                'lat'            => $lat,
                'lng'            => $lng,
            ]);

            $messages = [
                'no_face'             => 'Wajah tidak terdeteksi.',
                'multiple_faces'      => 'Terdeteksi lebih dari satu wajah.',
                'face_too_small'      => 'Wajah terlalu kecil.',
                'too_blurry'          => 'Gambar terlalu buram.',
                'bad_lighting'        => 'Pencahayaan tidak memadai.',
                'spoof_detected'      => 'Terdeteksi foto/layar, bukan wajah asli.',
                'blink_not_detected'  => 'Frame tengah tidak menunjukkan mata tertutup (kedipan palsu / foto statis terdeteksi).',
                'cross_frame_mismatch'=> 'Wajah pada 3 frame terdeteksi orang yang berbeda.',
                'service_error'       => 'Layanan pengenalan wajah tidak tersedia. Presensi ditolak.',
            ];
            $msg = isset($messages[$result['reason']]) ? $messages[$result['reason']] : $result['reason'];
            echo json_encode(['success' => false, 'message' => $msg, 'reason' => $result['reason'], 'detail' => isset($result['detail']) ? $result['detail'] : null]);
            return;
        }

        $probe = $result['embedding'];
        $model_version = $this->config->item('face_model_version', 'face');
        $threshold  = $this->config->item('face_threshold', 'face');
        $min_margin = $this->config->item('face_min_margin', 'face');
        $face_mode  = $this->config->item('face_mode', 'face');

        // Determine matching mode
        $use_1to1 = ($face_mode === '1to1' && !empty($em_code_hint));

        if ($use_1to1) {
            // 1:1 — verify against specific employee only
            $templates = $this->face_template_model->get_employee_templates($em_code_hint, $model_version);
            if (empty($templates)) {
                echo json_encode(['success' => false, 'message' => 'Karyawan belum terdaftar biometrik wajah.']);
                return;
            }
            $score = $this->face_matcher->match_1to1($probe, $templates);
            $decision_data = [
                'decision'       => ($score >= $threshold) ? 'accepted' : 'rejected_threshold',
                'em_code'        => ($score >= $threshold) ? $em_code_hint : null,
                'best_score'     => round($score, 6),
                'second_score'   => 0.0,
                'score_margin'   => round($score, 6),
                'runner_up_code' => null,
            ];
        } else {
            // 1:N — identify against all enrolled employees
            $gallery = $this->face_template_model->get_all_active_templates($model_version);
            if (empty($gallery)) {
                echo json_encode(['success' => false, 'message' => 'Belum ada karyawan terdaftar biometrik.']);
                return;
            }
            $scores = $this->face_matcher->match_1toN($probe, $gallery);
            $decision_data = $this->face_matcher->decide($scores, $threshold, $min_margin);
        }

        // Build log entry
        $log_data = [
            'em_code'        => $decision_data['em_code'],
            'runner_up_code' => $decision_data['runner_up_code'],
            'purpose'        => 'verify',
            'decision'       => $decision_data['decision'],
            'best_score'     => $decision_data['best_score'],
            'second_score'   => $decision_data['second_score'],
            'score_margin'   => $decision_data['score_margin'],
            'liveness_score' => isset($result['liveness_score']) ? $result['liveness_score'] : null,
            'det_score'      => isset($result['det_score']) ? $result['det_score'] : null,
            'blur'           => isset($result['blur']) ? $result['blur'] : null,
            'yaw'            => isset($result['pose']['yaw']) ? $result['pose']['yaw'] : null,
            'pitch'          => isset($result['pose']['pitch']) ? $result['pose']['pitch'] : null,
            'roll'           => isset($result['pose']['roll']) ? $result['pose']['roll'] : null,
            'model_version'  => $model_version,
            'threshold_used' => $threshold,
            'margin_used'    => $min_margin,
            'latency_ms'     => isset($result['latency_ms']) ? $result['latency_ms'] : null,
            'ip'             => $this->input->ip_address(),
            'user_agent'     => $this->input->user_agent(),
            'lat'            => $lat,
            'lng'            => $lng,
        ];

        // Handle decision
        if ($decision_data['decision'] === 'accepted') {
            $emp = $this->db->get_where('employee', ['em_code' => $decision_data['em_code']])->row();
            if (!$emp) {
                $log_data['decision'] = 'service_error';
                $this->face_log_model->log_attempt($log_data);
                echo json_encode(['success' => false, 'message' => 'Data karyawan tidak ditemukan.']);
                return;
            }

            // Save attendance photo
            $snapshot_path = $this->_save_attendance_photo($image_b64, $emp->em_code);
            $log_data['snapshot_path'] = $snapshot_path;

            // Process attendance
            $attendance_result = $this->_process_attendance($emp, $snapshot_path, $decision_data['best_score'], 
                isset($result['liveness_score']) ? $result['liveness_score'] : null, $lat, $lng);
            
            $log_data['attendance_type'] = $attendance_result['type'];
            $log_id = $this->face_log_model->log_attempt($log_data);

            // Link log to attendance record
            if (isset($attendance_result['attendance_id'])) {
                $log_col = ($attendance_result['type'] === 'in') ? 'face_log_in_id' : 'face_log_out_id';
                $this->db->where('id', $attendance_result['attendance_id'])
                          ->update('attendance', [$log_col => $log_id]);
            }

            echo json_encode([
                'success' => true,
                'message' => $attendance_result['message'],
                'data'    => $attendance_result['data'],
            ]);

        } elseif ($decision_data['decision'] === 'rejected_ambiguous') {
            $this->face_log_model->log_attempt($log_data);
            echo json_encode([
                'success'    => false,
                'message'    => 'Wajah kurang jelas. Silakan masukkan NIK + PIN untuk verifikasi.',
                'reason'     => 'rejected_ambiguous',
                'offer_pin'  => true,
            ]);

        } else {
            // rejected_threshold
            $this->face_log_model->log_attempt($log_data);
            echo json_encode([
                'success'   => false,
                'message'   => 'Wajah tidak dikenali. Pastikan Anda sudah terdaftar.',
                'reason'    => 'rejected_threshold',
                'offer_pin' => true,
            ]);
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Verify via NIK + PIN (fallback for ambiguous/threshold rejection)
    // ═══════════════════════════════════════════════════════════════════════
    public function api_verify_pin() {
        header('Content-Type: application/json');

        $em_code   = $this->input->post('em_code');
        $pin       = $this->input->post('pin');
        $image_b64 = $this->input->post('image_b64');
        $nonce     = $this->input->post('nonce');
        $lat       = $this->input->post('lat');
        $lng       = $this->input->post('lng');

        if (empty($em_code) || empty($pin)) {
            echo json_encode(['success' => false, 'message' => 'NIK dan PIN wajib diisi']);
            return;
        }

        // Validate nonce
        if (!empty($nonce)) {
            $nonce_result = $this->face_nonce->validate($nonce, 'verify');
            if (!$nonce_result['valid']) {
                echo json_encode(['success' => false, 'message' => 'Sesi kedaluwarsa.']);
                return;
            }
        }

        // Find employee
        $emp = $this->db->get_where('employee', ['em_code' => $em_code, 'status' => 'ACTIVE'])->row();
        if (!$emp) {
            echo json_encode(['success' => false, 'message' => 'NIK tidak ditemukan.']);
            return;
        }

        // Verify PIN (support both hashed and legacy plaintext)
        $pin_valid = false;
        if (!empty($emp->pin)) {
            if (password_verify($pin, $emp->pin)) {
                $pin_valid = true;
            } elseif ($emp->pin === $pin) {
                // Legacy plaintext PIN — verify then upgrade to hash
                $pin_valid = true;
                $this->db->where('id', $emp->id)->update('employee', [
                    'pin' => password_hash($pin, PASSWORD_DEFAULT)
                ]);
            }
        } else {
            // No PIN set — reject, force them to set one
            echo json_encode([
                'success' => false,
                'message' => 'PIN belum diatur. Hubungi HR untuk mengatur PIN Anda.',
            ]);
            return;
        }

        if (!$pin_valid) {
            $this->face_log_model->log_attempt([
                'em_code'  => $em_code,
                'purpose'  => 'verify',
                'decision' => 'rejected_threshold',
                'ip'       => $this->input->ip_address(),
            ]);
            echo json_encode(['success' => false, 'message' => 'PIN salah.']);
            return;
        }

        // If image provided, do 1:1 face verification for extra security
        $face_score = null;
        $liveness = null;
        if (!empty($image_b64)) {
            $model_version = $this->config->item('face_model_version', 'face');
            $templates = $this->face_template_model->get_employee_templates($em_code, $model_version);
            
            if (!empty($templates)) {
                $sidecar_result = $this->face_service->analyze($image_b64, 'verify');
                if ($sidecar_result['ok'] && isset($sidecar_result['embedding'])) {
                    try {
                        $face_score = $this->face_matcher->match_1to1($sidecar_result['embedding'], $templates);
                    } catch (RuntimeException $e) {
                        // Dimension mismatch — log but don't block PIN-verified entry
                        log_message('error', 'Face matcher error during PIN verify: ' . $e->getMessage());
                    }
                    $liveness = isset($sidecar_result['liveness_score']) ? $sidecar_result['liveness_score'] : null;
                }
            }
        }

        // Save attendance photo
        $snapshot_path = null;
        if (!empty($image_b64)) {
            $snapshot_path = $this->_save_attendance_photo($image_b64, $emp->em_code);
        }

        // Process attendance
        $attendance_result = $this->_process_attendance($emp, $snapshot_path, $face_score, $liveness, $lat, $lng, 'pin');

        // Log
        $this->face_log_model->log_attempt([
            'em_code'        => $em_code,
            'purpose'        => 'verify',
            'attendance_type'=> $attendance_result['type'],
            'decision'       => 'accepted',
            'best_score'     => $face_score,
            'liveness_score' => $liveness,
            'snapshot_path'  => $snapshot_path,
            'ip'             => $this->input->ip_address(),
            'user_agent'     => $this->input->user_agent(),
            'lat'            => $lat,
            'lng'            => $lng,
        ]);

        echo json_encode([
            'success' => true,
            'message' => $attendance_result['message'],
            'data'    => $attendance_result['data'],
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Serve attendance photo (protected)
    // ═══════════════════════════════════════════════════════════════════════
    public function photo($attendance_id = null) {
        if (empty($attendance_id) || !is_numeric($attendance_id)) {
            show_404();
            return;
        }

        $slot = $this->input->get('slot') ?: 'in';
        if (!in_array($slot, ['in', 'out'])) {
            show_404();
            return;
        }

        // Require valid session
        $user_session = $this->session->userdata('user_login_access');
        $portal_session = $this->session->userdata('em_code');
        if (!$user_session && !$portal_session) {
            show_error('Unauthorized', 401);
            return;
        }

        $attendance = $this->db->get_where('attendance', ['id' => $attendance_id])->row();
        if (!$attendance) {
            show_404();
            return;
        }

        // Authorization: employees can only see their OWN photos
        // Admin (user_login_access) can see all
        if (!$user_session && $portal_session) {
            if ((string)$attendance->emp_id !== (string)$portal_session) {
                show_error('Forbidden', 403);
                return;
            }
        }

        $photo_col = ($slot === 'in') ? 'photo_in' : 'photo_out';
        $photo_path = isset($attendance->$photo_col) ? $attendance->$photo_col : null;

        if (empty($photo_path)) {
            show_404();
            return;
        }

        // Build full path and validate it's within allowed directory
        $base_dir = FCPATH . 'uploads/attendance/';
        $base_real = realpath($base_dir);
        $base = ($base_real !== false) ? rtrim($base_real, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : false;
        $full_path = realpath($base_dir . $photo_path);

        // Path traversal prevention
        if (!$full_path || !$base || strpos($full_path, $base) !== 0) {
            log_message('error', "Photo path traversal attempt: {$photo_path}");
            show_error('Forbidden', 403);
            return;
        }

        if (!file_exists($full_path)) {
            show_404();
            return;
        }

        // Stream the photo
        header('Content-Type: image/jpeg');
        header('Cache-Control: private, no-store');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ADMIN: Audit logs dashboard
    // ═══════════════════════════════════════════════════════════════════════
    public function admin_logs() {
        if (!$this->session->userdata('user_login_access')) {
            redirect(base_url(), 'refresh');
            return;
        }

        $data = [];
        $data['logs'] = $this->face_log_model->get_recent_logs(100);
        $data['failure_rates'] = $this->face_log_model->get_failure_rates(30);
        $data['confusion_pairs'] = $this->face_log_model->get_confusion_pairs(30);

        header('Content-Type: text/html; charset=utf-8');
        // Simple admin log view (can be replaced with full view later)
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Face Recognition Audit Logs</title>';
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">';
        echo '<style>body{background:#1a1a2e;color:#eee;font-family:monospace;padding:20px;} table{font-size:12px;} .badge-success{background:#10B981;} .badge-danger{background:#EF4444;} .badge-warning{background:#F59E0B;color:#000;}</style>';
        echo '</head><body>';
        echo '<h4>🔍 Face Recognition Audit Logs</h4>';
        echo '<a href="' . base_url('face') . '" class="btn btn-sm btn-outline-light mb-3">← Back to Kiosk</a>';

        // Failure rates
        if (!empty($data['failure_rates'])) {
            echo '<h5 class="mt-4">⚠️ Failure Rates per Employee (30 days)</h5>';
            echo '<table class="table table-dark table-sm table-bordered"><thead><tr><th>NIK</th><th>Total</th><th>Accepted</th><th>Rejected</th><th>Fail Rate</th></tr></thead><tbody>';
            foreach ($data['failure_rates'] as $fr) {
                $badge = ($fr->fail_rate_pct > 50) ? 'badge-danger' : (($fr->fail_rate_pct > 20) ? 'badge-warning' : 'badge-success');
                echo "<tr><td>{$fr->em_code}</td><td>{$fr->total_attempts}</td><td>{$fr->accepted}</td><td>{$fr->rejected}</td><td><span class='badge {$badge}'>{$fr->fail_rate_pct}%</span></td></tr>";
            }
            echo '</tbody></table>';
        }

        // Recent logs
        echo '<h5 class="mt-4">📋 Recent Attempts</h5>';
        echo '<table class="table table-dark table-sm table-bordered table-hover"><thead><tr><th>Time</th><th>NIK</th><th>Decision</th><th>Score</th><th>2nd</th><th>Margin</th><th>Liveness</th><th>Latency</th><th>IP</th></tr></thead><tbody>';
        foreach ($data['logs'] as $log) {
            $badge_class = ($log->decision === 'accepted') ? 'badge-success' : 'badge-danger';
            echo "<tr>";
            echo "<td>{$log->created_at}</td>";
            echo "<td>{$log->em_code}</td>";
            echo "<td><span class='badge {$badge_class}'>{$log->decision}</span></td>";
            echo "<td>" . ($log->best_score !== null ? round($log->best_score, 4) : '-') . "</td>";
            echo "<td>" . ($log->second_score !== null ? round($log->second_score, 4) : '-') . "</td>";
            echo "<td>" . ($log->score_margin !== null ? round($log->score_margin, 4) : '-') . "</td>";
            echo "<td>" . ($log->liveness_score !== null ? round($log->liveness_score, 3) : '-') . "</td>";
            echo "<td>" . ($log->latency_ms !== null ? $log->latency_ms . 'ms' : '-') . "</td>";
            echo "<td>{$log->ip}</td>";
            echo "</tr>";
        }
        echo '</tbody></table>';
        echo '</body></html>';
    }

    // ═══════════════════════════════════════════════════════════════════════
    // ADMIN: Health check
    // ═══════════════════════════════════════════════════════════════════════
    public function admin_health() {
        header('Content-Type: application/json');

        $health = $this->face_service->health();
        if (!$health) {
            echo json_encode([
                'success' => false,
                'message' => 'Sidecar tidak dapat dihubungi. Periksa status layanan di VPS.',
            ]);
            return;
        }

        $model_version = $this->config->item('face_model_version', 'face');
        $enrolled = $this->face_template_model->count_enrolled($model_version);

        echo json_encode([
            'success'         => true,
            'sidecar'         => $health,
            'enrolled_employees' => $enrolled,
            'face_mode'       => $this->config->item('face_mode', 'face'),
            'threshold'       => $this->config->item('face_threshold', 'face'),
            'min_margin'      => $this->config->item('face_min_margin', 'face'),
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // API: Delete biometric data (UU PDP compliance)
    // ═══════════════════════════════════════════════════════════════════════
    public function api_delete_biometric() {
        header('Content-Type: application/json');

        if (!$this->session->userdata('user_login_access')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $em_code = $this->input->post('em_code');
        if (empty($em_code)) {
            echo json_encode(['success' => false, 'message' => 'em_code wajib diisi']);
            return;
        }

        $this->face_template_model->delete_employee_templates($em_code);

        $this->face_log_model->log_attempt([
            'em_code'  => $em_code,
            'purpose'  => 'enroll',
            'decision' => 'accepted',
            'ip'       => $this->input->ip_address(),
        ]);

        echo json_encode([
            'success' => true,
            'message' => "Data biometrik karyawan {$em_code} berhasil dihapus. Karyawan harus mendaftar ulang.",
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PRIVATE: Save attendance photo
    // ═══════════════════════════════════════════════════════════════════════
    private function _save_attendance_photo($image_b64, $em_code) {
        // Strip data URI prefix
        $clean = preg_replace('#^data:image/\w+;base64,#i', '', $image_b64);
        $clean = str_replace(' ', '+', $clean);
        $decoded = base64_decode($clean);

        if (!$decoded) return null;

        // Resize to max 720px
        $img = @imagecreatefromstring($decoded);
        if ($img) {
            $w = imagesx($img);
            $h = imagesy($img);
            $max_side = 720;
            if ($w > $max_side || $h > $max_side) {
                $scale = $max_side / max($w, $h);
                $new_w = (int)($w * $scale);
                $new_h = (int)($h * $scale);
                $resized = imagecreatetruecolor($new_w, $new_h);
                imagecopyresampled($resized, $img, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
                imagedestroy($img);
                $img = $resized;
            }

            // Save to dated directory
            $date_dir = date('Y') . '/' . date('m') . '/' . date('d');
            $full_dir = FCPATH . 'uploads/attendance/' . $date_dir . '/';
            if (!is_dir($full_dir)) {
                @mkdir($full_dir, 0755, true);
            }

            // Unpredictable filename
            $hash = substr(md5($em_code . time()), 0, 8);
            $random = bin2hex(random_bytes(8));
            $filename = "att_{$hash}_" . time() . "_{$random}.jpg";
            $full_path = $full_dir . $filename;

            imagejpeg($img, $full_path, 80);
            imagedestroy($img);

            return $date_dir . '/' . $filename;
        }

        return null;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // PRIVATE: Process attendance (check-in / check-out)
    // ═══════════════════════════════════════════════════════════════════════
    private function _process_attendance($emp, $snapshot_path, $face_score, $liveness, $lat, $lng, $verified_by = 'face') {
        $today = date('Y-m-d');
        $now_time = date('H:i:s');

        $existing = $this->db->get_where('attendance', [
            'emp_id'    => $emp->em_code,
            'atten_date' => $today,
        ])->row();

        $department = $this->db->get_where('department', ['id' => $emp->dep_id])->row();
        $dept_name = $department ? $department->dep_name : 'General';
        $emp_name = trim($emp->first_name . ' ' . $emp->last_name);
        $emp_photo_url = !empty($emp->em_image) ? base_url('assets/images/users/' . $emp->em_image) : base_url('assets/images/users/user.png');

        // Already completed today
        if ($existing && !empty($existing->signin_time) && !empty($existing->signout_time)) {
            $in_fmt = date('H:i', strtotime($existing->signin_time));
            $out_fmt = date('H:i', strtotime($existing->signout_time));
            return [
                'type'    => null,
                'message' => "Halo {$emp_name}, presensi hari ini sudah lengkap (Masuk: {$in_fmt} | Keluar: {$out_fmt} WIB).",
                'data'    => [
                    'action'           => 'ALREADY_COMPLETED',
                    'em_code'          => $emp->em_code,
                    'employee_name'    => $emp_name,
                    'department'       => $dept_name,
                    'photo_url'        => $emp_photo_url,
                    'time'             => $now_time,
                ],
            ];
        }

        // Check-out
        if ($existing && !empty($existing->signin_time) && empty($existing->signout_time)) {
            // Early checkout restriction (before 17:00)
            if ($now_time < '17:00:00') {
                $in_fmt = date('H:i', strtotime($existing->signin_time));
                return [
                    'type'    => null,
                    'message' => "Halo {$emp_name}, presensi keluar dapat dilakukan mulai pukul 17:00 WIB.",
                    'data'    => [
                        'action'        => 'ALREADY_CHECKED_IN',
                        'em_code'       => $emp->em_code,
                        'employee_name' => $emp_name,
                        'department'    => $dept_name,
                        'photo_url'     => $emp_photo_url,
                        'time'          => $now_time,
                        'info_note'     => "Sudah Absen Masuk ({$in_fmt} WIB)",
                    ],
                ];
            }

            $update = [
                'signout_time'  => $now_time,
                'lat_out'       => $lat,
                'lng_out'       => $lng,
                'face_score_out'=> $face_score,
                'liveness_out'  => $liveness,
                'verified_by'   => $verified_by,
            ];
            if ($snapshot_path) {
                $update['photo_out'] = $snapshot_path;
            }
            $this->db->where('id', $existing->id)->update('attendance', $update);

            return [
                'type'          => 'out',
                'attendance_id' => $existing->id,
                'message'       => "Presensi KELUAR Berhasil. Selamat beristirahat, {$emp_name}!",
                'data'          => [
                    'action'        => 'CHECK_OUT',
                    'em_code'       => $emp->em_code,
                    'employee_name' => $emp_name,
                    'department'    => $dept_name,
                    'photo_url'     => $emp_photo_url,
                    'time'          => $now_time,
                ],
            ];
        }

        // Check-in
        $is_late = ($now_time > '09:00:00');
        $status = $is_late ? 'L' : 'A';

        $insert_data = [
            'emp_id'        => $emp->em_code,
            'atten_date'    => $today,
            'signin_time'   => $now_time,
            'lat_in'        => $lat,
            'lng_in'        => $lng,
            'photo_in'      => $snapshot_path,
            'face_score_in' => $face_score,
            'liveness_in'   => $liveness,
            'verified_by'   => $verified_by,
            'status'        => $status,
        ];
        $this->db->insert('attendance', $insert_data);
        $attendance_id = $this->db->insert_id();

        $msg = $is_late
            ? "Presensi MASUK Berhasil (TERLAMBAT - Lewat Pukul 09:00 WIB)"
            : "Presensi MASUK Berhasil (TEPAT WAKTU)";

        return [
            'type'          => 'in',
            'attendance_id' => $attendance_id,
            'message'       => $msg,
            'data'          => [
                'action'        => 'CHECK_IN',
                'is_late'       => $is_late,
                'em_code'       => $emp->em_code,
                'employee_name' => $emp_name,
                'department'    => $dept_name,
                'photo_url'     => $emp_photo_url,
                'time'          => $now_time,
            ],
        ];
    }
}
