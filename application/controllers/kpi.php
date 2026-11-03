<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kpi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat library database (jika belum dimuat secara autoload)
        $this->load->database();
        // Memuat helper URL untuk fungsi base_url()
        $this->load->helper('url');
        // Memuat helper form untuk fungsi form_open(), form_close(), dll.
        $this->load->helper('form');
        // Memuat library form validation untuk validasi input
        $this->load->library('form_validation');

       
        $this->load->model('Kpis_model'); 
        $this->load->model('Evaluation_model'); 

        // Memuat model-model lain yang mungkin dibutuhkan di modul KPI ini atau oleh header/sidebar
        $this->load->model('employee_model'); // Digunakan oleh sidebar dan mungkin untuk employee_kpis()
        $this->load->model('settings_model'); // Digunakan oleh header
        $this->load->model('leave_model');    // Digunakan oleh header

        // Pastikan user sudah login dan memiliki role SUPER ADMIN / ADMIN
        check_admin_access();
    }

    // Fungsi default: menampilkan daftar definisi KPI
    public function definitions()
    {
        if($this->session->userdata('user_login_access') != False) {
            $data['kpis'] = $this->Kpis_model->get_all_kpis(); // Menggunakan Kpis_model
            $data['title'] = 'KPI Definitions';
            $data['page_title'] = 'KPI Definitions';
            $data['periods'] = $this->Evaluation_model->get_all_periods();

            // Cukup memuat view utama, karena header dan sidebar dimuat dari dalam view tersebut
            $this->load->view('backend/kpi_definitions_list', $data);
        } else {
            redirect(base_url() , 'refresh');
        }
    }

    // Fungsi untuk menampilkan form tambah KPI baru
    public function add_definition()
    {
        if($this->session->userdata('user_login_access') != False) {
            $data['title'] = 'Add New KPI Definition';
            $data['page_title'] = 'Add New KPI Definition';
            $data['kpi_categories'] = $this->Kpis_model->get_kpi_categories(); // Menggunakan Kpis_model
            $data['kpi_statuses'] = $this->Kpis_model->get_kpi_statuses(); // Menggunakan Kpis_model
            $data['periods'] = $this->Evaluation_model->get_all_periods();


            // Hanya memuat konten modal, karena modal akan dipicu dari kpi_definitions_list
            $this->load->view('backend/kpi_definitions_list', $data);
        } else {
            redirect(base_url() , 'refresh');
        }
    }

    // Fungsi untuk menyimpan KPI baru atau memperbarui yang sudah ada
    public function save_definition()
{
    if($this->session->userdata('user_login_access') != False) {

        // Set validation rules
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
        $this->form_validation->set_rules('kpi_name', 'KPI Name', 'required|trim|max_length[255]');
        // kpi_category: use your department values! 
        // $this->form_validation->set_rules('kpi_category', 'KPI Category', 'required|in_list[HRD,Marketing,Finance,IT,Operasional]');
        $this->form_validation->set_rules('kpi_level', 'KPI Level', 'required|in_list[Individu,Organisasi,Perusahaan]');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('target_value', 'Target', 'required|numeric');
        $this->form_validation->set_rules('weight', 'Weight', 'required|numeric');

        $kpi_id = $this->input->post('kpi_id');

        if ($this->form_validation->run() == FALSE) {
            // If you use AJAX, return errors
            echo validation_errors();
        } else {
            $data = array(
                'kpi_name'     => $this->input->post('kpi_name'),
                'kpi_category' => $this->input->post('kpi_category'),
                'kpi_level'    => $this->input->post('kpi_level'),
                'description'  => $this->input->post('description'),
                'target_value' => $this->input->post('target_value'),
                'weight'       => $this->input->post('weight'),
                'status'       => 'active'
            );

            if ($kpi_id) {
                // Edit mode
                if ($this->Kpis_model->update_kpi($kpi_id, $data)) {
                    $this->session->set_flashdata('msg', 'KPI successfully updated!');
                } else {
                    $this->session->set_flashdata('msg', 'Failed to update KPI definition.');
                }
            } else {
                // Add mode
                if ($this->Kpis_model->add_kpi($data)) {
                    $this->session->set_flashdata('msg', 'KPI successfully added!');
                } else {
                    $this->session->set_flashdata('msg', 'Failed to add KPI definition.');
                }
            }
            redirect('kpi/definitions');
        }
    } else {
        redirect(base_url(), 'refresh');
    }
}


    // Fungsi untuk menampilkan form edit KPI
    public function edit_definition() {
        if ($this->input->post()) {
            $kpi_id = $this->input->post('kpi_id');
            $data = array(
                'kpi_name'     => $this->input->post('kpi_name'),
                'kpi_category' => $this->input->post('kpi_category'),
                'kpi_level'    => $this->input->post('kpi_level'),
                'target_value' => $this->input->post('target_value'),
                'weight'       => $this->input->post('weight'),
                'description'  => $this->input->post('description'),
                'status'       => $this->input->post('status')
            );
            $this->Kpis_model->update_kpi($kpi_id, $data);
            $this->session->set_flashdata('msg', 'KPI successfully updated!');
        }
        redirect('kpi/definitions');
    }
    
    // Handle delete POST
    public function delete_definition() {
        if ($this->input->post()) {
            $kpi_id = $this->input->post('kpi_id');
            $this->Kpis_model->delete_kpi($kpi_id);
            $this->session->set_flashdata('msg', 'KPI successfully deleted!');
        }
        redirect('kpi/definitions');
    }
    

    // --- Placeholder untuk fungsi-fungsi modul KPI lainnya ---

    // Fungsi untuk mengelola Periode Evaluasi
    public function periods()
    {
        if($this->session->userdata('user_login_access') != False) {
            $data['title'] = 'Evaluation Periods';
            $data['page_title'] = 'Evaluation Periods';
            // Logic untuk mengambil dan menampilkan daftar periode evaluasi
            // Nanti akan memanggil model baru: Evaluation_periods_model
            // Karena view ini akan menjadi halaman penuh, memuat header/sidebar/footer secara manual

            $this->load->view('kpi/evaluation_periods_list', $data); // Akan dibuat nanti

        } else {
            redirect(base_url() , 'refresh');
        }
    }

    // Fungsi untuk mengelola KPI Karyawan (Target & Achievement)
    public function employee_kpis()
    {
        if($this->session->userdata('user_login_access') != False) {
            $data['title'] = 'Employee KPI Management';
            $data['page_title'] = 'Employee KPI Management';
            // Logic untuk menampilkan form filter karyawan dan periode,
            // serta tabel target dan achievement KPI per karyawan.
            // Nanti akan memanggil model: Kpi_targets_model, Kpi_achievements_model, Employee_model
            // Karena view ini akan menjadi halaman penuh, memuat header/sidebar/footer secara manual

            $this->load->view('kpi/employee_kpi_management', $data); // Akan dibuat nanti

        } else {
            redirect(base_url() , 'refresh');
        }
    }

    
}
