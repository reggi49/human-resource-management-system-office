<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('settings_model');
        $this->load->model('recruitment_model');
    }

    public function index() {
        // Direct ke Portal Karyawan sesuai permintaan
        redirect('portal');
    }

    public function compro() {
        $data = array();
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $data['total_employees'] = $this->db->where('status', 'ACTIVE')->count_all_results('employee');
        $data['total_departments'] = $this->db->count_all_results('department');
        
        $this->db->where('status', 'OPEN');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(3);
        $data['open_jobs'] = $this->db->get('job_postings')->result();
        
        $this->load->view('frontend/compro', $data);
    }
}
