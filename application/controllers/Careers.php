<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Careers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('recruitment_model');
        $this->load->library('gemini_cv_parser');
    }

    public function index() {
        $this->db->where('status', 'OPEN');
        $this->db->order_by('id', 'DESC');
        $data['jobs'] = $this->db->get('job_postings')->result();
        $this->load->view('frontend/careers_list', $data);
    }

    public function detail($job_id) {
        $data['job'] = $this->recruitment_model->get_job($job_id);
        if (!$data['job'] || $data['job']->status !== 'OPEN') {
            show_404();
        }
        $this->load->view('frontend/apply_form', $data);
    }

    public function submit_application() {
        $job_id = $this->input->post('job_id', TRUE);
        $job = $this->recruitment_model->get_job($job_id);

        if (!$job || $job->status !== 'OPEN') {
            show_404();
        }

        $applicant_name = $this->input->post('name', TRUE);
        $applicant_email = $this->input->post('email', TRUE);
        $applicant_phone = $this->input->post('phone', TRUE);

        // Upload PDF CV Handling
        $config['upload_path'] = './uploads/jobs/job_' . $job_id . '/';
        $config['allowed_types'] = 'pdf|doc|docx';
        $config['max_size'] = 5120; // 5MB
        $config['encrypt_name'] = TRUE;

        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('cv_file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('careers/apply/' . $job_id);
            return;
        }

        $upload_data = $this->upload->data();
        $filepath = $upload_data['full_path'];
        $relative_cv_path = 'uploads/jobs/job_' . $job_id . '/' . $upload_data['file_name'];

        // Extract CV text
        $content = @file_get_contents($filepath);
        $clean_text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $content);
        $cv_text = substr($clean_text, 0, 4000);

        // Process with AI Parser
        $parsed = $this->gemini_cv_parser->parse_and_score($cv_text, $job->job_title, $job->requirements);

        // Combine user input with AI extracted data if empty
        $final_name = !empty($applicant_name) ? $applicant_name : $parsed['name'];
        $final_email = !empty($applicant_email) ? $applicant_email : $parsed['email'];
        $final_phone = !empty($applicant_phone) ? $applicant_phone : $parsed['phone'];

        $applicant_data = array(
            'job_id' => $job_id,
            'name' => $final_name,
            'email' => $final_email,
            'phone' => $final_phone,
            'cv_file' => $relative_cv_path,
            'skills_json' => $parsed['skills'],
            'experience_summary' => $parsed['summary'],
            'match_score' => $parsed['match_score'],
            'match_reason' => $parsed['match_reason'],
            'status' => ($parsed['match_score'] >= 75) ? 'SHORTLISTED' : 'APPLIED'
        );

        $this->recruitment_model->insert_applicant($applicant_data);
        $this->session->set_flashdata('success', 'Terima kasih! Lamaran Anda telah diterima dan sedang diproses oleh AI HR Parser.');
        redirect('careers/apply/' . $job_id);
    }
}
