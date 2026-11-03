<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recruitment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('recruitment_model');
        $this->load->library('gemini_cv_parser');

        // Session & RBAC Permission Guard
        check_admin_access();
        check_permission('recruitment.manage');
    }

    public function index() {
        $data['jobs'] = $this->recruitment_model->get_all_jobs();
        $this->load->view('backend/recruitment/jobs', $data);
    }

    public function create_job() {
        if ($this->input->post()) {
            $job_title = $this->input->post('job_title', TRUE);
            $department = $this->input->post('department', TRUE);
            $requirements = $this->input->post('requirements', TRUE);
            $closing_date = $this->input->post('closing_date', TRUE);

            $job_data = array(
                'job_title' => $job_title,
                'department' => $department,
                'requirements' => $requirements,
                'closing_date' => $closing_date,
                'status' => 'OPEN'
            );

            $job_id = $this->recruitment_model->insert_job($job_data);

            // Create job uploads directory
            $folder_path = './uploads/jobs/job_' . $job_id;
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $this->recruitment_model->update_job($job_id, array('folder_path' => $folder_path));
            $this->session->set_flashdata('feedback', 'Lowongan pekerjaan berhasil ditambahkan.');
            redirect('recruitment', 'refresh');
        }
    }

    public function close_and_scan($job_id) {
        $job = $this->recruitment_model->get_job($job_id);
        if (!$job) {
            show_404();
        }

        // Update status to PROCESSING
        $this->recruitment_model->update_job($job_id, array('status' => 'PROCESSING'));

        $folder_path = FCPATH . 'uploads/jobs/job_' . $job_id;
        if (!file_exists($folder_path)) {
            mkdir($folder_path, 0777, true);
        }

        // Find all PDF and text files in directory
        $files = glob($folder_path . '/*.{pdf,txt,doc,docx}', GLOB_BRACE);
        $scanned_count = 0;

        if ($files && is_array($files)) {
            foreach ($files as $filepath) {
                $filename = basename($filepath);
                
                // Skip files already scanned (prefixed with scanned_)
                if (strpos($filename, 'scanned_') === 0) {
                    continue;
                }

                // Extract text content from file
                $cv_text = $this->extract_file_text($filepath);

                // Send to Gemini AI for Parsing & Scoring
                $parsed = $this->gemini_cv_parser->parse_and_score($cv_text, $job->job_title, $job->requirements);

                // Insert into applicants table
                $applicant_data = array(
                    'job_id' => $job_id,
                    'name' => $parsed['name'],
                    'email' => $parsed['email'],
                    'phone' => $parsed['phone'],
                    'cv_file' => 'uploads/jobs/job_' . $job_id . '/scanned_' . $filename,
                    'skills_json' => $parsed['skills'],
                    'experience_summary' => $parsed['summary'],
                    'match_score' => $parsed['match_score'],
                    'match_reason' => $parsed['match_reason'],
                    'status' => ($parsed['match_score'] >= 75) ? 'SHORTLISTED' : 'APPLIED'
                );

                $this->recruitment_model->insert_applicant($applicant_data);

                // Rename file to mark as processed
                $new_filepath = $folder_path . '/scanned_' . $filename;
                @rename($filepath, $new_filepath);
                $scanned_count++;
            }
        }

        // Update job status to COMPLETED
        $this->recruitment_model->update_job($job_id, array('status' => 'COMPLETED'));
        $this->session->set_flashdata('feedback', 'Batch Scan AI Selesai. Diproses: ' . $scanned_count . ' berkas CV.');
        redirect('recruitment/candidates/' . $job_id, 'refresh');
    }

    public function candidates($job_id) {
        $data['job'] = $this->recruitment_model->get_job($job_id);
        if (!$data['job']) {
            show_404();
        }
        $data['candidates'] = $this->recruitment_model->get_candidates_by_job($job_id);
        $this->load->view('backend/recruitment/candidates', $data);
    }

    public function update_status() {
        $id = $this->input->post('applicant_id');
        $status = $this->input->post('status');
        if ($id && $status) {
            $this->recruitment_model->update_applicant($id, array('status' => $status));
            echo json_encode(array('status' => 'success', 'message' => 'Status kandidat berhasil diperbarui.'));
        }
    }

    private function extract_file_text($filepath) {
        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        if ($ext === 'txt') {
            return file_get_contents($filepath);
        }

        // Fallback text reading using plain file get contents / shell pdftotext if available
        $content = @file_get_contents($filepath);
        // Clean non-printable characters for text preview
        $clean_text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', ' ', $content);
        return substr($clean_text, 0, 4000);
    }
}
