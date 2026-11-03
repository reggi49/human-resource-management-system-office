<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');  
        $this->load->model('dashboard_model');
        $this->load->model('employee_model');
        $this->load->model('loan_model');
        $this->load->model('settings_model');
        $this->load->model('leave_model');
        $this->load->model('attendance_model');
        $this->load->model('project_model');
        $this->load->library('csvimport');
        $this->load->model('Evaluation_model');
   
    
        check_admin_access();
    }

    public function periods() {
        $data['periods'] = $this->Evaluation_model->get_all_periods();

        // Tambahan penting agar header.php tidak error
        $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
        $id = $this->session->userdata('user_login_id');
        $data['basicinfo'] = $this->employee_model->GetBasic($id);
        $date = date("m/d/y");
        $data['leavetoday'] = $this->leave_model->GetLeaveToday($date);

        $this->load->view('backend/evaluation_list', $data);
    }

    public function save_period() {
        $this->form_validation->set_rules('period_name', 'Period Name', 'required|trim');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[active,inactive]');

        $period_id = $this->input->post('period_id');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('msg', validation_errors());
        } else {
            $data = array(
                'period_name' => $this->input->post('period_name'),
                'start_date'  => $this->input->post('start_date'),
                'end_date'    => $this->input->post('end_date'),
                'description' => $this->input->post('description'),
                'status'      => $this->input->post('status')
            );

            if ($period_id) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->Evaluation_model->update_period($period_id, $data);
                $this->session->set_flashdata('msg', 'Evaluation period updated successfully.');
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->Evaluation_model->add_period($data);
                $this->session->set_flashdata('msg', 'Evaluation period added successfully.');
            }
        }
        redirect('evaluation/periods');
    }

    public function delete_period() {
        $id = $this->input->post('period_id');
        if ($this->Evaluation_model->delete_period($id)) {
            $this->session->set_flashdata('msg', 'Evaluation period deleted successfully.');
        } else {
            $this->session->set_flashdata('msg', 'Failed to delete evaluation period.');
        }
        redirect('evaluation/periods');
    }
}
