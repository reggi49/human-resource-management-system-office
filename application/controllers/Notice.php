 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends CI_Controller {


    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('notice_model');
        $this->load->model('settings_model');
        $this->load->model('leave_model');
        check_admin_access();
    }
    
	public function index()
	{
		#Redirect to Admin dashboard after authentication
        if ($this->session->userdata('user_login_access') == 1)
            redirect('dashboard/Dashboard');
            $data=array();
            #$data['settingsvalue'] = $this->dashboard_model->GetSettingsValue();
			$this->load->view('login');
	}
    public function All_notice(){
        if($this->session->userdata('user_login_access') != False) {
            $data['notice'] = $this->db->query("
                SELECT n.*,
                       (SELECT COUNT(*) FROM notice_signatures WHERE notice_id = n.id) AS total_signed,
                       (SELECT COUNT(*) FROM employee WHERE status = 'ACTIVE') AS total_employees
                FROM notice n
                ORDER BY n.id DESC
            ")->result();
            $this->load->view('backend/notice', $data);
        } else {
            redirect(base_url(), 'refresh');
        }        
    }

    public function Published_Notice(){
        if($this->session->userdata('user_login_access') != False) {    
            $filetitle = $this->input->post('title');    		
            $description = $this->input->post('description');    		
            $ndate = $this->input->post('nodate');    		
            $is_mandatory = $this->input->post('is_mandatory') ? 1 : 0;

            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters();
            $this->form_validation->set_rules('title', 'title', 'trim|required|min_length[5]|max_length[255]|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => validation_errors()]);
                } else {
                    $this->session->set_flashdata('error', validation_errors());
                    redirect('notice/All_notice');
                }
            } else {
                $img_url = '';
                if(!empty($_FILES['file_url']['name'])){
                    $file_name = $_FILES['file_url']['name'];
                    $config = array(
                        'file_name' => time().'_'.$file_name,
                        'upload_path' => "./assets/images/notice",
                        'allowed_types' => "gif|jpg|png|jpeg|webp|pdf|doc|docx",
                        'overwrite' => False,
                        'max_size' => "50720000"
                    );
            
                    $this->load->library('Upload', $config);
                    $this->upload->initialize($config);                
                    if (!$this->upload->do_upload('file_url')) {
                        if ($this->input->is_ajax_request()) {
                            echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors()]);
                        } else {
                            $this->session->set_flashdata('error', $this->upload->display_errors());
                            redirect('notice/All_notice');
                        }
                        return;
                    } else {
                        $path = $this->upload->data();
                        $img_url = $path['file_name'];
                    }
                }

                $data = array(
                    'title' => $filetitle,
                    'description' => $description,
                    'file_url' => $img_url,
                    'date' => $ndate,
                    'is_mandatory' => $is_mandatory
                );

                $this->notice_model->Published_Notice($data); 
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'success', 'message' => 'Pengumuman berhasil dipublikasikan']);
                } else {
                    $this->session->set_flashdata('feedback', 'Pengumuman / Surat Edaran berhasil dipublikasikan');
                    redirect('notice/All_notice');
                }
            }
        } else {
            redirect(base_url(), 'refresh');
        }        
    }

    public function get_signatures_ajax($notice_id) {
        if($this->session->userdata('user_login_access') == False) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $notice = $this->db->get_where('notice', array('id' => $notice_id))->row();
        if(!$notice) {
            echo json_encode(['status' => 'error', 'message' => 'Notice tidak ditemukan']);
            return;
        }

        // Get all active employees with their signature status for this notice
        $employees = $this->db->query("
            SELECT e.em_id, e.em_code, CONCAT(e.first_name, ' ', IFNULL(e.last_name, '')) AS full_name,
                   e.em_image, e.em_phone,
                   d.dep_name, des.des_name,
                   ns.signed_at, ns.ip_address, ns.status AS sign_status
            FROM employee e
            LEFT JOIN department d ON e.dep_id = d.id
            LEFT JOIN designation des ON e.des_id = des.id
            LEFT JOIN notice_signatures ns ON ns.notice_id = ? AND (ns.em_id = e.em_id OR ns.em_id = e.em_code)
            WHERE e.status = 'ACTIVE'
            ORDER BY (CASE WHEN ns.status = 'SIGNED' THEN 1 ELSE 2 END) ASC, ns.signed_at DESC, e.first_name ASC
        ", array($notice_id))->result();

        $total_all = count($employees);
        $total_signed = 0;
        foreach($employees as $emp) {
            if ($emp->sign_status === 'SIGNED') $total_signed++;
        }
        $total_unsigned = $total_all - $total_signed;

        echo json_encode([
            'status' => 'success',
            'notice' => $notice,
            'total_all' => $total_all,
            'total_signed' => $total_signed,
            'total_unsigned' => $total_unsigned,
            'employees' => $employees
        ]);
    }
}