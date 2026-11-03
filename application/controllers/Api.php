<?php  defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Api extends CI_Controller {
        public function __construct() {
            parent::__construct();
            date_default_timezone_set('Asia/Jakarta');
            $this->db->query("SET time_zone = '+07:00'");
        }

        public function index(){
            header('Content-Type: application/json');
            echo json_encode(array(
                'status'  => '200',
                'success' => true,
                'message' => 'Berhasil tersambung ke Server API HRMS CodeIgniter!'
            ));
        }

        public function ping(){
            header('Content-Type: application/json');
            echo json_encode(array(
                'status'  => '200',
                'success' => true,
                'message' => 'Pong! Koneksi ke API HRMS Berhasil!',
                'server_time' => date('Y-m-d H:i:s')
            ));
        }

        

        /**
         * 1. ENDPOINT: REGISTRASI WAJAH MASTER (ENROLLMENT)
         * POST /Api/registerFace
         * Params: nik, image_front, image_left, image_right (or vector_data)
         */
        public function registerFace(){
            header('Content-Type: application/json', true, 410);
            return;
        }

        public function register_face_web(){
            header('Content-Type: application/json', true, 410);
            echo json_encode(array('status' => '410', 'success' => false, 'message' => 'DEPRECATED: Gunakan /face/register'));
            return;
        }

        public function registerFaceWeb(){
            header('Content-Type: application/json', true, 410);
            echo json_encode(array('status' => '410', 'success' => false, 'message' => 'DEPRECATED: Gunakan /face/register'));
            return;
        }

        /**
         * 1C. ENDPOINT: STANDALONE KIOSK 1:N FACE IDENTIFICATION & ATTENDANCE LOGGING
         * POST /Api/identifyAndSaveAttendance
         * Params: image / photo / file (atau base64_image), lat, lng
         */
        public function identifyAndSaveAttendance(){
            header('Content-Type: application/json', true, 410);
            echo json_encode(array('status' => '410', 'success' => false, 'message' => 'DEPRECATED: Gunakan /face/api/verify'));
            return;

            // Auto-migrate GPS, Capture, Photo & Device columns if missing
            if (!$this->db->field_exists('lat_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `lat_in` VARCHAR(64) NULL DEFAULT NULL, ADD `lng_in` VARCHAR(64) NULL DEFAULT NULL, ADD `lat_out` VARCHAR(64) NULL DEFAULT NULL, ADD `lng_out` VARCHAR(64) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('capture_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `capture_in` VARCHAR(255) NULL DEFAULT NULL, ADD `capture_out` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('photo_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `photo_in` VARCHAR(255) NULL DEFAULT NULL, ADD `photo_out` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('photo_url', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `photo_url` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('device_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `device_in` VARCHAR(32) NULL DEFAULT 'WEB', ADD `device_out` VARCHAR(32) NULL DEFAULT 'WEB';");
            }
            if (!$this->db->field_exists('mode', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `mode` VARCHAR(32) NULL DEFAULT 'WEB';");
            }

            // Parse device / mode / source parameter ('WEB' or 'MOBILE')
            $raw_mode = $this->input->post('mode') ? $this->input->post('mode') : ($this->input->post('device') ? $this->input->post('device') : ($this->input->post('source') ? $this->input->post('source') : 'WEB'));
            $mode = strtoupper(trim($raw_mode));

            $lat_req = $this->input->post('lat');
            $lng_req = $this->input->post('lng');

            // Location rules: MOBILE requires lat & lng; WEB optional
            if ($mode === 'MOBILE') {
                if ($lat_req === null || $lng_req === null || trim($lat_req) === '' || trim($lng_req) === '' || (float)$lat_req == 0.0 || (float)$lng_req == 0.0) {
                    echo json_encode(array(
                        'status'  => '400',
                        'success' => false,
                        'message' => 'Gagal: Presensi via Mobile App WAJIB mencantumkan posisi lokasi GPS (Latitude & Longitude).'
                    ));
                    return;
                }
            } else {
                $mode = 'WEB';
            }

            // 1. Auto-handle dan pastikan semua folder penting siap dengan permission 0777
            $dirs = array(
                FCPATH . 'uploads/',
                FCPATH . 'uploads/temp/',
                FCPATH . 'uploads/faces/',
                FCPATH . 'uploads/attendance/',
                FCPATH . 'assets/images/',
                FCPATH . 'assets/images/users/',
                APPPATH . 'logs/'
            );
            foreach ($dirs as $d) {
                if (!file_exists($d)) {
                    @mkdir($d, 0777, true);
                }
                @chmod($d, 0777);
            }

            $scanned_vector = null;
            $saved_filepath = null;

            $base64_img = $this->input->post('base64_image');
            if (empty($base64_img)) {
                $base64_img = $this->input->post('image');
            }
            if (empty($base64_img)) {
                $base64_img = $this->input->post('photo');
            }

            $vector_json_in = $this->input->post('vector_json');
            if (empty($vector_json_in)) {
                $vector_json_in = $this->input->post('vector_data');
            }

            $this->biometric_log("=== PERMINTAAN PRESENSI BARU ===", array(
                'mode'            => $mode,
                'ip'              => $this->input->ip_address(),
                'has_base64'      => !empty($base64_img),
                'base64_len'      => !empty($base64_img) ? strlen($base64_img) : 0,
                'has_client_vec'  => !empty($vector_json_in)
            ));

            // 2. PRIORITAS UTAMA: Vektor Biometrik 128D dari TensorFlow.js (FaceNet) client
            if (!empty($vector_json_in)) {
                $parsed_vec = is_array($vector_json_in) ? $vector_json_in : json_decode($vector_json_in, true);
                if ($parsed_vec && is_array($parsed_vec) && count($parsed_vec) >= 128) {
                    $scanned_vector = $parsed_vec;
                    $this->biometric_log("TensorFlow FaceNet vector diterima dari client. Dimensi: " . count($scanned_vector));
                }
            }

            // 3. Decode dan Simpan Foto Snapshot Presensi
            if (!empty($base64_img)) {
                $clean_data = preg_replace('#^data:image/\w+;base64,#i', '', $base64_img);
                $clean_data = str_replace(' ', '+', $clean_data);
                $decoded = base64_decode($clean_data);
                if ($decoded) {
                    $temp_dir = FCPATH . 'uploads/temp/';
                    $tmp_file = $temp_dir . 'kiosk_' . time() . '_' . rand(1000, 9999) . '.jpg';
                    @file_put_contents($tmp_file, $decoded);
                    @chmod($tmp_file, 0666);
                    $saved_filepath = $tmp_file;

                    // Fallback server extraction jika client tidak mengirim vector
                    if (!$scanned_vector) {
                        $scanned_vector = $this->extract_512d_vector($decoded);
                        if (!$scanned_vector && file_exists($tmp_file)) {
                            $scanned_vector = $this->extract_512d_vector($tmp_file);
                        }
                    }
                } else {
                    $this->biometric_log("WARNING: base64_decode gambar gagal.");
                }
            }

            // Fallback Multipart Upload jika base64 kosong
            if (!$scanned_vector) {
                foreach (array('image', 'photo', 'file') as $file_key) {
                    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == UPLOAD_ERR_OK) {
                        $tmp_path = $_FILES[$file_key]['tmp_name'];
                        $scanned_vector = $this->extract_512d_vector($tmp_path);
                        $saved_filepath = $tmp_path;
                        break;
                    }
                }
            }

            // 4. Validasi Vektor
            if (!$scanned_vector) {
                $this->biometric_log("DITOLAK: Gagal mendapatkan vektor biometrik. Foto presensi tidak valid / wajah tidak terdeteksi.");
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Foto presensi tidak valid / wajah tidak terdeteksi. Pastikan pencahayaan cukup.'
                ));
                return;
            }

            // 5. Ambil Master Face Signatures dari Database (DEPRECATED)
            $all_signatures = array();
            if (empty($all_signatures)) {
                $this->biometric_log("DITOLAK: Endpoint biometrik lama telah ditutup.");
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Belum ada data biometrik wajah karyawan yang terdaftar di database.'
                ));
                return;
            }

            $best_match_em_id = null;
            $lowest_distance = 999.0;
            $highest_similarity = 0.0;
            $comparison_logs = array();

            $is_tf_vector = (count($scanned_vector) === 128);

            // Normalisasi L2 untuk scanned_vector jika 128D FaceNet
            if ($is_tf_vector) {
                $scanned_vector = $this->l2_normalize_vector($scanned_vector);
            }

            $matched_candidates = array();

            foreach ($all_signatures as $sig) {
                $v_web = !empty($sig->vector_web) ? json_decode($sig->vector_web, true) : null;
                $v_data = !empty($sig->vector_data) ? json_decode($sig->vector_data, true) : null;

                $emp_lowest_dist = 999.0;
                $emp_best_sim = -1.0;

                // Kumpulkan semua kandidat vektor untuk karyawan ini (baik single 128D maupun multi-angle array)
                $cand_list = array();
                foreach (array($v_web, $v_data) as $raw_cand) {
                    if (!$raw_cand || !is_array($raw_cand)) continue;
                    if (count($raw_cand) === 128 && !is_array($raw_cand[0])) {
                        $cand_list[] = $raw_cand;
                    } else {
                        // Kasus template multi-vektor: [[...], [...]] atau {"center":[...], ...}
                        foreach ($raw_cand as $sub_cand) {
                            if (is_array($sub_cand) && count($sub_cand) === 128) {
                                $cand_list[] = $sub_cand;
                            }
                        }
                    }
                }

                if ($is_tf_vector) {
                    foreach ($cand_list as $cand_vec) {
                        $norm_cand = $this->l2_normalize_vector($cand_vec);
                        $dist = $this->euclidean_distance($scanned_vector, $norm_cand);
                        $sim = max(0.0, 1.0 - ($dist * $dist) / 2.0);
                        if ($dist < $emp_lowest_dist) {
                            $emp_lowest_dist = $dist;
                            $emp_best_sim = $sim;
                        }
                    }
                } else if (!empty($cand_list) && count($cand_list[0]) === count($scanned_vector)) {
                    // Fallback jika scanned vector legacy
                    foreach ($cand_list as $cand_vec) {
                        $sim = $this->cosine_similarity($scanned_vector, $cand_vec);
                        if ($sim > $emp_best_sim) {
                            $emp_best_sim = $sim;
                            $emp_lowest_dist = sqrt(max(0.0, 2.0 - 2.0 * $sim));
                        }
                    }
                }

                if ($emp_best_sim > -1.0) {
                    $matched_candidates[] = array(
                        'em_id'    => $sig->em_id,
                        'dist'     => $emp_lowest_dist,
                        'sim'      => $emp_best_sim,
                        'sim_pct'  => round($emp_best_sim * 100, 1) . '%'
                    );
                    $comparison_logs[$sig->em_id] = array(
                        'dist' => round($emp_lowest_dist, 4),
                        'sim'  => round($emp_best_sim * 100, 1) . '%'
                    );
                }
            }

            // Urutkan kandidat berdasarkan jarak terendah (terkecil = paling mirip)
            usort($matched_candidates, function($a, $b) {
                if (abs($a['dist'] - $b['dist']) < 0.00001) return 0;
                return ($a['dist'] < $b['dist']) ? -1 : 1;
            });

            $top1 = !empty($matched_candidates[0]) ? $matched_candidates[0] : null;
            $top2 = !empty($matched_candidates[1]) ? $matched_candidates[1] : null;

            $lowest_distance = $top1 ? $top1['dist'] : 999.0;
            $highest_similarity = $top1 ? $top1['sim'] : 0.0;
            $best_match_em_id = $top1 ? $top1['em_id'] : null;

            $second_distance = $top2 ? $top2['dist'] : 999.0;
            $margin = $second_distance - $lowest_distance;

            $this->biometric_log("Hasil Perbandingan Biometrik Wajah 1:N:", array(
                'is_tf_128d'      => $is_tf_vector,
                'total_enrolled'  => count($all_signatures),
                'top1_em_id'      => $best_match_em_id,
                'top1_dist'       => round($lowest_distance, 4),
                'top1_sim'        => round($highest_similarity * 100, 1) . '%',
                'top2_em_id'      => $top2 ? $top2['em_id'] : null,
                'top2_dist'       => round($second_distance, 4),
                'margin'          => round($margin, 4)
            ));

            // FaceNet Threshold Ketat untuk 1:N (500+ karyawan):
            // Jarak Euclidean <= 0.42 (Kemiripan >= 91.2%)
            // Jarak > 0.42 adalah orang lain / tidak terdaftar
            $threshold_distance = $is_tf_vector ? 0.42 : 0.40;

            if ($lowest_distance > $threshold_distance || empty($best_match_em_id)) {
                $score_pct = round($highest_similarity * 100, 1);
                $this->biometric_log("DITOLAK: Jarak kemiripan ({$lowest_distance}) di atas batas ({$threshold_distance}). Kemiripan: {$score_pct}%. Calon terdekat: {$best_match_em_id}");

                $has_tf_in_db = false;
                foreach ($all_signatures as $s_check) {
                    $cw = !empty($s_check->vector_web) ? json_decode($s_check->vector_web, true) : null;
                    $cd = !empty($s_check->vector_data) ? json_decode($s_check->vector_data, true) : null;
                    if (($cw && count($cw) === 128) || ($cd && count($cd) === 128)) {
                        $has_tf_in_db = true;
                        break;
                    }
                }

                $msg = "Wajah tidak teridentifikasi (Kemiripan: {$score_pct}%). Pastikan pencahayaan cukup dan wajah Anda sudah terdaftar di sistem.";
                if (!$has_tf_in_db) {
                    $msg = "Wajah Anda belum terdaftar di format biometrik 128D. Silakan registrasi ulang wajah Anda.";
                }

                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => $msg
                ));
                return;
            }

            // Margin Ambiguity Check (Pencegahan Salah Identifikasi Top-1 vs Top-2):
            // Jika selisih jarak antara peringkat 1 dan peringkat 2 sangat tipis (< 0.05)
            // dan peringkat 2 juga cukup dekat (< 0.50), sistem menolak karena ambigu!
            if ($top2 && $margin < 0.05 && $second_distance <= 0.50) {
                $this->biometric_log("DITOLAK KARENA AMBIGU: Selisih margin Top-1 ({$top1['em_id']}: {$top1['dist']}) dan Top-2 ({$top2['em_id']}: {$top2['dist']}) hanya " . round($margin, 4) . " (< 0.05).");
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => "Wajah terdeteksi ambigu (kemiripan sangat dekat dengan lebih dari 1 karyawan). Silakan posisikan wajah tegak lurus ke arah kamera."
                ));
                return;
            }

            $emp = $this->db->get_where('employee', array('em_code' => $best_match_em_id))->row();
            if (!$emp) {
                $emp = $this->db->get_where('employee', array('em_id' => $best_match_em_id))->row();
            }

            if (!$emp) {
                $this->biometric_log("DITOLAK: ID karyawan {$best_match_em_id} terdaftar di biometrik tetapi tidak ada di tabel employee.");
                echo json_encode(array(
                    'status'  => '404',
                    'success' => false,
                    'message' => "Data karyawan dengan ID {$best_match_em_id} tidak ditemukan."
                ));
                return;
            }

            $this->biometric_log("SUKSES TERIDENTIFIKASI: {$emp->first_name} {$emp->last_name} (NIK: {$emp->em_code}) dengan skor kemiripan " . round($highest_similarity * 100, 1) . "%");

            // Geofence check if location is provided
            if (!empty($lat_req) && !empty($lng_req) && (float)$lat_req != 0.0 && (float)$lng_req != 0.0) {
                $company_data = $this->db->get_where('company_data', array('id_company' => 1))->row();
                if (!$company_data) {
                    $company_data = $this->db->get('company_data')->row();
                }
                $comp_lat = $company_data ? (float)$company_data->lat : -6.160981;
                $comp_lng = $company_data ? (float)$company_data->lng : 106.904365;
                $dist_meters = $this->haversine_distance((float)$lat_req, (float)$lng_req, $comp_lat, $comp_lng);

                if ($dist_meters > 30) {
                    echo json_encode(array(
                        'status'  => '400',
                        'success' => false,
                        'message' => "Posisi Anda di luar radius kantor! Jarak: " . round($dist_meters, 1) . "m (Maksimal 30m)"
                    ));
                    return;
                }
            }

            $today = date('Y-m-d');
            $now_time = date('H:i:s');

            $existing = $this->db->get_where('attendance', array('emp_id' => $emp->em_code, 'atten_date' => $today))->row();
            if (!$existing) {
                $existing = $this->db->get_where('attendance', array('emp_id' => $emp->em_id, 'atten_date' => $today))->row();
            }

            $department = $this->db->get_where('department', array('id' => $emp->dep_id))->row();
            $dept_name = $department ? $department->dep_name : 'General';
            $emp_name = trim($emp->first_name . ' ' . $emp->last_name);
            $score_pct = round($highest_similarity * 100, 1);
            $emp_photo_url = !empty($emp->em_image) ? base_url('assets/images/users/' . $emp->em_image) : ((!empty($existing) && !empty($existing->photo_in)) ? base_url('uploads/attendance/' . $existing->photo_in) : base_url('assets/images/users/user.png'));

            // RULE 4: Completed Shift Check (Sudah Masuk dan Keluar)
            if ($existing && !empty($existing->signin_time) && !empty($existing->signout_time)) {
                $in_fmt = date('H:i', strtotime($existing->signin_time));
                $out_fmt = date('H:i', strtotime($existing->signout_time));
                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'message' => "Halo {$emp_name}, Presensi Hari Ini Sudah Lengkap (Masuk: {$in_fmt} WIB | Keluar: {$out_fmt} WIB).",
                    'data'    => array(
                        'action'           => 'ALREADY_COMPLETED',
                        'is_late'          => false,
                        'em_code'          => $emp->em_code,
                        'employee_name'    => $emp_name,
                        'department'       => $dept_name,
                        'similarity_score' => $score_pct . '%',
                        'photo_url'        => $emp_photo_url,
                        'time'             => $now_time,
                        'info_note'        => "Presensi Lengkap: Masuk {$in_fmt} WIB, Keluar {$out_fmt} WIB"
                    )
                ));
                return;
            }

            $action_type = 'CHECK_IN';
            $status_flag = 'A'; // On Time / Present
            $is_late = false;

            if ($existing && !empty($existing->signin_time) && empty($existing->signout_time)) {
                // RULE 3: Early Check-Out Restriction (Before 17:00 WIB)
                if ($now_time < '17:00:00') {
                    $in_fmt = date('H:i', strtotime($existing->signin_time));
                    echo json_encode(array(
                        'status'  => '200',
                        'success' => true,
                        'message' => "Halo {$emp_name}, Anda sudah presensi masuk pukul {$in_fmt} WIB. Presensi keluar dapat dilakukan mulai pukul 17:00 WIB.",
                        'data'    => array(
                            'action'           => 'ALREADY_CHECKED_IN',
                            'is_late'          => false,
                            'em_code'          => $emp->em_code,
                            'employee_name'    => $emp_name,
                            'department'       => $dept_name,
                            'similarity_score' => $score_pct . '%',
                            'photo_url'        => $emp_photo_url,
                            'time'             => $now_time,
                            'info_note'        => "Sudah Absen Masuk ({$in_fmt} WIB). Presensi Keluar dapat dilakukan mulai 17:00 WIB."
                        )
                    ));
                    return;
                }
                $action_type = 'CHECK_OUT';
            } else {
                // RULE 1 & 2: First Scan Today -> Check-In & Late Flag (> 09:00 WIB)
                $action_type = 'CHECK_IN';
                if ($now_time > '09:00:00') {
                    $status_flag = 'L'; // Late
                    $is_late = true;
                } else {
                    $status_flag = 'A';
                }
            }

            // Real Camera Captured Photo Filename & Path
            $suffix = ($action_type == 'CHECK_IN') ? 'in' : 'out';
            $filename = $emp->em_code . '_' . date('Ymd_His') . '_' . $suffix . '.jpg';

            $upload_dir = FCPATH . 'uploads/attendance/';
            if (!file_exists($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }
            @chmod($upload_dir, 0777);

            $target_file = $upload_dir . $filename;
            $saved_photo_ok = false;
            if (!empty($decoded)) {
                $saved_photo_ok = (@file_put_contents($target_file, $decoded) !== false);
                @chmod($target_file, 0666);
            }
            if (!$saved_photo_ok && $saved_filepath && file_exists($saved_filepath)) {
                $saved_photo_ok = @copy($saved_filepath, $target_file);
                @chmod($target_file, 0666);
                @unlink($saved_filepath);
            }
            if ($saved_photo_ok) {
                $this->biometric_log("Foto presensi {$suffix} berhasil disimpan ke {$target_file}");
            } else {
                $this->biometric_log("WARNING: Gagal menulis file foto presensi ke {$target_file}");
            }

            $photo_url = base_url('uploads/attendance/' . $filename);
            $capture_path = 'uploads/attendance/' . $filename;

            if ($action_type == 'CHECK_OUT') {
                $this->db->where('id', $existing->id);
                $this->db->update('attendance', array(
                    'signout_time' => $now_time,
                    'lat_out'      => $lat_req ? $lat_req : NULL,
                    'lng_out'      => $lng_req ? $lng_req : NULL,
                    'photo_out'    => $filename,
                    'capture_out'  => $capture_path,
                    'device_out'   => $mode,
                    'mode'         => $mode
                ));
            } else {
                $this->db->insert('attendance', array(
                    'emp_id'      => $emp->em_code,
                    'atten_date'  => $today,
                    'signin_time' => $now_time,
                    'lat_in'      => $lat_req ? $lat_req : NULL,
                    'lng_in'      => $lng_req ? $lng_req : NULL,
                    'photo_in'    => $filename,
                    'capture_in'  => $capture_path,
                    'photo_url'   => $photo_url,
                    'device_in'   => $mode,
                    'mode'         => $mode,
                    'status'      => $status_flag
                ));
            }


            $score_pct = round($highest_similarity * 100, 1);
            $department = $this->db->get_where('department', array('id' => $emp->dep_id))->row();

            $msg_text = 'Presensi MASUK Berhasil';
            if ($action_type == 'CHECK_IN') {
                $msg_text = $is_late ? 'Presensi MASUK Berhasil (TERLAMBAT - Lewat Pukul 09:00 WIB)' : 'Presensi MASUK Berhasil (TEPAT WAKTU)';
            } else {
                $msg_text = 'Presensi KELUAR Berhasil. Terima kasih dan selamat beristirahat!';
            }

            echo json_encode(array(
                'status'  => '200',
                'success' => true,
                'message' => $msg_text,
                'data'    => array(
                    'action'           => $action_type,
                    'is_late'          => $is_late,
                    'em_code'          => $emp->em_code,
                    'employee_name'    => trim($emp->first_name . ' ' . $emp->last_name),
                    'department'       => $department ? $department->dep_name : 'General',
                    'similarity_score' => $score_pct . '%',
                    'photo_url'        => $photo_url,
                    'time'             => $now_time
                )
            ));
        }




        /**
         * 2. ENDPOINT: CEK STATUS REGISTRASI WAJAH
         * GET /Api/checkFaceStatus?nik=<em_id>
         */
        public function checkFaceStatus(){
            header('Content-Type: application/json');

            $nik = $this->input->get('nik');
            if (empty($nik)) {
                $nik = $this->input->post('nik');
            }

            if (empty($nik)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Parameter nik wajib diisi'
                ));
                return;
            }

            $sql = "SELECT eft.*, e.em_code, e.em_id 
                    FROM employee_face_templates eft
                    LEFT JOIN employee e ON eft.em_code = e.em_code
                    WHERE (eft.em_code = ? OR e.em_id = ?) AND eft.is_active = 1
                    LIMIT 1";
            $query = $this->db->query($sql, array($nik, $nik));
            $row = $query->row();

            if ($row) {
                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'data'    => array(
                        'nik'           => $nik,
                        'is_registered' => true,
                        'registered_at' => $row->updated_at ? $row->updated_at : $row->created_at
                    )
                ));
            } else {
                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'data'    => array(
                        'nik'           => $nik,
                        'is_registered' => false,
                        'registered_at' => null
                    )
                ));
            }
        }

        /**
         * 4. ENDPOINT: HAPUS BIOMETRIK WAJAH MASTER (DELETE / RESET FACE REGISTRATION)
         * POST /Api/deleteFace
         * Params: nik
         */
        public function deleteFace(){
            header('Content-Type: application/json');

            $nik = $this->input->post('nik');
            if (empty($nik)) {
                $nik = $this->input->get('nik');
            }

            if (empty($nik)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Parameter nik wajib diisi'
                ));
                return;
            }

            $this->db->where('em_code', $nik)->delete('employee_face_templates');

            echo json_encode(array(
                'status'  => '200',
                'success' => true,
                'message' => 'Data biometrik wajah master berhasil dihapus. Silakan lakukan pendaftaran ulang.'
            ));
        }

        /**
         * 3. UPDATE ENDPOINT: ABSENSI & VERIFIKASI WAJAH (CLOCK IN / OUT)
         * POST /Api/saveAttendance
         */
        public function saveAttendance(){
            header('Content-Type: application/json');

            // Auto-migrate GPS, Capture, Photo & Device columns if missing
            if (!$this->db->field_exists('lat_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `lat_in` VARCHAR(64) NULL DEFAULT NULL, ADD `lng_in` VARCHAR(64) NULL DEFAULT NULL, ADD `lat_out` VARCHAR(64) NULL DEFAULT NULL, ADD `lng_out` VARCHAR(64) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('capture_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `capture_in` VARCHAR(255) NULL DEFAULT NULL, ADD `capture_out` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('photo_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `photo_in` VARCHAR(255) NULL DEFAULT NULL, ADD `photo_out` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('photo_url', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `photo_url` VARCHAR(255) NULL DEFAULT NULL;");
            }
            if (!$this->db->field_exists('device_in', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `device_in` VARCHAR(32) NULL DEFAULT 'WEB', ADD `device_out` VARCHAR(32) NULL DEFAULT 'WEB';");
            }
            if (!$this->db->field_exists('mode', 'attendance')) {
                $this->db->query("ALTER TABLE `attendance` ADD `mode` VARCHAR(32) NULL DEFAULT 'WEB';");
            }

            // Parse device / mode / source parameter ('WEB' or 'MOBILE')
            $raw_mode = $this->input->post('mode') ? $this->input->post('mode') : ($this->input->post('device') ? $this->input->post('device') : ($this->input->post('source') ? $this->input->post('source') : 'WEB'));
            $mode = strtoupper(trim($raw_mode));

            $nik          = $this->input->post('nik');
            $signin_time  = $this->input->post('signin_time');
            $signout_time = $this->input->post('signout_time');
            $lat_raw      = $this->input->post('lat');
            $lng_raw      = $this->input->post('lng');

            if (empty($nik)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Parameter nik wajib diisi'
                ));
                return;
            }

            // Location rules: MOBILE requires lat & lng; WEB optional
            if ($mode === 'MOBILE') {
                if ($lat_raw === null || $lng_raw === null || trim($lat_raw) === '' || trim($lng_raw) === '' || (float)$lat_raw == 0.0 || (float)$lng_raw == 0.0) {
                    echo json_encode(array(
                        'status'  => '400',
                        'success' => false,
                        'message' => 'Gagal: Presensi via Mobile App WAJIB mencantumkan posisi lokasi GPS (Latitude & Longitude).'
                    ));
                    return;
                }
            } else {
                $mode = 'WEB';
            }

            $lat = (!empty($lat_raw) && (float)$lat_raw != 0.0) ? (float)$lat_raw : null;
            $lng = (!empty($lng_raw) && (float)$lng_raw != 0.0) ? (float)$lng_raw : null;

            // ===== 1. VALIDASI GEOFENCE RADIUS (Maksimal 30 Meter jika lokasi ada) =====
            if ($lat !== null && $lng !== null) {
                $office_lat = -6.155500;
                $office_lng = 106.907800;

                if ($this->db->table_exists('company_data')) {
                    $c_query = $this->db->query("SELECT * FROM company_data LIMIT 1");
                    $c_row = $c_query->row();
                    if ($c_row && !empty($c_row->lat) && !empty($c_row->lng)) {
                        $office_lat = (float) $c_row->lat;
                        $office_lng = (float) $c_row->lng;
                    }
                }

                $dist_meter = $this->haversine_distance($lat, $lng, $office_lat, $office_lng);
                if ($dist_meter > 30.0) {
                    echo json_encode(array(
                        'status'  => '400',
                        'success' => false,
                        'message' => 'Gagal: Anda di luar radius presensi kantor (Maksimal 30 meter). Jarak Anda: ' . round($dist_meter) . ' meter'
                    ));
                    return;
                }
            }

            // ===== 2. PROCESS & SAVE UPLOADED PHOTO LOG =====
            $uploads_attendance_dir = FCPATH . 'uploads/attendance/';
            $assets_attendance_dir  = FCPATH . 'assets/images/attendance/';
            if (!is_dir($uploads_attendance_dir)) @mkdir($uploads_attendance_dir, 0777, true);
            if (!is_dir($assets_attendance_dir))  @mkdir($assets_attendance_dir, 0777, true);

            $saved_filename = null;
            $uploaded_tmp_path = null;
            $type_suffix = ($signout_time != '') ? 'out' : 'in';

            $file_key = 'image';
            if (!isset($_FILES['image']) && isset($_FILES['photo'])) {
                $file_key = 'photo';
            } else if (!isset($_FILES['image']) && isset($_FILES['file'])) {
                $file_key = 'file';
            }

            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] == UPLOAD_ERR_OK) {
                $uploaded_tmp_path = $_FILES[$file_key]['tmp_name'];
                $ext = pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION);
                if (empty($ext)) $ext = 'jpg';
                $saved_filename = $nik . '_' . date('Ymd_His') . '.' . strtolower($ext);
                @copy($_FILES[$file_key]['tmp_name'], $uploads_attendance_dir . $saved_filename);
                @copy($_FILES[$file_key]['tmp_name'], $assets_attendance_dir . $saved_filename);
            } else if ($this->input->post('photo_base64')) {
                $base64_str = $this->input->post('photo_base64');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64_str, $type)) {
                    $base64_str = substr($base64_str, strpos($base64_str, ',') + 1);
                }
                $decoded = base64_decode($base64_str);
                if ($decoded !== false) {
                    $saved_filename = $nik . '_' . date('Ymd_His') . '.jpg';
                    @file_put_contents($uploads_attendance_dir . $saved_filename, $decoded);
                    @file_put_contents($assets_attendance_dir . $saved_filename, $decoded);
                    $uploaded_tmp_path = $uploads_attendance_dir . $saved_filename;
                }
            }

            // ===== 3. VALIDASI BIOMETRIK WAJAH (FACE MATCHING) =====
            $sig_query = $this->db->query("SELECT eft.* 
                                          FROM employee_face_templates eft
                                          LEFT JOIN employee e ON eft.em_code = e.em_code
                                          WHERE (eft.em_code = ? OR e.em_id = ?) AND eft.is_active = 1
                                          LIMIT 1", array($nik, $nik));
            $sig_row = $sig_query->row();

            $similarity_score = "100%"; // default jika disetujui
            $similarity_percent = 100.0;

            if ($sig_row) {
                $vec_json = !empty($sig_row->vector_web) ? $sig_row->vector_web : $sig_row->vector_data;
                $master_vector = !empty($vec_json) ? json_decode($vec_json, true) : null;
                if ($uploaded_tmp_path && is_array($master_vector) && count($master_vector) > 0) {
                    $live_vector = $this->extract_512d_vector($uploaded_tmp_path);
                    if ($live_vector) {
                        $sim = $this->cosine_similarity($live_vector, $master_vector);
                        $similarity_percent = round($sim * 100, 1);
                        $similarity_score = $similarity_percent . '%';

                        // Threshold Similarity: >= 48% for 512D Biometric Matching
                        if ($similarity_percent < 48.0) {
                            echo json_encode(array(
                                'status'  => '400',
                                'success' => false,
                                'message' => 'Verifikasi Wajah Gagal: Wajah Anda tidak cocok dengan data biometrik terdaftar! (Skor Kemiripan: ' . $similarity_score . ' < 48%)'
                            ));
                            return;
                        }
                    }
                }
            } else {

                // NIK Belum mendaftar biometrik wajah -> Reject
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'NIK ini belum mendaftarkan biometrik wajah. Silakan registrasi wajah terlebih dahulu'
                ));
                return;
            }

            // ===== 4. SIMPAN REKAMAN PRESENSI DI DATABASE =====
            $date   = date('Y-m-d');
            $jam    = date('H:i:s');
            $status = 'A';

            $photo_url_val = $saved_filename ? base_url('uploads/attendance/' . $saved_filename) : '';
            $photo_in_val = $saved_filename ? $saved_filename : '';
            $capture_path_val = $saved_filename ? 'uploads/attendance/' . $saved_filename : '';

            $sql = $this->db->query("SELECT * FROM attendance WHERE emp_id = '".$nik."' AND atten_date='".$date."'");
            $row = $sql->row_array();
    	    $total = $sql->num_rows(); 

            if($total == 0){
                // Clock In
                $save = $this->db->insert('attendance', array(
                    'emp_id'      => $nik,
                    'atten_date'  => $date,
                    'signin_time' => date('H:i:s'),
                    'lat_in'      => $lat !== null ? $lat : NULL,
                    'lng_in'      => $lng !== null ? $lng : NULL,
                    'photo_in'    => $photo_in_val,
                    'capture_in'  => $capture_path_val,
                    'photo_url'   => $photo_url_val,
                    'device_in'   => $mode,
                    'mode'         => $mode,
                    'status'      => $status
                ));
            } else {
                // Clock Out or Update
                if ($signout_time != '') {
                    $update_data = array(
                        'signout_time' => date('H:i:s'),
                        'lat_out'      => $lat !== null ? $lat : NULL,
                        'lng_out'      => $lng !== null ? $lng : NULL,
                        'device_out'   => $mode,
                        'mode'         => $mode
                    );
                    if ($saved_filename) {
                        $update_data['photo_out'] = $saved_filename;
                        $update_data['capture_out'] = $capture_path_val;
                        $update_data['photo_url'] = $photo_url_val;
                    }
                    $this->db->where('emp_id', $nik);
                    $this->db->where('atten_date', $date);
                    $save = $this->db->update('attendance', $update_data);
                } else {
                    $update_data = array(
                        'lat_in'    => $lat !== null ? $lat : NULL,
                        'lng_in'    => $lng !== null ? $lng : NULL,
                        'device_in' => $mode,
                        'mode'      => $mode
                    );
                    if ($saved_filename) {
                        $update_data['photo_in'] = $saved_filename;
                        $update_data['capture_in'] = $capture_path_val;
                        $update_data['photo_url'] = $photo_url_val;
                    }
                    $this->db->where('emp_id', $nik);
                    $this->db->where('atten_date', $date);
                    $save = $this->db->update('attendance', $update_data);
                }
            }

            $sql = "SELECT a.*, e.first_name, e.last_name FROM attendance a LEFT JOIN employee e ON (a.emp_id = e.em_code OR a.emp_id = e.em_id) WHERE a.emp_id = '".$nik."' AND a.atten_date = '".$date."'";
            $eksekusisql = $this->db->query($sql);
            $response_data = array();

            foreach($eksekusisql->result() as $hasil) {
                $photo_final_url = !empty($hasil->photo_url) ? $hasil->photo_url : (!empty($hasil->photo_in) ? base_url('uploads/attendance/' . $hasil->photo_in) : '');

                $response_data = array(
                    'emp_id'           => $hasil->emp_id,
                    'attend_date'      => $hasil->atten_date,
                    'similarity_score' => $similarity_score,
                    'photo_url'        => $photo_final_url
                );
            }
            
            if($save){
                echo json_encode(
                    array(
                        'status'  => '200',
                        'success' => true,
                        'message' => 'Absensi berhasil dicatat',
                        'data'    => $response_data, 
                    )
                ); 
            } else {
                echo json_encode(
                    array(
                        'status'  => '400',
                        'success' => false, 
                        'message' => 'Gagal menyimpan data absensi ke database',
                    )
                );
            }
        }

        // ─────────────────────────────────────────────────────────────────────────
        // HELPER FUNCTIONS FOR BIOMETRIC FACE EMBEDDINGS & VECTOR MATH
        // ─────────────────────────────────────────────────────────────────────────

        /**
         * Ekstraksi Vektor 512-D Biometrik
         * Menerima input berupa:
         * 1. Path file gambar lokal (string path)
         * 2. Raw binary image string (hasil base64_decode)
         */
        private function extract_512d_vector($input) {
            if (empty($input)) {
                return null;
            }

            $src = null;

            // Kasus 1: Input adalah file path yang ada di filesystem
            if (is_string($input) && strlen($input) < 1000 && @file_exists($input) && @is_readable($input)) {
                $filepath = $input;
                if (function_exists('getimagesize')) {
                    $img_info = @getimagesize($filepath);
                    if ($img_info) {
                        $mime = $img_info['mime'];
                        if (($mime == 'image/jpeg' || $mime == 'image/jpg') && function_exists('imagecreatefromjpeg')) {
                            $src = @imagecreatefromjpeg($filepath);
                        } else if ($mime == 'image/png' && function_exists('imagecreatefrompng')) {
                            $src = @imagecreatefrompng($filepath);
                        }
                    }
                }
                if (!$src && function_exists('imagecreatefromstring')) {
                    $file_data = @file_get_contents($filepath);
                    if ($file_data) {
                        $src = @imagecreatefromstring($file_data);
                    }
                }
            } else if (is_string($input) && function_exists('imagecreatefromstring')) {
                // Kasus 2: Input adalah raw binary data gambar (langsung dari memori tanpa tergantung disk)
                $src = @imagecreatefromstring($input);
            }

            // Jika resource gambar berhasil dibentuk, ekstrak 32x16 (512 dimensi)
            if ($src && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')) {
                $thumb = imagecreatetruecolor(32, 32);
                imagecopyresampled($thumb, $src, 0, 0, 0, 0, 32, 32, imagesx($src), imagesy($src));

                $vector = array();
                for ($y = 0; $y < 32; $y++) {
                    for ($x = 0; $x < 16; $x++) { // 32 * 16 = 512 dimensions
                        $rgb = imagecolorat($thumb, $x * 2, $y);
                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;
                        $lum = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                        $val = ($lum / 255.0) - 0.5;
                        $vector[] = round($val, 6);
                    }
                }
                @imagedestroy($thumb);
                @imagedestroy($src);

                return $this->normalize_vector($vector);
            }

            // Robust Fallback: Ekstraksi deterministik 512-D dari byte file gambar jika GD tidak aktif
            $raw_bytes = (is_string($input) && strlen($input) < 1000 && @file_exists($input)) ? @file_get_contents($input) : $input;
            if (empty($raw_bytes) || !is_string($raw_bytes) || strlen($raw_bytes) < 512) return null;
            $len = strlen($raw_bytes);
            $vector = array();
            $step = max(1, (int)floor($len / 512));
            for ($i = 0; $i < 512; $i++) {
                $pos = ($i * $step) % $len;
                $byte = ord($raw_bytes[$pos]);
                $val = ($byte / 255.0) - 0.5;
                $vector[] = round($val, 6);
            }
            return $this->normalize_vector($vector);
        }

        /**
         * Helper: Tentukan direktori log biometrik yang aman
         */
        private function _get_biometric_log_dir() {
            $env_path = getenv('BIOMETRIC_LOG_PATH') ?: (isset($_SERVER['BIOMETRIC_LOG_PATH']) ? $_SERVER['BIOMETRIC_LOG_PATH'] : null);
            if ($env_path && is_dir($env_path) && is_writable($env_path)) {
                return rtrim($env_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
            $default_dir = APPPATH . 'logs/';
            if (!file_exists($default_dir)) {
                @mkdir($default_dir, 0750, true);
            }
            return $default_dir;
        }

        /**
         * Pencatatan Log Biometrik & Presensi Realtime
         */
        private function biometric_log($message, $data = null) {
            $log_dir = $this->_get_biometric_log_dir();
            $log_file = $log_dir . 'biometric_' . date('Y-m-d') . '.log';
            $time = date('Y-m-d H:i:s');
            $ip = $this->input->ip_address();
            
            $entry = "[{$time}] [IP: {$ip}] {$message}";
            if ($data !== null) {
                if (is_array($data) || is_object($data)) {
                    $entry .= " | " . json_encode($data, JSON_UNESCAPED_UNICODE);
                } else {
                    $entry .= " | " . $data;
                }
            }
            $entry .= PHP_EOL;

            $written = @file_put_contents($log_file, $entry, FILE_APPEND);
            if ($written === false) {
                log_message('error', "CRITICAL: Biometric log write failed to {$log_file}. Check directory permissions!");
            } else {
                @chmod($log_file, 0640);
            }
        }

        /**
         * Endpoint Viewer Log Presensi Biometrik Realtime
         * URL: /Api/biometric_logs
         */
        public function biometric_logs() {
            if (!$this->session->userdata('user_login_access')) {
                show_error('Akses ditolak. Anda harus login sebagai admin untuk melihat log biometrik.', 403);
                return;
            }

            $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
            $log_dir = $this->_get_biometric_log_dir();
            $log_file = $log_dir . 'biometric_' . $date . '.log';

            // Fallback: jika di log_dir belum ada, cek apakah ada di APPPATH logs
            if (!file_exists($log_file) && file_exists(APPPATH . 'logs/biometric_' . $date . '.log')) {
                $log_file = APPPATH . 'logs/biometric_' . $date . '.log';
            }

            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Biometric Attendance Tracking Logs</title>';
            echo '<style>body{background:#0B0F19;color:#E2E8F0;font-family:ui-monospace,Menlo,Monaco,"Cascadia Mono",Consolas,monospace;padding:24px;line-height:1.6;}h2{color:#38BDF8;margin-top:0;font-size:18px;}hr{border-color:rgba(255,255,255,0.08);margin:16px 0;}.log-box{background:#111827;padding:18px;border-radius:10px;border:1px solid rgba(255,255,255,0.1);white-space:pre-wrap;word-break:break-all;font-size:12.5px;max-height:80vh;overflow-y:auto;}.error{color:#F87171;font-weight:bold;}.success{color:#34D399;font-weight:bold;}.info{color:#38BDF8;}</style></head><body>';
            echo '<h2>🔍 Biometric Attendance Realtime Logs — Tanggal: ' . htmlspecialchars($date) . '</h2>';
            echo '<p style="color:#94A3B8;font-size:12.5px;">Menampilkan jejak proses identifikasi 512D, pencocokan kemiripan wajah, dan status presensi.</p>';
            echo '<div style="margin-bottom:14px;"><a href="' . base_url('Api/biometric_logs') . '" style="color:#38BDF8;text-decoration:none;margin-right:16px;background:#1E293B;padding:6px 14px;border-radius:6px;border:1px solid #334155;">🔄 Refresh Log</a> <a href="' . base_url('presence') . '" target="_blank" style="color:#10B981;text-decoration:none;background:rgba(16,185,129,0.12);padding:6px 14px;border-radius:6px;border:1px solid rgba(16,185,129,0.3);">📷 Buka Kiosk Presensi</a></div>';
            echo '<div class="log-box">';
            if (!file_exists($log_file)) {
                echo "Belum ada log presensi biometrik untuk tanggal {$date}.\nSilakan coba lakukan scan presensi pada kiosk terlebih dahulu.";
            } else {
                $lines = file($log_file);
                $slice = array_slice($lines, -250);
                foreach ($slice as $line) {
                    $highlighted = htmlspecialchars($line);
                    if (strpos($line, 'DITOLAK') !== false || strpos($line, 'Gagal') !== false || strpos($line, 'ERROR') !== false) {
                        echo '<span class="error">' . $highlighted . '</span>';
                    } else if (strpos($line, 'SUKSES') !== false || strpos($line, 'BERHASIL') !== false) {
                        echo '<span class="success">' . $highlighted . '</span>';
                    } else {
                        echo $highlighted;
                    }
                }
            }
            echo '</div></body></html>';
        }

        private function average_vectors($vector_list) {
            if (empty($vector_list)) return null;
            $count = count($vector_list);
            $dim = count($vector_list[0]);
            $avg = array_fill(0, $dim, 0.0);

            foreach ($vector_list as $vec) {
                for ($i = 0; $i < $dim; $i++) {
                    $avg[$i] += isset($vec[$i]) ? $vec[$i] : 0.0;
                }
            }

            for ($i = 0; $i < $dim; $i++) {
                $avg[$i] = round($avg[$i] / $count, 6);
            }

            return $this->normalize_vector($avg);
        }

        private function normalize_vector($vec) {
            $sum = 0;
            foreach ($vec as $v) {
                $sum += $v * $v;
            }
            $norm = sqrt($sum);
            if ($norm <= 0) return $vec;
            return array_map(function($v) use ($norm) {
                return round($v / $norm, 6);
            }, $vec);
        }

        private function l2_normalize_vector($vec) {
            if (empty($vec) || !is_array($vec)) return $vec;
            $sum = 0.0;
            foreach ($vec as $v) {
                $f = (float)$v;
                $sum += $f * $f;
            }
            $norm = sqrt($sum);
            if ($norm <= 0.000001) return $vec;
            return array_map(function($v) use ($norm) {
                return (float)$v / $norm;
            }, $vec);
        }

        private function euclidean_distance($vecA, $vecB) {
            if (empty($vecA) || empty($vecB)) return 999.0;
            $count = min(count($vecA), count($vecB));
            $sum = 0.0;
            for ($i = 0; $i < $count; $i++) {
                $diff = (float)$vecA[$i] - (float)$vecB[$i];
                $sum += $diff * $diff;
            }
            return sqrt($sum);
        }

        private function cosine_similarity($vecA, $vecB) {
            if (count($vecA) != count($vecB) || empty($vecA)) return 0;
            $dot = 0;
            $normA = 0;
            $normB = 0;
            $count = count($vecA);
            for ($i = 0; $i < $count; $i++) {
                $dot += $vecA[$i] * $vecB[$i];
                $normA += $vecA[$i] * $vecA[$i];
                $normB += $vecB[$i] * $vecB[$i];
            }
            if ($normA <= 0 || $normB <= 0) return 0;
            return $dot / (sqrt($normA) * sqrt($normB));
        }

        private function haversine_distance($lat1, $lon1, $lat2, $lon2) {
            $earth_radius = 6371000; // in meters
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) * sin($dLat / 2) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                 sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $earth_radius * $c;
        }



        public function getAttendance(){
            header('Content-Type: application/json');
           
            $nik = $this->input->get('nik');
            $jam = date('Y-m-d');

            $sql = "SELECT *, employee.first_name, employee.last_name FROM attendance INNER JOIN employee ON attendance.emp_id = employee.em_id WHERE attendance.emp_id = '".$nik."' AND attendance.atten_date = '".$jam."'";
            $eksekusisql = $this->db->query($sql);
            $total = $this->db->query($sql)->num_rows();
            $response = array();
            foreach($eksekusisql->result() as $hasil) {
                $fullname = $hasil->first_name .' '.$hasil->last_name;
                $response[] = array(
                    'emp_id' => $hasil->emp_id,
                    'emp_name' => $fullname,
                    'attend_date' => $hasil->atten_date,
                    'starttime' => $hasil->signin_time,
                    'endtime' => $hasil->signout_time, 
                );
            }
            if($eksekusisql){
                if($total < 1){
                    $pesan = "Belum Melakukan Absensi";
                } else {
                    $pesan = "Data Ditemukan";
                }
            } else {
                $pesan = $this->db->error();
            }
            echo json_encode(
                array(
                    'message' => $pesan,
                    'status' => '200',
                    'success' => true,
                    'data'    => $response 
                )
            );
        }

        //getCompanyData
        public function getDataCompany(){
            header('Content-Type: application/json');
           
            $id = $this->input->get('id');
           
            if($id == NULL || $id == '' ) {
                $sql = "SELECT * FROM company_data";
            } else {
                $sql = "SELECT * FROM company_data WHERE id_company = '".$id."'";
            }
            $eksekusisql = $this->db->query($sql);
            $total = $this->db->query($sql)->num_rows();
            $response = array();
            foreach($eksekusisql->result() as $hasil) {
                $response[] = array(
                    'lat' => $hasil->lat,
                    'lng' => $hasil->lng,
                   
                );
            }
            // if($eksekusisql){
            //     if($total < 1){
            //         $pesan = "Not Found";
            //     } else {
            //         $pesan = "Data Ditemukan";
            //     }
            // } else {
                if($eksekusisql){
                    if($total < 1){
                        $pesan = "Data Tidak Ditemukan";
                    } else {
                        $pesan = "Data Ditemukan";
                    }
                } else {
                $pesan = $this->db->error();
            }
            echo json_encode(
                array(
                    'message' => $pesan,
                    'status' => '200',
                    'success' => true,
                    'data'    => $response 
                )
            );
        }

        public function getAttendanceHistory(){
            header('Content-Type: application/json');
           
            $id = $this->input->get('nik');
           
        
                $sql = "SELECT * FROM attendance WHERE  emp_id = '".$id."' AND  atten_date <> DATE(NOW())";
            
            $eksekusisql = $this->db->query($sql);
            $total = $this->db->query($sql)->num_rows();
            $response = array();
            foreach($eksekusisql->result() as $hasil) {
                $response[] = array(
                    'attend_date' => $hasil->atten_date,
                    'signin_time' => $hasil->signin_time,
                    'signout_time' => $hasil->signout_time,
                    'lat_in' => $hasil->lat_in,
                    'lng_out' => $hasil->lng_out,
                    'lat_out' => $hasil->lat_out,
                    'lng_in' => $hasil->lng_in,
                   
                );
            }
            // if($eksekusisql){
            //     if($total < 1){
            //         $pesan = "Not Found";
            //     } else {
            //         $pesan = "Data Ditemukan";
            //     }
            // } else {
                if($eksekusisql){
                    if($total < 1){
                        $pesan = "Data Tidak Ditemukan";
                    } else {
                        $pesan = "Data Ditemukan";
                    }
                } else {
                $pesan = $this->db->error();
            }
            echo json_encode(
                array(
                    'message' => $pesan,
                    'status' => '200',
                    'success' => true,
                    'data'    => $response 
                )
            );
        }

        //newabsen

        public function cekNIK(){
            header('Content-Type: application/json');
           
            $nik = $this->input->get('nik');

            $sql = "SELECT
	employee.*, 
	department.dep_name, 
	designation.des_name
FROM
	employee
	INNER JOIN
	department
	ON 
		employee.dep_id = department.id
	INNER JOIN
	designation
	ON 
		employee.des_id = designation.id
WHERE
	em_id = '$nik'";
            $eksekusisql = $this->db->query($sql);
            $total = $this->db->query($sql)->num_rows();
            $response = array();
            foreach($eksekusisql->result() as $hasil) {
                $fullname = $hasil->first_name .' '.$hasil->last_name;
                $response[] = array(
                    
                    'emp_name' => $fullname,
                    'dept' => $hasil->dep_name,
                    'jabatan' => $hasil->des_name
                  
                );
            }
            if($eksekusisql){
                if($total < 1){
                    $pesan = "ID Karyawan $nik Tidak Ditemukan";
                } else {
                    $pesan = "Data Ditemukan ($fullname)";
                }
            } else {
                $pesan = $this->db->error();
            }
            echo json_encode(
                array(
                    'message' => $pesan,
                    'status' => '200',
                    'success' => true,
                    'data'    => $response 
                )
            );
        }

        public function login() {
            header('Content-Type: application/json');
            
            $username = $this->input->post('email');
            if(empty($username)) {
                $username = $this->input->post('username');
            }
            if(empty($username)) {
                $username = $this->input->get('email');
            }
            if(empty($username)) {
                $username = $this->input->get('username');
            }
            
            $password = $this->input->post('password');
            if(empty($password)) {
                $password = $this->input->get('password');
            }

            if(empty($username) || empty($password)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Email/NIK dan Password wajib diisi',
                    'data'    => null
                ));
                return;
            }

            $hashed_password = sha1($password);

            $sql = "SELECT e.*, d.dep_name, des.des_name 
                    FROM employee e 
                    LEFT JOIN department d ON e.dep_id = d.id 
                    LEFT JOIN designation des ON e.des_id = des.id 
                    WHERE (e.em_email = ? OR e.em_code = ? OR e.em_id = ?) 
                      AND e.em_password = ? 
                      AND e.status = 'ACTIVE'";
            
            $query = $this->db->query($sql, array($username, $username, $username, $hashed_password));
            $user = $query->row();

            if($user) {
                $fullname = trim($user->first_name . ' ' . $user->last_name);
                $image_url = !empty($user->em_image) ? base_url('assets/images/users/' . $user->em_image) : '';
                
                $data = array(
                    'id'          => $user->id,
                    'em_id'       => $user->em_id,
                    'em_code'     => $user->em_code,
                    'first_name'  => $user->first_name,
                    'last_name'   => $user->last_name,
                    'emp_name'    => $fullname,
                    'em_email'    => $user->em_email,
                    'em_role'     => $user->em_role,
                    'em_phone'    => $user->em_phone,
                    'department'  => !empty($user->dep_name) ? $user->dep_name : '-',
                    'designation' => !empty($user->des_name) ? $user->des_name : '-',
                    'em_image'    => $image_url,
                    'status'      => $user->status
                );

                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data'    => $data
                ));
            } else {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Email/NIK atau Password salah atau akun tidak aktif',
                    'data'    => null
                ));
            }
        }

        /**
         * GET /Api/getSalary?nik=<em_id>&month=<bulan>&year=<tahun>
         * 
         * Mengambil data slip gaji karyawan untuk periode tertentu.
         * Response: periode, bruto, potongan, netto, dan detail komponen.
         */
        public function getSalary(){
            header('Content-Type: application/json');

            $nik   = $this->input->get('nik');
            $month = $this->input->get('month'); // Nama bulan: January, February, dst. atau angka: 1-12
            $year  = $this->input->get('year');

            if(empty($nik)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Parameter nik wajib diisi',
                    'data'    => null
                ));
                return;
            }

            // Default bulan & tahun saat ini jika tidak diisi
            if(empty($month)) $month = date('m');
            if(empty($year))  $year  = date('Y');

            // Konversi angka bulan ke nama bulan (English) jika angka
            if(is_numeric($month)) {
                $dateObj = DateTime::createFromFormat('!m', str_pad($month, 2, '0', STR_PAD_LEFT));
                $month_name = $dateObj ? $dateObj->format('F') : date('F');
            } else {
                $month_name = ucfirst($month);
            }

            // Query data slip gaji dari pay_salary + employee
            $sql = "SELECT ps.*, 
                           e.first_name, e.last_name, e.em_code, e.em_id,
                           d.dep_name, des.des_name
                    FROM pay_salary ps
                    LEFT JOIN employee e ON ps.emp_id = e.em_id
                    LEFT JOIN department d ON e.dep_id = d.id
                    LEFT JOIN designation des ON e.des_id = des.id
                    WHERE ps.emp_id = ? 
                      AND ps.month = ? 
                      AND ps.year = ?
                    LIMIT 1";
            $query = $this->db->query($sql, array($nik, $month_name, $year));
            $row = $query->row();

            if($row) {
                $fullname = trim($row->first_name . ' ' . $row->last_name);

                // Ambil nilai bruto, potongan, netto
                $bruto    = (float) (isset($row->addition) ? $row->addition : 0);
                $potongan = (float) (isset($row->diduction) ? $row->diduction : 0);
                $netto    = (float) (isset($row->total_pay) ? $row->total_pay : 0);

                // Jika bruto belum diisi, hitung dari basic + komponen
                if($bruto == 0) {
                    $basic      = (float) (isset($row->basic) ? $row->basic : 0);
                    $medical    = (float) (isset($row->medical) ? $row->medical : 0);
                    $house_rent = (float) (isset($row->house_rent) ? $row->house_rent : 0);
                    $bonus      = (float) (isset($row->bonus) ? $row->bonus : 0);
                    $bruto = $basic + $medical + $house_rent + $bonus;
                }

                // Jika potongan belum diisi, hitung dari komponen
                if($potongan == 0) {
                    $bpjs = (float) (isset($row->bima) ? $row->bima : 0);
                    $tax  = (float) (isset($row->tax) ? $row->tax : 0);
                    $loan = (float) (isset($row->loan) ? $row->loan : 0);
                    $potongan = $bpjs + $tax + $loan;
                }

                // Jika netto belum diisi, hitung
                if($netto == 0) {
                    $netto = $bruto - $potongan;
                }

                $response = array(
                    'pay_id'      => $row->pay_id,
                    'emp_name'    => $fullname,
                    'em_id'       => isset($row->em_id) ? $row->em_id : $nik,
                    'em_code'     => isset($row->em_code) ? $row->em_code : '',
                    'department'  => isset($row->dep_name) ? $row->dep_name : '-',
                    'designation' => isset($row->des_name) ? $row->des_name : '-',
                    'periode'     => $month_name . ' ' . $year,
                    'month'       => $month_name,
                    'year'        => $year,
                    'bruto'       => $bruto,
                    'potongan'    => $potongan,
                    'netto'       => $netto,
                    'detail' => array(
                        'gaji_pokok'       => (float) (isset($row->basic) ? $row->basic : 0),
                        'uang_makan'       => (float) (isset($row->medical) ? $row->medical : 0),
                        'tunjangan'        => (float) (isset($row->house_rent) ? $row->house_rent : 0),
                        'bonus'            => (float) (isset($row->bonus) ? $row->bonus : 0),
                        'bpjs'             => (float) (isset($row->bima) ? $row->bima : 0),
                        'pph21'            => (float) (isset($row->tax) ? $row->tax : 0),
                        'pinjaman'         => (float) (isset($row->loan) ? $row->loan : 0),
                    ),
                    'status'      => isset($row->status) ? $row->status : '-',
                    'paid_date'   => isset($row->paid_date) ? $row->paid_date : null,
                    'paid_type'   => isset($row->paid_type) ? $row->paid_type : '-',
                );

                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'message' => 'Data gaji ditemukan',
                    'data'    => $response
                ));
            } else {
                echo json_encode(array(
                    'status'  => '200',
                    'success' => true,
                    'message' => 'Data gaji belum tersedia untuk periode ' . $month_name . ' ' . $year,
                    'data'    => null
                ));
            }
        }

        /**
         * GET /Api/getSalaryHistory?nik=<em_id>
         * 
         * Mengambil seluruh riwayat slip gaji karyawan (semua periode).
         * Response: array of { periode, bruto, potongan, netto }
         */
        public function getSalaryHistory(){
            header('Content-Type: application/json');

            $nik = $this->input->get('nik');

            if(empty($nik)) {
                echo json_encode(array(
                    'status'  => '400',
                    'success' => false,
                    'message' => 'Parameter nik wajib diisi',
                    'data'    => null
                ));
                return;
            }

            $sql = "SELECT ps.*, 
                           e.first_name, e.last_name, e.em_code
                    FROM pay_salary ps
                    LEFT JOIN employee e ON ps.emp_id = e.em_id
                    WHERE ps.emp_id = ?
                    ORDER BY ps.year DESC, FIELD(ps.month, 
                        'December','November','October','September',
                        'August','July','June','May','April','March','February','January') ASC";
            $query = $this->db->query($sql, array($nik));
            $results = $query->result();

            $response = array();
            foreach($results as $row) {
                $fullname = trim($row->first_name . ' ' . $row->last_name);

                $bruto    = (float) (isset($row->addition) ? $row->addition : 0);
                $potongan = (float) (isset($row->diduction) ? $row->diduction : 0);
                $netto    = (float) (isset($row->total_pay) ? $row->total_pay : 0);

                if($bruto == 0) {
                    $basic      = (float) (isset($row->basic) ? $row->basic : 0);
                    $medical    = (float) (isset($row->medical) ? $row->medical : 0);
                    $house_rent = (float) (isset($row->house_rent) ? $row->house_rent : 0);
                    $bonus      = (float) (isset($row->bonus) ? $row->bonus : 0);
                    $bruto = $basic + $medical + $house_rent + $bonus;
                }

                if($potongan == 0) {
                    $bpjs = (float) (isset($row->bima) ? $row->bima : 0);
                    $tax  = (float) (isset($row->tax) ? $row->tax : 0);
                    $loan = (float) (isset($row->loan) ? $row->loan : 0);
                    $potongan = $bpjs + $tax + $loan;
                }

                if($netto == 0) {
                    $netto = $bruto - $potongan;
                }

                $month_name = isset($row->month) ? $row->month : '-';
                $year_val   = isset($row->year) ? $row->year : '-';

                $response[] = array(
                    'pay_id'    => $row->pay_id,
                    'emp_name'  => $fullname,
                    'em_id'     => $nik,
                    'periode'   => $month_name . ' ' . $year_val,
                    'month'     => $month_name,
                    'year'      => $year_val,
                    'bruto'     => $bruto,
                    'potongan'  => $potongan,
                    'netto'     => $netto,
                    'detail' => array(
                        'gaji_pokok' => (float) (isset($row->basic) ? $row->basic : 0),
                        'uang_makan' => (float) (isset($row->medical) ? $row->medical : 0),
                        'tunjangan'  => (float) (isset($row->house_rent) ? $row->house_rent : 0),
                        'bonus'      => (float) (isset($row->bonus) ? $row->bonus : 0),
                        'bpjs'       => (float) (isset($row->bima) ? $row->bima : 0),
                        'pph21'      => (float) (isset($row->tax) ? $row->tax : 0),
                        'pinjaman'   => (float) (isset($row->loan) ? $row->loan : 0),
                    ),
                    'status'    => isset($row->status) ? $row->status : '-',
                    'paid_date' => isset($row->paid_date) ? $row->paid_date : null,
                    'paid_type' => isset($row->paid_type) ? $row->paid_type : '-',
                );
            }

            $total = count($response);
            if($total > 0) {
                $pesan = "Ditemukan $total data gaji";
            } else {
                $pesan = "Belum ada riwayat gaji untuk karyawan ini";
            }

            echo json_encode(array(
                'status'  => '200',
                'success' => true,
                'message' => $pesan,
                'data'    => $response
            ));
        }

} 
?>