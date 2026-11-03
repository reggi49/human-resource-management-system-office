<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->db->query("SET time_zone = '+07:00'");
        $this->load->model('login_model');
        $this->load->model('employee_model');
        $this->load->model('payroll_model');
        $this->load->model('leave_model');
        $this->load->model('settings_model');
        $this->load->model('notice_model');
        $this->load->model('project_model');
        $this->load->model('dashboard_model');

        // Schema Guard: Ensure 'loan' table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `loan` (
            `id` int(14) NOT NULL AUTO_INCREMENT,
            `emp_id` varchar(256) DEFAULT NULL,
            `amount` varchar(256) DEFAULT NULL,
            `interest_percentage` varchar(256) DEFAULT NULL,
            `total_amount` varchar(64) DEFAULT NULL,
            `total_pay` varchar(64) DEFAULT NULL,
            `total_due` varchar(64) DEFAULT NULL,
            `installment` varchar(256) DEFAULT NULL,
            `loan_number` varchar(256) DEFAULT NULL,
            `loan_details` varchar(256) DEFAULT NULL,
            `approve_date` varchar(256) DEFAULT NULL,
            `install_period` varchar(256) DEFAULT NULL,
            `status` enum('Granted','Deny','Pause','Done') NOT NULL DEFAULT 'Pause',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        // Schema Guard: Ensure 'reimbursement' table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `reimbursement` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `batch_id` int(11) NULL DEFAULT NULL,
            `em_id` varchar(64) NOT NULL,
            `title` varchar(255) NOT NULL,
            `category` varchar(64) DEFAULT 'Umum',
            `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
            `receipt_image` varchar(255) DEFAULT NULL,
            `description` text DEFAULT NULL,
            `ai_analysis` text DEFAULT NULL,
            `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        // Schema Guard: Ensure 'reimbursement_batch' table exists
        $this->db->query("CREATE TABLE IF NOT EXISTS `reimbursement_batch` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `batch_code` varchar(64) NOT NULL UNIQUE,
            `em_id` varchar(64) NOT NULL,
            `batch_title` varchar(255) NOT NULL,
            `total_items` int(11) NOT NULL DEFAULT '0',
            `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
            `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

        if (!$this->db->field_exists('batch_id', 'reimbursement')) {
            $this->db->query("ALTER TABLE `reimbursement` ADD `batch_id` int(11) NULL DEFAULT NULL AFTER `id`;");
        }
    }

    public function login() {
        if ($this->session->userdata('user_login_access') == 1) {
            redirect('portal');
            return;
        }
        $data = array();
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $this->load->view('backend/employee_login', $data);
    }

    public function login_auth() {
        $email_or_nik = $this->input->post('email');
        $raw_pass = $this->input->post('password');

        if (empty($email_or_nik) || empty($raw_pass)) {
            $this->session->set_flashdata('feedback', 'NIK / Email dan Password wajib diisi.');
            redirect('portal/login');
            return;
        }

        $hash_pass = sha1($raw_pass);

        $sql = "SELECT * FROM employee WHERE (em_email = ? OR em_code = ? OR em_id = ?) AND em_password = ? AND status = 'ACTIVE' LIMIT 1";
        $query = $this->db->query($sql, array($email_or_nik, $email_or_nik, $email_or_nik, $hash_pass));

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('user_login_access', '1');
            $this->session->set_userdata('user_login_id', $row->em_id);
            $this->session->set_userdata('name', $row->first_name);
            $this->session->set_userdata('email', $row->em_email);
            $this->session->set_userdata('user_image', $row->em_image);
            $this->session->set_userdata('user_type', $row->em_role);
            redirect('portal');
        } else {
            $this->session->set_flashdata('feedback', 'NIK / Email atau Password salah.');
            redirect('portal/login');
        }
    }

    public function index() {
        // Authenticate employee session
        if ($this->session->userdata('user_login_access') != 1) {
            redirect('portal/login');
            return;
        }

        $em_id = $this->session->userdata('user_login_id');
        $data = array();

        // 1. Employee Detail
        $data['employee'] = $this->employee_model->GetBasic($em_id);
        if (!$data['employee']) {
            $data['employee'] = $this->db->get_where('employee', array('em_code' => $em_id))->row();
        }

        $em_code = isset($data['employee']->em_code) ? $data['employee']->em_code : $em_id;
        $dep_id = isset($data['employee']->dep_id) ? $data['employee']->dep_id : '';
        $des_id = isset($data['employee']->des_id) ? $data['employee']->des_id : '';

        // Department Name & Designation Name
        $data['department'] = $this->db->get_where('department', array('id' => $dep_id))->row();
        $data['designation'] = $this->db->get_where('designation', array('id' => $des_id))->row();

        // Manager Info (Dynamic Department & Designation Resolution)
        $manager = null;
        $div_id = isset($data['employee']->division_id) ? $data['employee']->division_id : '';

        // 1. Check if department has specific manager_id assigned
        if (!empty($data['department']->manager_id)) {
            $mgr_val = $data['department']->manager_id;
            $dept_assigned_mgr = $this->db->query("SELECT * FROM employee WHERE (em_id = ? OR em_code = ?) AND status = 'ACTIVE' LIMIT 1", array($mgr_val, $mgr_val))->row();
            // If the current user is NOT this manager, set as manager
            if ($dept_assigned_mgr && $dept_assigned_mgr->em_id != $em_id && $dept_assigned_mgr->em_code != $em_code) {
                $manager = $dept_assigned_mgr;
            }
        }

        // 2. If not set or current user is the department manager, detect manager by designation in same department
        if (!$manager && !empty($dep_id)) {
            $this->db->select('e.*');
            $this->db->from('employee e');
            $this->db->join('designation d', 'd.id = e.des_id', 'left');
            $this->db->where('e.dep_id', $dep_id);
            $this->db->where('e.em_id !=', $em_id);
            $this->db->where('e.em_code !=', $em_code);
            $this->db->where('e.status', 'ACTIVE');
            $this->db->group_start();
            $this->db->like('d.des_name', 'Manager');
            $this->db->or_like('d.des_name', 'Head');
            $this->db->or_where('e.em_role', 'ADMIN');
            $this->db->group_end();
            $this->db->order_by("CASE WHEN d.des_name LIKE '%Manager%' THEN 1 ELSE 2 END", "ASC", FALSE);
            $this->db->limit(1);
            $dept_mgr = $this->db->get()->row();
            if ($dept_mgr) {
                $manager = $dept_mgr;
            }
        }

        // 3. If still not found and employee has division_id, look in same division
        if (!$manager && !empty($div_id)) {
            $this->db->select('e.*');
            $this->db->from('employee e');
            $this->db->join('designation d', 'd.id = e.des_id', 'left');
            $this->db->where('e.division_id', $div_id);
            $this->db->where('e.em_id !=', $em_id);
            $this->db->where('e.em_code !=', $em_code);
            $this->db->where('e.status', 'ACTIVE');
            $this->db->group_start();
            $this->db->like('d.des_name', 'Manager');
            $this->db->or_like('d.des_name', 'Head');
            $this->db->or_where('e.em_role', 'ADMIN');
            $this->db->group_end();
            $this->db->order_by("CASE WHEN d.des_name LIKE '%Manager%' THEN 1 ELSE 2 END", "ASC", FALSE);
            $this->db->limit(1);
            $div_mgr = $this->db->get()->row();
            if ($div_mgr) {
                $manager = $div_mgr;
            }
        }

        // 4. Fallback: If current user is the manager or no department manager found, manager is Super Admin / Director
        if (!$manager) {
            $manager = $this->db->where('em_role', 'SUPER ADMIN')->where('status', 'ACTIVE')->get('employee')->row();
        }

        $data['manager'] = $manager;

        // 2. Settings
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();

        // 3. Attendance Today (Clocked In Banner)
        $today = date('Y-m-d');
        $data['attendance_today'] = $this->db->query("
            SELECT * FROM attendance 
            WHERE (emp_id = ? OR emp_id = ?) 
              AND atten_date = ? 
            ORDER BY id DESC 
            LIMIT 1
        ", array($em_code, $em_id, $today))->row();

        // Fallback: if no record for today yet (e.g. night shift crossover), check latest within 24h
        if (!$data['attendance_today']) {
            $latest = $this->db->query("
                SELECT * FROM attendance 
                WHERE (emp_id = ? OR emp_id = ?) 
                ORDER BY id DESC 
                LIMIT 1
            ", array($em_code, $em_id))->row();
            if ($latest && ($latest->atten_date == $today || $latest->atten_date == date('Y-m-d', strtotime('-1 day')))) {
                $data['attendance_today'] = $latest;
            }
        }

        // 4. Monthly Attendance Logs
        $current_month = date('m');
        $current_year = date('Y');
        $data['attendance_logs'] = $this->db->query("
            SELECT * FROM attendance 
            WHERE (emp_id = ? OR emp_id = ?) 
              AND MONTH(atten_date) = ? 
              AND YEAR(atten_date) = ? 
            ORDER BY atten_date DESC, id DESC
        ", array($em_code, $em_id, $current_month, $current_year))->result();

        // 5. Salary & Payslip Data
        $data['salary_info'] = $this->payroll_model->getAllSalaryDataByMonthYearEm($em_id, date('F'), date('Y'));
        if (!$data['salary_info']) {
            $data['salary_info'] = $this->db->query("SELECT * FROM pay_salary WHERE emp_id = ? ORDER BY pay_id DESC LIMIT 1", array($em_id))->row();
        }

        // 6. Biometric Face Template Status (v2 InsightFace)
        $face_query = $this->db->query("SELECT * FROM employee_face_templates WHERE (em_code = ? OR em_code = ?) AND is_active = 1 LIMIT 1", array($em_code, $em_id));
        $data['face_signature'] = $face_query->row();

        // 7. Leave Management & Balance
        $data['pending_leaves'] = $this->db->get_where('emp_leave', array('em_id' => $em_code, 'leave_status' => 'Not Approve'))->num_rows();
        $data['leave_history'] = $this->db->get_where('emp_leave', array('em_id' => $em_code))->result();

        // 8. Company Announcements / Notices with Digital Signatures & Complete History
        $data['all_notices'] = $this->db->query("
            SELECT n.*, 
                   ns.signed_at, 
                   ns.status AS sign_status,
                   (SELECT COUNT(*) FROM notice_signatures WHERE notice_id = n.id) AS total_signed,
                   (SELECT COUNT(*) FROM employee WHERE status = 'ACTIVE') AS total_employees
            FROM notice n
            LEFT JOIN notice_signatures ns ON n.id = ns.notice_id AND (ns.em_id = ? OR ns.em_id = ?)
            ORDER BY n.id DESC
        ", array($em_id, $em_code))->result();

        $pending_count = 0;
        foreach ($data['all_notices'] as $n_item) {
            if (!empty($n_item->is_mandatory) && empty($n_item->sign_status)) {
                $pending_count++;
            }
        }
        $data['pending_notices_count'] = $pending_count;
        $data['announcements'] = array_slice($data['all_notices'], 0, 5); // top 5 for dashboard summary

        // 9. Employee-Scoped Backend Modules (Brought from Admin Backend)
        // A. Assigned Projects & Tasks
        $data['my_projects'] = $this->project_model->GetEmProjectsValue($em_id);
        
        // B. Assigned Loans & Installments (Querying 'loan' table)
        $data['my_loans'] = $this->db->get_where('loan', array('emp_id' => $em_code))->result();
        if(empty($data['my_loans'])) {
            $data['my_loans'] = $this->db->get_where('loan', array('emp_id' => $em_id))->result();
        }

        // C. Field Visits / Dinas Luar
        $data['my_field_visits'] = $this->db->query("SELECT field_visit.*, project.pro_name FROM field_visit LEFT JOIN project ON field_visit.project_id = project.id WHERE field_visit.emp_id = ? OR field_visit.emp_id = ?", array($em_id, $em_code))->result();

        // D. Personal To-Do List
        $data['my_todos'] = $this->dashboard_model->GettodoInfo($em_id);

        // E. Assigned Company Assets & Logistics
        $data['my_assets'] = $this->db->query("SELECT logistic_assign.*, assets.ass_name FROM logistic_assign LEFT JOIN assets ON logistic_assign.asset_id = assets.ass_id WHERE logistic_assign.assign_id = ? OR logistic_assign.assign_id = ?", array($em_id, $em_code))->result();

        // F. Employee Reimbursement Batches & Items
        $batches = $this->db->query("SELECT * FROM reimbursement_batch WHERE em_id = ? OR em_id = ? ORDER BY id DESC", array($em_id, $em_code))->result();
        foreach ($batches as &$b) {
            $b->items = $this->db->get_where('reimbursement', array('batch_id' => $b->id))->result();
        }
        $data['my_reimbursement_batches'] = $batches;
        $data['my_reimbursements'] = $this->db->query("SELECT * FROM reimbursement WHERE em_id = ? OR em_id = ? ORDER BY id DESC", array($em_id, $em_code))->result();

        // 10. Comprehensive Real-Time Calendar Matrix Engine
        $first_day_of_month = date('Y-m-01');
        $last_day_of_month = date('Y-m-t');
        $days_in_month = (int)date('t');

        $calendar_matrix = array();
        for ($d = 1; $d <= $days_in_month; $d++) {
            $date_str = date('Y-m-') . sprintf('%02d', $d);
            $calendar_matrix[$d] = array(
                'day' => $d,
                'date' => $date_str,
                'is_holiday' => false,
                'holiday_name' => '',
                'has_event' => false,
                'event_title' => '',
                'attendance' => null,
                'leave' => null,
                'status' => 'NONE'
            );
        }

        // Map Holidays
        $holidays = $this->db->query("SELECT * FROM holiday WHERE from_date <= ? AND to_date >= ?", array($last_day_of_month, $first_day_of_month))->result();
        foreach ($holidays as $h) {
            $start = max(strtotime($first_day_of_month), strtotime($h->from_date));
            $end = min(strtotime($last_day_of_month), strtotime($h->to_date));
            for ($i = $start; $i <= $end; $i += 86400) {
                $day_num = (int)date('j', $i);
                if (isset($calendar_matrix[$day_num])) {
                    $calendar_matrix[$day_num]['is_holiday'] = true;
                    $calendar_matrix[$day_num]['holiday_name'] = $h->holiday_name;
                }
            }
        }

        // Map Events
        $events = $this->db->query("SELECT * FROM notice WHERE date >= ? AND date <= ?", array($first_day_of_month, $last_day_of_month))->result();
        foreach ($events as $ev) {
            $day_num = (int)date('j', strtotime($ev->date));
            if (isset($calendar_matrix[$day_num])) {
                $calendar_matrix[$day_num]['has_event'] = true;
                $calendar_matrix[$day_num]['event_title'] = $ev->title;
            }
        }

        // Map Attendance
        $atten_records = $this->db->query("SELECT * FROM attendance WHERE emp_id = ? AND atten_date >= ? AND atten_date <= ?", array($em_code, $first_day_of_month, $last_day_of_month))->result();
        foreach ($atten_records as $att) {
            $day_num = (int)date('j', strtotime($att->atten_date));
            if (isset($calendar_matrix[$day_num])) {
                $calendar_matrix[$day_num]['attendance'] = $att;
                $signin_time = $att->signin_time;
                if (empty($signin_time) || strtotime($signin_time) > strtotime('09:00:00')) {
                    $calendar_matrix[$day_num]['status'] = 'NOT_CLEAR';
                } else {
                    $calendar_matrix[$day_num]['status'] = 'CLEAR';
                }
            }
        }

        // Map Leaves
        $leave_records = $this->db->query("SELECT * FROM emp_leave WHERE em_id = ? AND start_date <= ? AND end_date >= ?", array($em_code, $last_day_of_month, $first_day_of_month))->result();
        foreach ($leave_records as $lv) {
            $start = max(strtotime($first_day_of_month), strtotime($lv->start_date));
            $end = min(strtotime($last_day_of_month), strtotime($lv->end_date));
            for ($i = $start; $i <= $end; $i += 86400) {
                $day_num = (int)date('j', $i);
                if (isset($calendar_matrix[$day_num])) {
                    $calendar_matrix[$day_num]['leave'] = $lv;
                    $calendar_matrix[$day_num]['status'] = 'NOT_CLEAR';
                }
            }
        }

        $data['calendar_matrix'] = $calendar_matrix;
        $data['days_in_month'] = $days_in_month;
        $data['month_start_weekday'] = (int)date('w', strtotime($first_day_of_month));

        // Render portal view
        $this->load->view('backend/employee_portal', $data);
    }

    public function scan_receipt_ai() {
        header('Content-Type: application/json');
        
        if ($this->session->userdata('user_login_access') != 1) {
            echo json_encode(array('status' => '401', 'success' => false, 'message' => 'Sesi login telah berakhir.'));
            return;
        }

        $api_key = trim($this->input->post('api_key'));
        $provider = trim($this->input->post('provider'));
        $model_name = trim($this->input->post('model'));

        if (empty($model_name) || $model_name === 'kimi-k3-free') {
            $model_name = 'agnes-2.5-flash';
        }

        if (!isset($_FILES['receipt_file']) || $_FILES['receipt_file']['error'] != UPLOAD_ERR_OK) {
            echo json_encode(array('status' => '400', 'success' => false, 'message' => 'Upload bukti nota/kuitansi gagal.'));
            return;
        }

        $upload_dir = FCPATH . 'uploads/reimbursement/';
        if (!file_exists($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        $file_tmp = $_FILES['receipt_file']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION));
        $file_name = 'receipt_' . time() . '_' . rand(1000, 9999) . '.' . $file_ext;
        $file_path = $upload_dir . $file_name;

        if (!move_uploaded_file($file_tmp, $file_path)) {
            echo json_encode(array('status' => '500', 'success' => false, 'message' => 'Gagal menyimpan file di server.'));
            return;
        }

        // Build array of images to analyze (per-page for PDFs, single for images)
        $image_pages = array(); // array of ['base64' => ..., 'mime' => ...]

        if ($file_ext === 'pdf') {
            $page_files = $this->process_pdf_to_pages($file_path);
            if (!empty($page_files)) {
                foreach ($page_files as $pf) {
                    $image_pages[] = array(
                        'base64' => base64_encode(file_get_contents($pf)),
                        'mime' => 'image/png'
                    );
                    @unlink($pf);
                }
            } else {
                // Fallback: send raw PDF
                $image_pages[] = array(
                    'base64' => base64_encode(file_get_contents($file_path)),
                    'mime' => 'application/pdf'
                );
            }
        } else {
            $image_pages[] = array(
                'base64' => $this->get_optimized_image_base64($file_path),
                'mime' => 'image/jpeg'
            );
        }

        // Universal High-Precision Financial AI System Prompt (Chain-of-Thought)
        $prompt = "Kamu adalah Pakar AI Analisis Keuangan & OCR Multimodal Berpresisi Tinggi.\n\n"
            . "TUGAS: Analisis gambar bukti transaksi ini dengan SANGAT TELITI. Gambar bisa berisi nota tunggal, banyak nota/struk terpisah, grid kartu, daftar item, invoice, atau kombinasi apapun.\n\n"
            . "METODE ANALISIS (WAJIB DIIKUTI STEP-BY-STEP):\n"
            . "STEP 1 - IDENTIFIKASI: Telusuri seluruh gambar. Identifikasi SETIAP nota/struk/kartu transaksi individual. Hitung jumlah totalnya.\n"
            . "STEP 2 - BACA ANGKA PERSIS: Untuk SETIAP nota, baca ANGKA NOMINAL yang TERCETAK PERSIS di kertas. JANGAN MENEBAK atau MENGASUMSIKAN nominal berdasarkan nama tempat/lokasi/merchant. Satu lokasi yang sama BISA memiliki tarif BERBEDA. Baca digit demi digit secara hati-hati.\n"
            . "STEP 3 - CATAT: Untuk setiap nota, catat: (nomor urut) tanggal - lokasi/merchant - nominal PERSIS yang terbaca dari kertas.\n"
            . "STEP 4 - JUMLAHKAN: Total = penjumlahan aritmatika semua nominal dari Step 3.\n\n"
            . "ATURAN KRITIS:\n"
            . "- BACA angka yang TERCETAK, jangan asumsikan dari nama lokasi.\n"
            . "- Perhatikan setiap digit: '7.500' (4 digit) BERBEDA dengan '11.000' (5 digit) BERBEDA dengan '500' (3 digit).\n"
            . "- Field 'amount' = ANGKA MURNI tanpa 'Rp', tanpa titik ribuan (contoh: 112500).\n\n"
            . "Kembalikan HANYA 1 Object JSON murni (TANPA markdown fences, TANPA backtick):\n"
            . "{\n"
            . '  "merchant": "Nama merchant/tempat",' . "\n"
            . '  "title": "Judul singkat reimbursement",' . "\n"
            . '  "amount": 112500,' . "\n"
            . '  "date": "YYYY-MM-DD",' . "\n"
            . '  "category": "Transportasi / Konsumsi / Perjalanan Dinas / Operasional Kantor / Kesehatan / Umum",' . "\n"
            . '  "description": "Rincian per nota dan perhitungan total"' . "\n"
            . "}";

        $ai_result = null;
        $raw_response = null;

        if (empty($api_key)) {
            $api_key = getenv('AI_OCR_API_KEY') ?: getenv('NARAROUTER_API_KEY') ?: getenv('OPENROUTER_API_KEY') ?: '';
        }

        if (empty($api_key)) {
            echo json_encode(array('status' => '400', 'success' => false, 'message' => 'API Key AI belum dikonfigurasi di server (.env) atau formulir.'));
            return;
        }

        // Process each page through AI and aggregate results
        $all_page_results = array();

        // Check Provider (OpenRouter / NaraRouter / Direct Gemini)
        if ($provider === 'openrouter' || $provider === 'nararouter' || strpos($api_key, 'sk-nry-') === 0 || strpos($api_key, 'sk-or-') === 0 || $provider !== 'gemini') {
            $base_api_url = ($provider === 'openrouter' && strpos($api_key, 'sk-or-') === 0) ? 'https://openrouter.ai/api/v1/chat/completions' : 'https://router.bynara.id/v1/chat/completions';

            foreach ($image_pages as $page_idx => $page_data) {
                $page_prompt = $prompt;
                if (count($image_pages) > 1) {
                    $page_prompt .= "\n\nINFO: Ini adalah halaman " . ($page_idx + 1) . " dari " . count($image_pages) . " halaman. Analisis HANYA isi halaman ini.";
                }

                $ch = curl_init($base_api_url);
                $payload = array(
                    'model' => $model_name,
                    'messages' => array(
                        array(
                            'role' => 'user',
                            'content' => array(
                                array('type' => 'text', 'text' => $page_prompt),
                                array('type' => 'image_url', 'image_url' => array('url' => 'data:' . $page_data['mime'] . ';base64,' . $page_data['base64']))
                            )
                        )
                    ),
                    'max_tokens' => 2500,
                    'temperature' => 0.1
                );
                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $api_key
                );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $response = curl_exec($ch);
                curl_close($ch);
                $raw_response = $response;

                if ($response) {
                    $res_obj = json_decode($response, true);
                    if (isset($res_obj['choices'][0]['message']['content'])) {
                        $ai_text = $res_obj['choices'][0]['message']['content'];
                        $ai_text = preg_replace('/```json\s*/i', '', $ai_text);
                        $ai_text = preg_replace('/```\s*/i', '', $ai_text);
                        $parsed = json_decode(trim($ai_text), true);
                        if (is_array($parsed)) {
                            $all_page_results[] = $parsed;
                        }
                    }
                }
            }

            // Aggregate results from all pages
            if (count($all_page_results) === 1) {
                $ai_result = $all_page_results[0];
                if (isset($ai_result['amount'])) {
                    $ai_result['amount'] = $this->clean_currency_to_float($ai_result['amount']);
                }
            } elseif (count($all_page_results) > 1) {
                $total_amt = 0;
                $descs = array();
                $merchant = '';
                $title_parts = array();
                $cat = 'Umum';
                foreach ($all_page_results as $idx => $pr) {
                    if (isset($pr['amount'])) $total_amt += $this->clean_currency_to_float($pr['amount']);
                    if (isset($pr['description'])) $descs[] = '[Hal.' . ($idx+1) . '] ' . $pr['description'];
                    if (empty($merchant) && isset($pr['merchant'])) $merchant = $pr['merchant'];
                    if (isset($pr['title'])) $title_parts[] = $pr['title'];
                    if (isset($pr['category'])) $cat = $pr['category'];
                }
                $ai_result = array(
                    'merchant' => $merchant,
                    'title' => $title_parts[0] ?? 'Reimbursement',
                    'amount' => $total_amt,
                    'category' => $cat,
                    'description' => implode("\n", $descs)
                );
            }
        } else {
            // Default Direct Google Gemini API
            $target_key = !empty($api_key) ? $api_key : (getenv('GEMINI_API_KEY') ?: '');
            if (empty($target_key)) {
                echo json_encode(array('status' => '400', 'success' => false, 'message' => 'Gemini API Key belum dikonfigurasi di server (.env) atau formulir.'));
                return;
            }
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $target_key;
            
            $payload = array(
                'contents' => array(
                    array(
                        'parts' => array(
                            array('text' => $prompt),
                            array(
                                'inline_data' => array(
                                    'mime_type' => $mime_type,
                                    'data' => $image_base64
                                )
                            )
                        )
                    )
                )
            );

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            curl_close($ch);
            $raw_response = $response;

            if ($response) {
                $res_obj = json_decode($response, true);
                if (isset($res_obj['candidates'][0]['content']['parts'][0]['text'])) {
                    $ai_text = $res_obj['candidates'][0]['content']['parts'][0]['text'];
                    $ai_text = preg_replace('/```json\s*/i', '', $ai_text);
                    $ai_text = preg_replace('/```\s*/i', '', $ai_text);
                    $ai_result = json_decode(trim($ai_text), true);
                }
            }
        }

        echo json_encode(array(
            'status' => '200',
            'success' => true,
            'image_url' => base_url('uploads/reimbursement/' . $file_name),
            'file_name' => $file_name,
            'ai_data' => $ai_result,
            'raw_response' => $raw_response
        ));
    }

    public function save_reimbursement() {
        header('Content-Type: application/json');
        
        if ($this->session->userdata('user_login_access') != 1) {
            echo json_encode(array('status' => '401', 'success' => false, 'message' => 'Sesi login telah berakhir.'));
            return;
        }

        $em_id = $this->session->userdata('user_login_id');
        $title = $this->input->post('title');
        $amount = (float)$this->input->post('amount');
        $category = $this->input->post('category');
        $description = $this->input->post('description');
        $receipt_image = $this->input->post('receipt_image');
        $ai_analysis = $this->input->post('ai_analysis');

        if (empty($title) || $amount <= 0) {
            echo json_encode(array('status' => '400', 'success' => false, 'message' => 'Judul dan Nominal klaim wajib diisi.'));
            return;
        }

        $data_insert = array(
            'em_id' => $em_id,
            'title' => $title,
            'category' => !empty($category) ? $category : 'Umum',
            'amount' => $amount,
            'receipt_image' => $receipt_image,
            'description' => $description,
            'ai_analysis' => $ai_analysis,
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('reimbursement', $data_insert);

        echo json_encode(array(
            'status' => '200',
            'success' => true,
            'message' => 'Pengajuan klaim reimbursement berhasil dikirim!'
        ));
    }

    private function get_optimized_image_base64($src_path) {
        if (!file_exists($src_path)) {
            return '';
        }
        $info = @getimagesize($src_path);
        if (!$info) {
            return base64_encode(file_get_contents($src_path));
        }

        $w = $info[0];
        $h = $info[1];
        $type = $info[2];
        $max_dim = 2048;
        
        if ($w > $max_dim || $h > $max_dim) {
            $ratio = min($max_dim / $w, $max_dim / $h);
            $new_w = (int)round($w * $ratio);
            $new_h = (int)round($h * $ratio);
        } else {
            $new_w = $w;
            $new_h = $h;
        }

        $src = null;
        if ($type == IMAGETYPE_JPEG) $src = @imagecreatefromjpeg($src_path);
        elseif ($type == IMAGETYPE_PNG) $src = @imagecreatefrompng($src_path);
        elseif ($type == IMAGETYPE_WEBP) $src = @imagecreatefromwebp($src_path);

        if (!$src) {
            return base64_encode(file_get_contents($src_path));
        }

        $dst = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

        ob_start();
        imagejpeg($dst, null, 90);
        $compressed_data = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return base64_encode($compressed_data);
    }

    public function save_bulk_reimbursement() {
        header('Content-Type: application/json');
        
        if ($this->session->userdata('user_login_access') != 1) {
            echo json_encode(array('status' => '401', 'success' => false, 'message' => 'Sesi login telah berakhir.'));
            return;
        }

        $em_id = $this->session->userdata('user_login_id');
        $batch_title = trim($this->input->post('batch_title'));
        $raw_items = $this->input->post('items');

        if (empty($batch_title)) {
            $batch_title = 'Pengajuan Klaim Kolektif - ' . date('d M Y');
        }

        if (empty($raw_items)) {
            echo json_encode(array('status' => '400', 'success' => false, 'message' => 'Belum ada transaksi di temporary list.'));
            return;
        }

        $items = is_array($raw_items) ? $raw_items : json_decode($raw_items, true);
        if (empty($items) || !is_array($items)) {
            echo json_encode(array('status' => '400', 'success' => false, 'message' => 'Format item transaksi tidak valid.'));
            return;
        }

        $grand_total = 0;
        foreach ($items as $it) {
            $grand_total += (float)($it['amount'] ?? 0);
        }

        $batch_code = 'RB-' . date('Ymd') . '-' . rand(1000, 9999);

        $data_batch = array(
            'batch_code' => $batch_code,
            'em_id' => $em_id,
            'batch_title' => $batch_title,
            'total_items' => count($items),
            'grand_total' => $grand_total,
            'status' => 'PENDING',
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('reimbursement_batch', $data_batch);
        $batch_id = $this->db->insert_id();

        foreach ($items as $item) {
            $data_item = array(
                'batch_id' => $batch_id,
                'em_id' => $em_id,
                'title' => !empty($item['title']) ? $item['title'] : 'Klaim Nota',
                'category' => !empty($item['category']) ? $item['category'] : 'Umum',
                'amount' => (float)($item['amount'] ?? 0),
                'receipt_image' => !empty($item['receipt_image']) ? $item['receipt_image'] : null,
                'description' => !empty($item['description']) ? $item['description'] : null,
                'ai_analysis' => !empty($item['ai_analysis']) ? (is_array($item['ai_analysis']) ? json_encode($item['ai_analysis']) : $item['ai_analysis']) : null,
                'status' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('reimbursement', $data_item);
        }

        echo json_encode(array(
            'status' => '200',
            'success' => true,
            'message' => 'Pengajuan Klaim Batch (' . count($items) . ' Nota, Total Rp ' . number_format($grand_total, 0, ',', '.') . ') berhasil dikirim!',
            'batch_code' => $batch_code
        ));
    }

    private function clean_currency_to_float($val) {
        if (is_numeric($val)) {
            return (float)$val;
        }
        if (empty($val)) {
            return 0;
        }
        $str = preg_replace('/[^\d\.,]/', '', (string)$val);
        if (empty($str)) {
            return 0;
        }
        if (strpos($str, '.') !== false && strpos($str, ',') !== false) {
            $str = str_replace('.', '', $str);
            $str = str_replace(',', '.', $str);
        } elseif (strpos($str, '.') !== false) {
            $parts = explode('.', $str);
            if (count($parts) > 1 && strlen(end($parts)) == 3) {
                $str = str_replace('.', '', $str);
            }
        } else {
            $str = str_replace(',', '.', $str);
        }
        return (float)$str;
    }

    /**
     * Render PDF to per-page PNG images at 300 DPI for per-page AI analysis.
     * Returns array of PNG file paths, or empty array on failure.
     */
    private function process_pdf_to_pages($pdf_path) {
        if (!file_exists($pdf_path)) {
            return array();
        }
        $tmp_dir = FCPATH . 'uploads/reimbursement/';
        $output_prefix = $tmp_dir . 'pdfpage_' . md5($pdf_path . time());
        $script_path = APPPATH . 'helpers/pdf_stitcher.py';

        $cmd = "DYLD_LIBRARY_PATH=\"\" PATH=\"/usr/bin:/bin:/usr/sbin:/sbin:/opt/miniconda3/bin\" " . escapeshellarg($script_path) . " " . escapeshellarg($pdf_path) . " " . escapeshellarg($output_prefix) . " 2>&1";
        $output = array();
        @exec($cmd, $output);

        $page_count = 0;
        foreach ($output as $line) {
            if (strpos($line, 'SUCCESS:') === 0) {
                $page_count = (int)str_replace('SUCCESS:', '', $line);
            }
        }

        $page_files = array();
        for ($i = 1; $i <= $page_count; $i++) {
            $pf = $output_prefix . '_page_' . $i . '.png';
            if (file_exists($pf) && filesize($pf) > 0) {
                $page_files[] = $pf;
            }
        }

        return $page_files;
    }

    /**
     * Digital PIN Verification & Signature for Notice Board
     */
    public function sign_notice() {
        if ($this->session->userdata('user_login_access') == False) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi login tidak valid. Silakan muat ulang halaman.']);
            return;
        }

        $notice_id = (int)$this->input->post('notice_id');
        $input_pin = trim($this->input->post('pin'));
        $em_id = $this->session->userdata('user_login_id');

        if (empty($notice_id) || empty($input_pin)) {
            echo json_encode(['status' => 'error', 'message' => 'ID Pengumuman dan PIN wajib diisi.']);
            return;
        }

        // 1. Verifikasi Employee & PIN
        $employee = $this->db->get_where('employee', array('em_id' => $em_id))->row();
        if (!$employee) {
            $employee = $this->db->get_where('employee', array('em_code' => $em_id))->row();
        }

        if (!$employee) {
            echo json_encode(['status' => 'error', 'message' => 'Data karyawan tidak ditemukan.']);
            return;
        }

        $actual_pin = !empty($employee->pin) ? $employee->pin : '1234';
        if ($input_pin !== $actual_pin) {
            echo json_encode(['status' => 'error', 'message' => 'PIN salah! Silakan coba lagi (Default PIN: 1234).']);
            return;
        }

        // 2. Verifikasi Notice
        $notice = $this->db->get_where('notice', array('id' => $notice_id))->row();
        if (!$notice) {
            echo json_encode(['status' => 'error', 'message' => 'Surat edaran/pengumuman tidak ditemukan.']);
            return;
        }

        // 3. Cek apakah sudah ditandatangani
        $existing = $this->db->get_where('notice_signatures', array('notice_id' => $notice_id, 'em_id' => $employee->em_id))->row();
        if ($existing) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Pengumuman ini sudah Anda tanda tangani sebelumnya.',
                'signed_at' => date('d M Y, H:i', strtotime($existing->signed_at)) . ' WIB'
            ]);
            return;
        }

        // 4. Simpan Tanda Tangan Digital
        $signature_data = array(
            'notice_id'   => $notice_id,
            'em_id'       => $employee->em_id,
            'signed_at'   => date('Y-m-d H:i:s'),
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => substr($this->input->user_agent(), 0, 250),
            'status'      => 'SIGNED'
        );

        $this->db->insert('notice_signatures', $signature_data);

        // Hitung total tanda tangan terbaru
        $total_signed = $this->db->where('notice_id', $notice_id)->count_all_results('notice_signatures');
        $total_emp = $this->db->where('status', 'ACTIVE')->count_all_results('employee');

        echo json_encode([
            'status' => 'success',
            'message' => 'Tanda tangan digital berhasil diverifikasi dengan PIN Anda.',
            'signed_at' => date('d M Y, H:i') . ' WIB',
            'total_signed' => $total_signed,
            'total_emp' => $total_emp
        ]);
    }

    /**
     * Update Employee PIN
     */
    public function update_pin() {
        if ($this->session->userdata('user_login_access') == False) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi login tidak valid.']);
            return;
        }

        $old_pin = trim($this->input->post('old_pin'));
        $new_pin = trim($this->input->post('new_pin'));
        $em_id = $this->session->userdata('user_login_id');

        if (empty($old_pin) || empty($new_pin)) {
            echo json_encode(['status' => 'error', 'message' => 'PIN Lama dan PIN Baru wajib diisi.']);
            return;
        }

        if (strlen($new_pin) < 4 || strlen($new_pin) > 6 || !ctype_digit($new_pin)) {
            echo json_encode(['status' => 'error', 'message' => 'PIN Baru harus berupa 4-6 digit angka.']);
            return;
        }

        $employee = $this->db->get_where('employee', array('em_id' => $em_id))->row();
        if (!$employee) {
            $employee = $this->db->get_where('employee', array('em_code' => $em_id))->row();
        }

        $actual_pin = !empty($employee->pin) ? $employee->pin : '1234';
        if ($old_pin !== $actual_pin) {
            echo json_encode(['status' => 'error', 'message' => 'PIN Lama Anda tidak cocok.']);
            return;
        }

        $this->db->where('id', $employee->id)->update('employee', array('pin' => $new_pin));

        echo json_encode(['status' => 'success', 'message' => 'PIN Anda berhasil diperbarui menjadi ' . $new_pin . '.']);
    }

    /**
     * Update Employee Login Password
     */
    public function update_password() {
        if ($this->session->userdata('user_login_access') == False) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi login tidak valid. Silakan login kembali.']);
            return;
        }

        $old_pass = $this->input->post('old_password');
        $new_pass = $this->input->post('new_password');
        $confirm_pass = $this->input->post('confirm_password');
        $em_id = $this->session->userdata('user_login_id');

        if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua kolom password wajib diisi.']);
            return;
        }

        if (strlen($new_pass) < 6) {
            echo json_encode(['status' => 'error', 'message' => 'Password Baru minimal 6 karakter.']);
            return;
        }

        if ($new_pass !== $confirm_pass) {
            echo json_encode(['status' => 'error', 'message' => 'Konfirmasi Password Baru tidak cocok.']);
            return;
        }

        $employee = $this->db->get_where('employee', array('em_id' => $em_id))->row();
        if (!$employee) {
            $employee = $this->db->get_where('employee', array('em_code' => $em_id))->row();
        }

        if (!$employee) {
            echo json_encode(['status' => 'error', 'message' => 'Data karyawan tidak ditemukan.']);
            return;
        }

        if (sha1($old_pass) !== $employee->em_password) {
            echo json_encode(['status' => 'error', 'message' => 'Password Lama Anda tidak cocok.']);
            return;
        }

        $this->db->where('id', $employee->id)->update('employee', array('em_password' => sha1($new_pass)));

        echo json_encode([
            'status' => 'success', 
            'message' => 'Password akun berhasil diperbarui! Silakan gunakan password baru ini pada sesi login berikutnya.'
        ]);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('portal/login');
    }
}
