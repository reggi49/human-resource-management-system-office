 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {

    /**
     * Index Page for this controller.
     *$individual_info
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
        function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('login_model');
        $this->load->model('dashboard_model'); 
        $this->load->model('employee_model'); 
        $this->load->model('leave_model'); 
        $this->load->model('payroll_model');
        $this->load->model('settings_model');    
        $this->load->model('organization_model');    
        $this->load->model('loan_model');
        $this->load->model('attendance_model');    
        
        // RBAC Access Guard
        check_admin_access();
        check_permission('payroll.view');
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
    public function Salary_Type(){
        if($this->session->userdata('user_login_access') != False) { 
        $data['typevalue'] = $this->payroll_model->GetsalaryType();
        $this->load->view('backend/salary_type',$data);
        }
        else{
            redirect(base_url() , 'refresh');
        }        
    }
   /* public function Salary_List(){
        if($this->session->userdata('user_login_access') != False) { 
        
        $data['salaryvalue'] = $this->payroll_model->GetsalaryValueEm();

        $this->load->view('backend/salary_list',$data);
        }
        else{
            redirect(base_url() , 'refresh');
        }        
    }*/
    public function Add_Sallary_Type(){
        if($this->session->userdata('user_login_access') != False) {
        $id = $this->input->post('id');
        $type = $this->input->post('typename');
        $createdate = $this->input->post('createdate');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('typename', 'Type name', 'trim|required|min_length[3]|max_length[120]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            } else {
            $data = array();
            $data = array(
                    'salary_type' => $type,
                    'create_date' => $createdate
                );
            if(empty($id)){
                $success = $this->payroll_model->Add_typeInfo($data);
                #redirect("leave/Holidays");
                #$this->session->set_flashdata('feedback','Successfully Added');
                echo "Successfully Added";
            } else {
                $success = $this->payroll_model->Update_typeInfo($id,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                #redirect("leave/Holidays");
                echo "Successfully Updated";
            }
                       
        }
        }
    else{
        redirect(base_url() , 'refresh');
    }            
    }
    public function GetSallaryTypeById(){
        if($this->session->userdata('user_login_access') != False) {  
        $id = $this->input->get('id');      
        $data['typevalueid'] = $this->payroll_model->Get_typeValue($id);
        echo json_encode($data);    
        }
    else{
        redirect(base_url() , 'refresh');
    }        
    }
    public function GetSallaryById(){
        if($this->session->userdata('user_login_access') != False) {  
        $id = $this->input->get('id');
        $data=array();    
        // $data['salaryvaluebyid'] = $this->payroll_model->Get_Salary_Value($id);
        // $data['salarypayvaluebyid'] = $this->payroll_model->Get_Salarypay_Value($id);
        $data['salaryvalue'] = $this->payroll_model->GetsalaryValueByID($id);
        $data['loanvaluebyid'] = $this->payroll_model->GetLoanValueByID($id);
        echo json_encode($data);
        }
    else{
        redirect(base_url() , 'refresh');
    }        
    }
    public function Generate_salary(){
    if($this->session->userdata('user_login_access') != False) {    
    $data['typevalue'] = $this->payroll_model->GetsalaryType();   
    $data['employee'] = $this->employee_model->emselect();    
    $data['salaryvalue'] = $this->payroll_model->GetAllSalary();
    $data['department'] = $this->organization_model->depselect();    
    $data['settingsvalue'] = $this->settings_model->GetSettingsValue();
    $this->load->view('backend/salary_view',$data);
        }
    else{
        redirect(base_url() , 'refresh');
    }  

    }

    // Generates the salary
    public function Add_Sallary_Pay(){
        if($this->session->userdata('user_login_access') != False) {
            $id = $this->input->post('id');
            $emid = $this->input->post('emid');
            $month = $this->input->post('month');
            $basic = $this->input->post('basic');
            $totalday = $this->input->post('month_work_hours');
            $totalday = $this->input->post('hours_worked');
            $loan = $this->input->post('loan');
            $loanid = $this->input->post('loan_id');
            $total = $this->input->post('total_paid');
            $paydate = $this->input->post('paydate');
            $status = $this->input->post('status');
            $paid_type = $this->input->post('paid_type');

            $this->form_validation->set_error_delimiters();
            $this->form_validation->set_rules('emid', 'Employee Id', 'trim|required');
            $this->form_validation->set_rules('basic', 'Employee Basic', 'trim|required|min_length[2]|max_length[7]|xss_clean');

            if ($this->form_validation->run() == FALSE) {

                    echo validation_errors();

                } else {
                
                $data = array();
                $data = array(
                        'emp_id' => $emid,
                        'month' => $month,
                        'paid_date' => $paydate,
                        'total_days' => $totalday,
                        'basic' => $basic,
                        'loan' => $loan,
                        'total_pay' => $total,
                        'status' => $status,
                        'paid_type' => $paid_type
                    );
                if(empty($id)){
                    $success = $this->payroll_model->insert_Salary_Pay($data);
                   if(empty($loanid)){
                        #$loaninfo = $this->payroll_model->GetloanInfo($emid);
                        echo "Successfully Added";
                    } else {
                        $loanvalue = $this->loan_model->GetLoanValuebyLId($loanid);              
                        #$loaninfo = $this->payroll_model->GetloanInfo($emid);
                        if(!empty($loanvalue)){
                    $period = $loanvalue->install_period - 1;
                    $number = $loanvalue->loan_number;
                    $data = array();
                    $data = array(
                        'emp_id' => $emid,
                        'loan_id' => $loanid,
                        'loan_number' => $number,
                        'install_amount' => $loan,
                        /*'pay_amount' => $payment,*/
                        'app_date' => $paydate,
                        /*'receiver' => $receiver,*/
                        'install_no' => $period
                        /*'notes' => $notes*/
                    );                         
                $success = $this->loan_model->Add_installData($data);
                $totalpay = $loanvalue->total_pay + $loan;
                $totaldue = $loanvalue->amount - $totalpay;
                 /*$period = $loanvalue->install_period - 1;*/
                    if($period == '1'){
                        $status = 'Done';
                    }                        
                $data = array();
                $data = array(
                'total_pay'=>$totalpay,
                'total_due'=>$totaldue,
                'install_period'=>$period,
                'status'=>'Done'
                );
                $success = $this->loan_model->update_LoanData($loanid,$data);
                    } else {
                     echo "Successfully added But your Loan number is not available";   
                    }
                }
                echo "Successfully Added";
                } else {
                    $success = $this->payroll_model->Update_SalaryPayInfo($id,$data);
                    echo "Successfully Updated";
                }
                           
            }
        }
        else{
            redirect(base_url() , 'refresh');
        }
    }

    // From Salary List - Not Sure
    public function Add_Salary(){
        if($this->session->userdata('user_login_access') != False) { 
        $sid = $this->input->post('sid');
        $aid = $this->input->post('aid');
        $did = $this->input->post('did');
        $em_id = $this->input->post('emid');
        /*$type = $this->input->post('typeid');*/
        $basic = $this->input->post('basic');
        $medical = $this->input->post('medical');
        $houserent = $this->input->post('houserent');
        $bonus = $this->input->post('bonus');
        $provident = $this->input->post('provident');
        $bima = $this->input->post('bima');
        $tax = $this->input->post('tax');
        $others = $this->input->post('others');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('basic', 'basic', 'trim|required|min_length[3]|max_length[10]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            #redirect("employee/view?I=" .base64_encode($em_id));
            } else {
            $data = array();
                $data = array(
                    'emp_id' => $em_id,
                    /*'type_id' => $type,*/
                    'basic' => $basic
                );
            if(!empty($sid)){
                $success = $this->employee_model->Update_Salary($sid,$data);
                #$this->session->set_flashdata('feedback','Successfully Updated');
                #echo "Successfully Updated";
                #$success = $this->employee_model->Add_Salary($data);
                $insertId = $this->db->insert_id();
                #$this->session->set_flashdata('feedback','Successfully Added');
                #echo "Successfully Added";
                $data1 = array();
                $data1 = array(
                    'salary_id' => $sid,
                    'medical' => $medical,
                    'house_rent' => $houserent,
                    'bonus' => $bonus
                );
                $success = $this->employee_model->Update_Addition($aid,$data1);
                $data2 = array();
                $data2 = array(
                    'salary_id' => $sid,
                    'provident_fund' => $provident,
                    'bima' => $bima,
                    'tax' => $tax,
                    'others' => $others
                );
                $success = $this->employee_model->Update_Deduction($did,$data2); 
                echo "Successfully Updated";                
            } else {
                $success = $this->employee_model->Add_Salary($data);
                $insertId = $this->db->insert_id();
                #$this->session->set_flashdata('feedback','Successfully Added');
                #echo "Successfully Added";
                $data1 = array();
                $data1 = array(
                    'salary_id' => $insertId,
                    'medical' => $medical,
                    'house_rent' => $houserent,
                    'bonus' => $bonus
                );
                $success = $this->employee_model->Add_Addition($data1);
                $data2 = array();
                $data2 = array(
                    'salary_id' => $insertId,
                    'provident_fund' => $provident,
                    'bima' => $bima,
                    'tax' => $tax,
                    'others' => $others
                );
                $success = $this->employee_model->Add_Deduction($data2); 
                echo "Successfully Added";
            }           
        }
        }
    else{
        redirect(base_url() , 'refresh');
    }        
    } 
    public function Get_PayrollDetails(){
        $depid = $this->input->get('dep_id');
        $dateval = $this->input->get('date_time');
        
        $orderdate = !empty($dateval) ? explode('-', $dateval) : array();
        $month = isset($orderdate[0]) && !empty($orderdate[0]) ? $orderdate[0] : date('m');
        $year = isset($orderdate[1]) && !empty($orderdate[1]) ? $orderdate[1] : date('Y');
        
        $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $holiday = $this->payroll_model->GetHolidayByYear($dateval);
        $totalday = 0;        
       foreach($holiday as $value){
            #$start = date_create($value->from_date); 
            #$end = date_create($value->to_date);
            
            $days = $value->number_of_days;
           #$inday = $days->format("%a");
           #$total = array_sum($inday);
           
            $totalday = $totalday + $days;         
        }
        $totalholiday = $totalday;
        $m = date('m');
        $y = date('Y');
        function getDays($y,$m){ 
            $allday = cal_days_in_month(CAL_GREGORIAN,$m,$y);
            $wed = array();
            for($i = 1; $i<= $allday; $i++){
                $daye  = date('Y-m-'.$i);
                $result = date("D", strtotime($daye));
                if($result == "Fri"){  
                    $fri[] = date("Y-m-d", strtotime($daye)). " ".$result."<br>";
                }  
            }
            return  count($fri);
        }
        $fri = getDays($y, $m);
        $totalweekend = $fri;
        $holidays = $totalholiday + $totalweekend;    
        $monthday = $day - $holidays;
        

        $totalmonthhour = $monthday * 8;
        $totalmonthhour;
        $employee = $this->payroll_model->GetDepEmployee($depid);

        foreach($employee as $value){
            $hourrate = $value->total/$totalmonthhour;
            echo "<tr>
                    <td>$value->em_code</td>
                    <td>$value->first_name</td>
                    <td>$value->total</td>
                    <td>$hourrate</td>
                    <td>$totalmonthhour</td>
                    <td><a href='javascript:void(0)' data-id='$value->em_id' class='btn btn-sm btn-info waves-effect waves-light salaryGenerateModal' data-toggle='modal' data-target='#generatePayrollModal' data-hour='$totalmonthhour'>Generate Salary</a></td>
                </tr>";
        }
        
    }

    // Original one commented out above
    public function Salary_List(){

        if($this->session->userdata('user_login_access') != False) { 
            
        $data['salary_info'] = $this->payroll_model->getAllSalaryData();

        $this->load->view('backend/salary_list', $data);

        }

        else {

            redirect(base_url() , 'refresh');
        }        
    }

    // Start Invoice
    public function Invoice(){
        if($this->session->userdata('user_login_access') != False) { 
        /*$data['typevalue'] = $this->payroll_model->GetsalaryType();*/ 
        $id                         = $this->input->get('Id');   
        $eid                         = $this->input->get('em');   
        $data2                      = array();

        $data['salary_info'] = $this->payroll_model->getAllSalaryDataById($id);

        $data['employee_info']      = $this->payroll_model->getEmployeeID($eid);
        $data['salaryvaluebyid']    = $this->payroll_model->Get_Salary_Value($eid); // 24
        $data['salarypaybyid']      = $this->payroll_model->Get_SalaryID($eid);
        $data['salaryvalue']        = $this->payroll_model->GetsalaryValueByID($eid); // 25000
        $data['loanvaluebyid']      = $this->payroll_model->GetLoanValueByID($eid);
        $data['settingsvalue']      = $this->settings_model->GetSettingsValue();

        $salary_val_id = (!empty($data['salaryvalue']) && !empty($data['salaryvalue']->id)) ? $data['salaryvalue']->id : 0;
        $data['addition']  = $this->payroll_model->getAdditionDataBySalaryID($salary_val_id);
        $data['diduction'] = $this->payroll_model->getDiductionDataBySalaryID($salary_val_id);

        $data['otherInfo'] = $this->payroll_model->getOtherInfo($eid);
        $data['bankinfo']  = $this->payroll_model->GetBankInfo($eid);

        //Count Add/Did
        $month_init = (!empty($data['salary_info']) && !empty($data['salary_info']->month)) ? $data['salary_info']->month : date('F');
        $month = date("n", strtotime($month_init));
        $year  = (!empty($data['salary_info']) && !empty($data['salary_info']->year)) ? $data['salary_info']->year : date('Y');
        $id_em = (!empty($data['employee_info']) && !empty($data['employee_info']->em_id)) ? $data['employee_info']->em_id : $eid;

        $data['id_em']=$id_em;
        $data['month']=$month;

        if ($month<10){
            $month = '0' . $month;
        }

        $employeePIN = $this->getPinFromID($id_em);

        // Count Friday
        $fridays = $this->count_friday($month, $year);

        $month_holiday_count = $this->payroll_model->getNumberOfHolidays($month, $year);

        // Total holidays and friday count
        $holidays_cnt = (!empty($month_holiday_count) && !empty($month_holiday_count->total_days)) ? $month_holiday_count->total_days : 0;
        $total_days_off = $fridays + $holidays_cnt;

        // Total days in the month
        $total_days_in_the_month = $this->total_days_in_a_month($month, $year);
        $total_work_days = $total_days_in_the_month - $total_days_off;
        $total_work_hours = $total_work_days * 8;

        //Format date for hours count in the hours_worked_by_employee() function
        $start_date = $year . '-' . $month . '-' . 1;
        $end_date = $year . '-' . $month . '-' . $total_days_in_the_month;

        // Employee actually worked
        $em_code_pin = (!empty($employeePIN) && !empty($employeePIN->em_code)) ? $employeePIN->em_code : '';
        $employee_actually_worked = $this->hours_worked_by_employee($em_code_pin, $start_date, $end_date);  // in hours

        //Get his monthly salary
        $employee_salary_obj = $this->payroll_model->GetsalaryValueByID($id_em);
        $employee_salary = (!empty($employee_salary_obj) && !empty($employee_salary_obj->total)) ? $employee_salary_obj->total : 0;

        // Hourly rate for the month
        $hourly_rate = ($total_work_hours > 0) ? ($employee_salary / $total_work_hours) : 0;

        $actual_hrs = (!empty($employee_actually_worked) && !empty($employee_actually_worked[0]) && isset($employee_actually_worked[0]->Hours)) ? $employee_actually_worked[0]->Hours : 0;
        $work_hour_diff = abs($total_work_hours) - abs($actual_hrs);

        $data['work_h_diff'] = $work_hour_diff;
        $addition = 0;
        $diduction = 0;
        if($work_hour_diff < 0) {
            $addition = abs($work_hour_diff) * $hourly_rate;
        } else if($work_hour_diff > 0) {
            $diduction = abs($work_hour_diff) * $hourly_rate;
        }
        // Loan
        $loan_amount = $this->payroll_model->GetLoanValueByID($id_em);
        if($loan_amount) {

            $loan_amount = $loan_amount->installment;
        }
         // Sending 
        
        $data['a'] = $addition;
        $data['d'] = $data['salary_info']->diduction;
        
        $this->load->view('backend/invoice',$data);
        }
        else {
            redirect(base_url() , 'refresh');
        }        
    }

     // Start Invoice / Payslip Report
    public function load_employee_Invoice_by_EmId_for_pay(){
        if($this->session->userdata('user_login_access') != False) {  
            $eid       = $this->input->get('emid');
            $dateval   = $this->input->get('date_time');

            $orderdate = !empty($dateval) ? explode('-', $dateval) : array();
            $month_num = isset($orderdate[0]) && !empty($orderdate[0]) ? $orderdate[0] : date('m');
            $year      = isset($orderdate[1]) && !empty($orderdate[1]) ? $orderdate[1] : date('Y');
            $month     = $this->month_number_to_name($month_num);

            $salary_info   = $this->payroll_model->getAllSalaryDataByMonthYearEm($eid, $month, $year);

            if(empty($salary_info)){
                echo "<div class='alert alert-warning text-center font-weight-bold p-20 m-t-20' style='border-radius:12px;'>
                        <i class='fa fa-exclamation-triangle m-r-5'></i> Slip gaji belum diproses/disimpan untuk karyawan ini pada periode <strong>$month $year</strong>.<br>
                        <small class='font-weight-normal text-muted'>Silakan lakukan kalkulasi dan simpan slip di menu <strong>Generate Payslip</strong> terlebih dahulu.</small>
                      </div>";
                return;
            }

            $employee_info = $this->payroll_model->getEmployeeID($eid);
            $otherInfo     = $this->payroll_model->getOtherInfo($eid);
            $bankinfo      = $this->payroll_model->GetBankInfo($eid);
            $settingsvalue = $this->settings_model->GetSettingsValue();

            $base = base_url();
            $full_name = isset($salary_info->first_name) ? ($salary_info->first_name . ' ' . $salary_info->last_name) : (isset($employee_info->first_name) ? ($employee_info->first_name . ' ' . $employee_info->last_name) : 'Karyawan');
            $em_code = isset($employee_info->em_code) ? $employee_info->em_code : (isset($salary_info->em_code) ? $salary_info->em_code : '-');
            $dep_name = (isset($otherInfo[0]->dep_name)) ? $otherInfo[0]->dep_name : '-';
            $des_name = (isset($otherInfo[0]->name)) ? $otherInfo[0]->name : '-';
            $joining_date = isset($employee_info->em_joining_date) ? $employee_info->em_joining_date : '-';

             $basic = (float) (isset($salary_info->basic) ? $salary_info->basic : 0);
            $medical = (float) (isset($salary_info->medical) ? $salary_info->medical : 0);
            $house_rent = (float) (isset($salary_info->house_rent) ? $salary_info->house_rent : 0);
            $bonus_total = (float) (isset($salary_info->bonus) ? $salary_info->bonus : 0);
            $gaji_bruto = (float) (isset($salary_info->addition) ? $salary_info->addition : 0);

            // Compute default breakdown if legacy record only has basic & thp
            if($medical == 0) $medical = 1150000; // Uang makan 23 hari x 50k
            if($house_rent == 0) $house_rent = 400000; // Tunjangan mingguan
            $tunjangan_bulanan = 500000;
            $bonus_absensi = max(0, $bonus_total - $tunjangan_bulanan);
            if($bonus_total == 0) {
                $tunjangan_bulanan = 500000;
                $bonus_absensi = 250000;
            }

            if($gaji_bruto == 0) {
                $gaji_bruto = $basic + $medical + $house_rent + $tunjangan_bulanan + $bonus_absensi;
            }

            $bpjs_total = (float) (isset($salary_info->bima) ? $salary_info->bima : 0);
            $tax   = (float) (isset($salary_info->tax) ? $salary_info->tax : 0);
            $loan  = (float) (isset($salary_info->loan) ? $salary_info->loan : 0);
            $total_potongan = (float) (isset($salary_info->diduction) ? $salary_info->diduction : 0);
            $thp   = (float) (isset($salary_info->total_pay) ? $salary_info->total_pay : 0);

            // Detailed BPJS breakdown (with dynamic settings caps)
            $settings_val = $this->settings_model->GetSettingsValue();
            $bpjs_jp_cap = (isset($settings_val->bpjs_jp_cap) && $settings_val->bpjs_jp_cap > 0) ? (float)$settings_val->bpjs_jp_cap : 10042300;
            $bpjs_kes_cap = (isset($settings_val->bpjs_kes_cap) && $settings_val->bpjs_kes_cap > 0) ? (float)$settings_val->bpjs_kes_cap : 12000000;

            $bpjs_kes_base = min($gaji_bruto, $bpjs_kes_cap);
            $bpjs_kes = round($bpjs_kes_base * 0.01);
            $jht = round($gaji_bruto * 0.02);
            $jp_base = min($gaji_bruto, $bpjs_jp_cap);
            $jp = round($jp_base * 0.01);
            if($bpjs_total == 0) $bpjs_total = $bpjs_kes + $jht + $jp;

            if($total_potongan == 0) {
                $total_potongan = $bpjs_total + $tax + $loan;
            }

            if($thp == 0) {
                $thp = $gaji_bruto - $total_potongan;
            }

            $paydate = !empty($salary_info->paid_date) ? date('d F Y', strtotime($salary_info->paid_date)) : date('d F Y');
            $paytype = !empty($salary_info->paid_type) ? $salary_info->paid_type : 'Transfer Bank';

            echo "
            <div class='row payslip_print' id='payslip_print' style='max-width: 900px; margin: 15px auto; font-family: sans-serif;'>
                <div class='col-md-12 p-0'>
                    <div class='payslip-card shadow-sm bg-white p-4' style='border-radius: 16px; border: 1px solid #e2e8f0;'>
                        
                        <!-- Header Banner -->
                        <div class='d-flex flex-wrap justify-content-between align-items-center pb-3 mb-3 border-bottom' style='border-color: #e2e8f0 !important;'>
                            <div class='d-flex align-items-center mb-2 mb-md-0'>
                                <div class='round round-primary mr-3' style='width: 44px; height: 44px; font-size: 18px; line-height: 44px;'>
                                    <i class='fa fa-building'></i>
                                </div>
                                <div>
                                    <h4 class='font-weight-bold text-dark m-0' style='font-size: 18px; letter-spacing: -0.3px;'>HRMS INDONESIA</h4>
                                    <small class='text-muted d-block' style='font-size: 11px;'>Sistem Penggajian Resmi Karyawan</small>
                                </div>
                            </div>
                            <div class='text-md-right'>
                                <div class='p-2 px-3 rounded-lg text-left' style='background: #f8fafc; border: 1px solid #e2e8f0;'>
                                    <span class='badge badge-pill badge-success font-weight-bold float-right ml-2' style='font-size: 10px;'>PAID</span>
                                    <h6 class='font-weight-bold text-primary m-0' style='font-size: 14px;'>Periode: $month $year</h6>
                                    <small class='text-muted' style='font-size: 11px;'>Tgl Bayar: <strong>$paydate</strong></small>
                                </div>
                            </div>
                        </div>

                        <!-- Employee Info Grid -->
                        <div class='p-3 mb-4 rounded-lg' style='background-color: #f8fafc; border: 1px solid #e2e8f0; font-size: 13px;'>
                            <div class='row' style='row-gap: 8px;'>
                                <div class='col-md-3 col-6'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>PIN / NIP</small>
                                    <span class='font-weight-bold text-dark'>$em_code</span>
                                </div>
                                <div class='col-md-3 col-6'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>Nama Karyawan</small>
                                    <span class='font-weight-bold text-dark'>$full_name</span>
                                </div>
                                <div class='col-md-3 col-6'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>Departemen</small>
                                    <span class='text-dark'>$dep_name</span>
                                </div>
                                <div class='col-md-3 col-6'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>Jabatan</small>
                                    <span class='text-dark'>$des_name</span>
                                </div>
                                <div class='col-md-3 col-6 pt-2 border-top'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>Tanggal Masuk</small>
                                    <span class='text-dark'>$joining_date</span>
                                </div>
                                <div class='col-md-3 col-6 pt-2 border-top'>
                                    <small class='text-uppercase text-muted font-weight-bold d-block' style='font-size: 10px;'>Metode Bayar</small>
                                    <span class='badge badge-info' style='font-size: 10px;'>$paytype</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dual Column Breakdown: Side-by-Side Earnings & Deductions -->
                        <div class='row mb-4' style='row-gap: 16px;'>
                            <!-- LEFT COLUMN: EARNINGS -->
                            <div class='col-md-6 col-xs-6'>
                                <div class='rounded-lg h-100 p-3' style='background-color: #f0fdf4; border: 1px solid #bbf7d0;'>
                                    <div class='pb-2 mb-2 border-bottom font-weight-bold text-success' style='font-size: 12px; border-color: #bbf7d0 !important;'>
                                        <i class='fa fa-plus-circle mr-1'></i> PENERIMAAN (EARNINGS)
                                    </div>
                                    
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Gaji Pokok</span>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>Rp " . number_format($basic, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Uang Makan Harian</span>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>Rp " . number_format($medical, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Tunjangan Mingguan</span>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>Rp " . number_format($house_rent, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Tunjangan Bulanan</span>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>Rp " . number_format($tunjangan_bulanan, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Bonus Absensi</span>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>Rp " . number_format($bonus_absensi, 0, ',', '.') . "</span>
                                    </div>

                                    <div class='d-flex justify-content-between pt-2 mt-3 border-top' style='border-color: #86efac !important;'>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>TOTAL GAJI BRUTO</span>
                                        <span class='font-weight-bold text-success' style='font-size: 13px;'>Rp " . number_format($gaji_bruto, 0, ',', '.') . "</span>
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT COLUMN: DEDUCTIONS -->
                            <div class='col-md-6 col-xs-6'>
                                <div class='rounded-lg h-100 p-3' style='background-color: #fef2f2; border: 1px solid #fecaca;'>
                                    <div class='pb-2 mb-2 border-bottom font-weight-bold text-danger' style='font-size: 12px; border-color: #fecaca !important;'>
                                        <i class='fa fa-minus-circle mr-1'></i> POTONGAN KARYAWAN (DEDUCTIONS)
                                    </div>

                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>BPJS Kesehatan (1%)</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 12px;'>Rp " . number_format($bpjs_kes, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>BPJS TK JHT (2%)</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 12px;'>Rp " . number_format($jht, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>BPJS TK JP (1%)</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 12px;'>Rp " . number_format($jp, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>PPh 21 Progresif</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 12px;'>Rp " . number_format($tax, 0, ',', '.') . "</span>
                                    </div>
                                    <div class='payslip-item-line py-1'>
                                        <span class='font-weight-semibold text-dark' style='font-size: 12px;'>Angsuran Pinjaman</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 12px;'>" . ($loan > 0 ? ('Rp ' . number_format($loan, 0, ',', '.')) : '-') . "</span>
                                    </div>


                                    <div class='d-flex justify-content-between pt-2 mt-3 border-top' style='border-color: #fca5a5 !important;'>
                                        <span class='font-weight-bold text-dark' style='font-size: 12px;'>TOTAL POTONGAN</span>
                                        <span class='font-weight-bold text-danger' style='font-size: 13px;'>Rp " . number_format($total_potongan, 0, ',', '.') . "</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Take Home Pay Compact Banner -->
                        <div class='p-3 text-white mb-4 d-flex flex-wrap align-items-center justify-content-between' style='background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 12px;'>
                            <div>
                                <span class='text-uppercase tracking-wider font-weight-bold d-block' style='font-size: 10px; opacity: 0.75;'>TAKE HOME PAY (GAJI BERSIH DITERIMA)</span>
                                <small style='opacity: 0.8; font-size: 11px;'>Bruto (Rp " . number_format($gaji_bruto, 0, ',', '.') . ") − Potongan (Rp " . number_format($total_potongan, 0, ',', '.') . ")</small>
                            </div>
                            <div>
                                <h3 class='font-weight-extrabold text-white m-0' style='font-size: 24px; letter-spacing: -0.5px;'>Rp " . number_format($thp, 0, ',', '.') . "</h3>
                            </div>
                        </div>

                        <!-- Signatures Footer -->
                        <div class='pt-3 border-top text-center' style='font-size: 11px; border-color: #e2e8f0 !important;'>
                            <div class='row'>
                                <div class='col-6'>
                                    <small class='text-muted text-uppercase d-block mb-4 font-weight-bold'>Disetujui Oleh</small>
                                    <p class='font-weight-bold mb-0'>HRD / Finance</p>
                                </div>
                                <div class='col-6'>
                                    <small class='text-muted text-uppercase d-block mb-4 font-weight-bold'>Penerima (Karyawan)</small>
                                    <p class='font-weight-bold mb-0'><u>$full_name</u></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>";
        }
        else {
            redirect(base_url() , 'refresh');
        }
    }
    // End Invoice


    private function count_friday($month, $year) {
        $fridays=0;
        $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for($i=1;$i<=$total_days;$i++) {
            if(date('N',strtotime($year.'-'.$month.'-'.$i))==5) {
                $fridays++;
            }
        }
        return $fridays;
    }

    private function total_days_in_a_month($month, $year) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    // Totals hours worked by an emplyee in a month
    private function hours_worked_by_employee($employeeID, $start_date, $end_date) {
        return $this->payroll_model->totalHoursWorkedByEmployeeInAMonth($employeeID, $start_date, $end_date);
    }
    
    
    private function getPinFromID($employeeID) {
        return $this->payroll_model->getPinFromID($employeeID);
    }

    /*GET WORKHOURS OF ANY MONTH - */
    /*||||| Method has not been used anywhere |||||*/
    public function GetSalaryByWorkdays(){

        if($this->session->userdata('user_login_access') != False) {  

        // Get the month and year
        $monthName = $this->input->get('monthName');
        $employeeID = $this->input->get('employeeID');
        $year = date("Y");

        // Count Friday
        $fridays = $this->count_friday($monthName, $year);


        $month_holiday_count = $this->payroll_model->getNumberOfHolidays($monthName, $year);

        // Total holidays and friday count
        $total_days_off = $fridays + $month_holiday_count->total_days;

        // Total days in the month
        $total_days_in_the_month = $this->total_days_in_a_month($monthName, $year);

        $total_work_days = $total_days_in_the_month - $total_days_off;

        $total_work_hours = $total_work_days * 8;

        //Format date for hours count in the hours_worked_by_employee() function
        $start_date = $year . '-' . $monthName . '-' . 1;
        $end_date = $total_days_in_the_month . '-' . $monthName . '-' . $total_days_in_the_month;

        // Employee actually worked
        $employee_actually_worked = $this->hours_worked_by_employee($employeeID, $start_date, $end_date);  // in hours

        //Get his monthly salary
        $employee_salary = $this->payroll_model->GetsalaryValueByID($employeeID);
        if($employee_salary) {
            $employee_salary = $employee_salary->total;
        }

        // Hourly rate for the month
        $hourly_rate = $employee_salary / $total_work_hours;

        $work_hour_diff = abs($total_work_hours) - abs($employee_actually_worked[0]->Hours); // 96 - 16 = 80

        $addition = 0;
        $diduction = 0;
        if($work_hour_diff < 0) {
            $addition = abs($work_hour_diff) * $hourly_rate;
        } else if($work_hour_diff > 0) {
            // 80 is > 0 which means he worked less, so diduction = 80 hrs
            // so 80 * hourly rate 208 taka = 17500
            $diduction = abs($work_hour_diff) * $hourly_rate;
        }

        // Loan
        $loan_amount = $this->payroll_model->GetLoanValueByID($employeeID);
        if($loan_amount) {
            $loan_amount = $loan_amount->installment;
        }

        // Sending 
        $data = array();
        $data['basic_salary'] = $employee_salary;
        $data['total_work_hours'] = $total_work_hours;
        $data['employee_actually_worked'] = $employee_actually_worked[0]->Hours;
        $data['addition'] = $addition;
        $data['diduction'] = $diduction;
        $data['loan'] = $loan_amount;
        echo json_encode($data);
        }
        else{
            redirect(base_url() , 'refresh');
        }        
    }

    public function month_number_to_name($month) {
        $dateObj   = DateTime::createFromFormat('!m', $month);
        return $dateObj->format('F'); // March
    }

    public function get_full_name($first_name, $last_name) {
        return $first_name . ' ' . $last_name;
    }

    // Add or update the salary record
    public function pay_salary_add_record() {
        if($this->session->userdata('user_login_access') != False) {
        $emid = $this->input->post('emid');
        $month_num = $this->input->post('month');
        $month = !empty($month_num) ? $this->month_number_to_name($month_num) : date('F');
        $basic = (float) $this->input->post('gaji_pokok');
        if(empty($basic)) $basic = (float) $this->input->post('basic');
        $year = $this->input->post('year');

        $uang_makan = (float) $this->input->post('uang_makan');
        $tunjangan_mingguan = (float) $this->input->post('tunjangan_mingguan');
        $tunjangan_bulanan = (float) $this->input->post('tunjangan_bulanan');
        $bonus_absensi = (float) $this->input->post('bonus_absensi');
        $gaji_bruto = (float) $this->input->post('gaji_bruto');

        $bpjs_total = (float) $this->input->post('total_bpjs_karyawan');
        $pph21 = (float) $this->input->post('pph21_sebulan');
        $loan = (float) $this->input->post('loan_amount');
        if(empty($loan)) $loan = (float) $this->input->post('loan');
        $loan_id = $this->input->post('loan_id');
        $total_potongan = (float) $this->input->post('total_potongan');
        $total_paid = (float) $this->input->post('thp');
        if(empty($total_paid)) $total_paid = (float) $this->input->post('total_paid');

        $paydate = $this->input->post('paydate');
        $status = $this->input->post('status');
        $paid_type = $this->input->post('paid_type');
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters();
        $this->form_validation->set_rules('emid', 'Employee ID', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

            $data = array();
            $data = array(
                'emp_id'    => $emid,
                'month'     => $month,
                'year'      => $year,
                'paid_date' => $paydate,
                'basic'     => $basic,
                'medical'   => $uang_makan,
                'house_rent'=> $tunjangan_mingguan,
                'bonus'     => ($tunjangan_bulanan + $bonus_absensi),
                'addition'  => $gaji_bruto,
                'bima'      => $bpjs_total,
                'tax'       => $pph21,
                'loan'      => $loan,
                'diduction' => $total_potongan,
                'total_pay' => $total_paid,
                'status'    => $status,
                'paid_type' => $paid_type,
            );
            
            // See if record exists
            $get_salary_record = $this->payroll_model->getSalaryRecord($emid, $month,$year);

            if($get_salary_record) {
                $payID = $get_salary_record[0]->pay_id;
                $payment_status = $get_salary_record[0]->status;
            }

            // If exists, add/edit
            if( isset($payID) && $payID > 0 ) {

                if($payment_status == "Paid") {

                    echo "Has already been paid";

                } else {

                    $success = $this->payroll_model->updatePaidSalaryData($payID, $data);

                    // Do the loan update
                    if($success && $status == "Paid") {
                        $loan_info = $this->loan_model->GetLoanValuebyLId($loan_id);

                        // loan_id and loan fields already grabbed
                        if (!empty($loan_info)) {

                            $period = $loan_info->install_period - 1;
                            $number = $loan_info->loan_number;
                            $data = array();
                            $data = array(
                                'emp_id' => $emid,
                                'loan_id' => $loan_id,
                                'loan_number' => $number,
                                'install_amount' => $loan,
                                /*'pay_amount' => $payment,*/
                                'app_date' => $paydate,
                                /*'receiver' => $receiver,*/
                                'install_no' => $period
                                /*'notes' => $notes*/
                            );

                            $success_installment = $this->loan_model->Add_installData($data);

                            $totalpay = $loan_info->total_pay + $loan;
                            $totaldue = $loan_info->amount - $totalpay;
                            $period = $loan_info->install_period - 1;
                            $loan_status = $loan_info->status;

                            if ($period == '1') {
                                $loan_status = 'Done';
                            }

                            $data = array();
                            $data = array(
                                'total_pay'         => $totalpay,
                                'total_due'         => $totaldue,
                                'install_period'    => $period,
                                'status'            => $loan_status
                            );

                            $success_loan = $this->loan_model->update_LoanData($loan_id, $data);
                        }
                    }

                    echo "Successfully added";

                }

            } else {
                $success = $this->payroll_model->insertPaidSalaryData($data);

                // Do the loan update
                if($success && $status == "Paid") {

                    // Input Status
                        $loan_info = $this->loan_model->GetLoanValuebyLId($loan_id);
                        
                        // loan_id and loan fields already grabbed
                        if (!empty($loan_info)) {

                            $period = $loan_info->install_period - 1;
                            $number = $loan_info->loan_number;
                            $data = array();
                            $data = array(
                                'emp_id' => $emid,
                                'loan_id' => $loan_id,
                                'loan_number' => $number,
                                'install_amount' => $loan,
                                /*'pay_amount' => $payment,*/
                                'app_date' => $paydate,
                                /*'receiver' => $receiver,*/
                                'install_no' => $period
                                /*'notes' => $notes*/
                            );

                            $success_installment = $this->loan_model->Add_installData($data);

                            $totalpay = $loan_info->total_pay + $loan;
                            $totaldue = $loan_info->amount - $totalpay;
                            $period = $loan_info->install_period - 1;
                            $loan_status = $loan_info->status;

                            if ($period == '0') {
                                $loan_status = 'Done';
                            }

                            $data = array();
                            $data = array(
                                'total_pay'         => $totalpay,
                                'total_due'         => $totaldue,
                                'install_period'    => $period,
                                'status'            => $loan_status
                            );

                            $success_loan = $this->loan_model->update_LoanData($loan_id, $data);
                        }

                    echo "Successfully added";
                }
            }
        }
    }
    else {
            redirect(base_url() , 'refresh');
        }        
    }

    // Generate the list of employees by dept. to generate their payments
    public function load_employee_by_deptID_for_pay(){

        if($this->session->userdata('user_login_access') != False) {  

        // Get the month and year
        $date_time = $this->input->get('date_time');
        $dep_id = $this->input->get('dep_id');

        $parts = !empty($date_time) ? explode('-', $date_time) : array();
        $month = isset($parts[0]) && !empty($parts[0]) ? $parts[0] : date('m');
        $year = isset($parts[1]) && !empty($parts[1]) ? $parts[1] : date('Y');

        $employees = $this->payroll_model->GetDepEmployee($dep_id);

        if(empty($employees)){
            echo "<tr><td colspan='4' class='text-center text-muted'>Tidak ada karyawan di departemen ini.</td></tr>";
            return;
        }

        foreach($employees as $employee){
            $full_name = $this->get_full_name($employee->first_name, $employee->last_name);
            $gaji_pokok = !empty($employee->total) ? $employee->total : 0;
            $gaji_formatted = 'Rp ' . number_format($gaji_pokok, 0, ',', '.');

            if($gaji_pokok == 0) {
                $gaji_formatted = '<span class="text-danger font-weight-bold">Belum diatur</span>';
            }

            echo "<tr>
                    <td>{$employee->em_code}</td>
                    <td>{$full_name}</td>
                    <td>{$gaji_formatted}</td>
                    <td>
                        <a href='javascript:void(0)'
                            data-id='{$employee->em_id}' 
                            data-month='{$month}' 
                            data-year='{$year}' 
                            class='btn btn-sm btn-success waves-effect waves-light salaryGenerateModal font-weight-bold' 
                            data-toggle='modal'
                            data-target='#generatePayrollModal'>
                            <i class='fa fa-calculator m-r-5'></i> Hitung Gaji
                        </a>
                    </td>
                </tr>";
        }

        }
        else{
            redirect(base_url() , 'refresh');
        }        
    }

    public function generate_payroll_for_each_employee(){

        if($this->session->userdata('user_login_access') != False) {  
        
        // Get inputs
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        $employeeID = $this->input->get('employeeID');
        $ptkp_status = $this->input->get('ptkp_status');
        if(empty($ptkp_status)) $ptkp_status = 'TK0';

        // PTKP values (per tahun) — UU HPP 2022+
        $ptkp_map = array(
            'TK0' => 54000000,
            'K0'  => 58500000,
            'K1'  => 63000000,
            'K2'  => 67500000,
            'K3'  => 72000000,
        );
        $ptkp_tahunan = isset($ptkp_map[$ptkp_status]) ? $ptkp_map[$ptkp_status] : 54000000;

        // Get employee PIN (em_code)
        $employeePIN = $this->getPinFromID($employeeID);
        if(!$employeePIN) {
            echo json_encode(array('error' => 'Karyawan tidak ditemukan'));
            return;
        }

        // Get basic salary (gaji pokok)
        $salary_data = $this->payroll_model->GetsalaryValueByID($employeeID);
        $gaji_pokok = 0;
        if($salary_data && !empty($salary_data->total)) {
            $gaji_pokok = (float) $salary_data->total;
        }

        if($gaji_pokok <= 0) {
            echo json_encode(array('error' => 'Gaji pokok belum diatur untuk karyawan ini. Silakan atur di menu Employee > Salary.'));
            return;
        }

        // Calculate work days
        $total_days_in_month = (int) date('t', strtotime("{$year}-{$month}-01"));
        
        // Count weekends (Sat & Sun)
        $weekends = 0;
        for($d = 1; $d <= $total_days_in_month; $d++){
            $dow = date('w', strtotime("{$year}-{$month}-" . sprintf('%02d', $d)));
            if($dow == 0 || $dow == 6) $weekends++;
        }

        // Holidays from DB
        $month_holiday_count = $this->payroll_model->getNumberOfHolidays($month, $year);
        $holidays = ($month_holiday_count && $month_holiday_count->total_days) ? (int) $month_holiday_count->total_days : 0;

        $total_work_days = $total_days_in_month - $weekends - $holidays;
        if($total_work_days < 0) $total_work_days = 0;

        // Employee attendance from DB
        $start_date = sprintf("%s-%s-01", $year, str_pad($month, 2, '0', STR_PAD_LEFT));
        $end_date = sprintf("%s-%s-%s", $year, str_pad($month, 2, '0', STR_PAD_LEFT), str_pad($total_days_in_month, 2, '0', STR_PAD_LEFT));
        
        $days_attended = $this->attendance_model->getAttendanceDaysCount($employeePIN->em_code, $start_date, $end_date);
        if(!$days_attended) $days_attended = 0;

        // ===== PENDAPATAN (EARNINGS) =====

        // 1. Uang Makan Harian (Rp 50.000 / hari hadir)
        $uang_makan_per_hari = 50000;
        $uang_makan = $days_attended * $uang_makan_per_hari;

        // 2. Tunjangan Mingguan (Rp 100.000 x 4 = Rp 400.000/bulan)
        $tunjangan_mingguan = 400000;

        // 3. Tunjangan Bulanan (Rp 500.000/bulan)
        $tunjangan_bulanan = 500000;

        // 4. Bonus Absensi (Rp 250.000 jika hadir penuh)
        $bonus_absensi = ($days_attended >= $total_work_days && $total_work_days > 0) ? 250000 : 0;

        // 5. GAJI BRUTO BULANAN
        $gaji_bruto = $gaji_pokok + $uang_makan + $tunjangan_mingguan + $tunjangan_bulanan + $bonus_absensi;

        // ===== POTONGAN BPJS (KARYAWAN & PERUSAHAAN) =====
        $settings_val = $this->settings_model->GetSettingsValue();
        $bpjs_jp_cap = (isset($settings_val->bpjs_jp_cap) && $settings_val->bpjs_jp_cap > 0) ? (float)$settings_val->bpjs_jp_cap : 10042300;
        $bpjs_kes_cap = (isset($settings_val->bpjs_kes_cap) && $settings_val->bpjs_kes_cap > 0) ? (float)$settings_val->bpjs_kes_cap : 12000000;

        // BPJS Kesehatan — 1% Karyawan + 4% Perusahaan
        $bpjs_kes_base = min($gaji_bruto, $bpjs_kes_cap);
        $bpjs_kes_karyawan = round($bpjs_kes_base * 0.01);
        $bpjs_kes_perusahaan = round($bpjs_kes_base * 0.04);

        // JHT — Karyawan 2% + Perusahaan 3.7%
        $jht_karyawan = round($gaji_bruto * 0.02);
        $jht_perusahaan = round($gaji_bruto * 0.037);

        // JP — Jaminan Pensiun (Jika Bruto > Cap maka pakai Cap, jika <= Cap maka dikalikan Bruto)
        $jp_base = min($gaji_bruto, $bpjs_jp_cap);
        $jp_karyawan = round($jp_base * 0.01);
        $jp_perusahaan = round($jp_base * 0.02);

        // JKK & JKM (ditanggung perusahaan, tidak dipotong dari karyawan)
        $jkk_perusahaan = round($gaji_pokok * 0.0024);
        $jkm_perusahaan = round($gaji_pokok * 0.003);

        $total_bpjs_karyawan = $bpjs_kes_karyawan + $jht_karyawan + $jp_karyawan;
        $total_bpjs_perusahaan = $bpjs_kes_perusahaan + $jht_perusahaan + $jp_perusahaan + $jkk_perusahaan + $jkm_perusahaan;

        // ===== PERHITUNGAN PPh 21 TER (PP 58 Tahun 2023 / PMK 168 Tahun 2023) =====
        $ter_info = $this->payroll_model->hitung_pph21_ter($gaji_bruto, $ptkp_status);
        $ter_category = $ter_info['category'];
        $ter_rate = $ter_info['rate'];
        $ter_rate_percent = $ter_info['rate_percent'];
        $pph21_ter_sebulan = $ter_info['pph21_sebulan'];

        // ===== PERHITUNGAN PPh 21 PROGRESIF (UU HPP No. 7/2021) UNTUK DESEMBER / PEMBANDING =====
        $biaya_jabatan = min(round($gaji_bruto * 0.05), 500000);
        $neto_sebulan = $gaji_bruto - $biaya_jabatan - $jht_karyawan - $jp_karyawan;
        if($neto_sebulan < 0) $neto_sebulan = 0;
        $neto_setahun = $neto_sebulan * 12;
        $pkp = $neto_setahun - $ptkp_tahunan;
        if($pkp < 0) $pkp = 0;
        $pph21_setahun = $this->hitung_pph21_progresif($pkp);
        $pph21_progresif_sebulan = round($pph21_setahun / 12);

        // Gunakan TER PPh 21 sebulan sebagai standar potongan bulanan (PP 58/2023)
        $pph21_sebulan = $pph21_ter_sebulan;

        // ===== PINJAMAN (LOAN) =====
        $loan_data = $this->payroll_model->GetLoanValueByID($employeeID);
        $loan_amount = 0;
        $loan_id = 0;
        if($loan_data && isset($loan_data->installment)) {
            $loan_amount = (float) $loan_data->installment;
            $loan_id = isset($loan_data->id) ? $loan_data->id : 0;
        }

        // ===== TOTAL POTONGAN & THP =====
        $total_potongan = $bpjs_kes_karyawan + $jht_karyawan + $jp_karyawan + $pph21_sebulan + $loan_amount;
        $thp = $gaji_bruto - $total_potongan;

        // ===== RESPONSE =====
        $data = array(
            'gaji_pokok'            => $gaji_pokok,
            'uang_makan'            => $uang_makan,
            'uang_makan_per_hari'   => $uang_makan_per_hari,
            'tunjangan_mingguan'    => $tunjangan_mingguan,
            'tunjangan_bulanan'     => $tunjangan_bulanan,
            'bonus_absensi'         => $bonus_absensi,
            'gaji_bruto'            => $gaji_bruto,

            'bpjs_kes_karyawan'     => $bpjs_kes_karyawan,
            'bpjs_kes_perusahaan'   => $bpjs_kes_perusahaan,
            'jht_karyawan'          => $jht_karyawan,
            'jht_perusahaan'        => $jht_perusahaan,
            'jp_karyawan'           => $jp_karyawan,
            'jp_perusahaan'         => $jp_perusahaan,
            'jkk_perusahaan'        => $jkk_perusahaan,
            'jkm_perusahaan'        => $jkm_perusahaan,
            'total_bpjs_karyawan'   => $total_bpjs_karyawan,
            'total_bpjs_perusahaan' => $total_bpjs_perusahaan,

            'biaya_jabatan'         => $biaya_jabatan,
            'neto_sebulan'          => $neto_sebulan,
            'neto_setahun'          => $neto_setahun,
            'ptkp_status'           => $ptkp_status,
            'ptkp_tahunan'          => $ptkp_tahunan,
            'pkp'                   => $pkp,
            'pph21_setahun'         => $pph21_setahun,
            'pph21_sebulan'         => $pph21_sebulan,
            'ter_category'          => $ter_category,
            'ter_rate'              => $ter_rate,
            'ter_rate_percent'      => $ter_rate_percent,
            'pph21_ter_sebulan'     => $pph21_ter_sebulan,
            'pph21_progresif_sebulan'=> $pph21_progresif_sebulan,

            'loan_amount'           => $loan_amount,
            'loan_id'               => $loan_id,

            'total_potongan'        => $total_potongan,
            'thp'                   => $thp,

            'days_attended'         => $days_attended,
            'total_work_days'       => $total_work_days,
            'total_days_in_month'   => $total_days_in_month,
            'month'                 => $month,
            'year'                  => $year,
        );
        
        echo json_encode($data);
        }
        else{
            redirect(base_url() , 'refresh');
        }      
    }

    /**
     * Hitung PPh 21 Progresif per tahun (UU HPP No. 7 Tahun 2021)
     * Lapisan 1: s/d 60 jt => 5%
     * Lapisan 2: 60 jt - 250 jt => 15%
     * Lapisan 3: 250 jt - 500 jt => 25%
     * Lapisan 4: 500 jt - 5 M => 30%
     * Lapisan 5: > 5 M => 35%
     */
    private function hitung_pph21_progresif($pkp) {
        if($pkp <= 0) return 0;

        $tax = 0;
        $brackets = array(
            array('limit' => 60000000,    'rate' => 0.05),
            array('limit' => 250000000,   'rate' => 0.15),
            array('limit' => 500000000,   'rate' => 0.25),
            array('limit' => 5000000000,  'rate' => 0.30),
            array('limit' => PHP_INT_MAX, 'rate' => 0.35),
        );

        $prev = 0;
        foreach($brackets as $bracket) {
            if($pkp <= 0) break;
            $taxable = min($pkp, $bracket['limit'] - $prev);
            $tax += $taxable * $bracket['rate'];
            $pkp -= $taxable;
            $prev = $bracket['limit'];
        }

        return round($tax);
    }

    private function get_nominal_ptkp_val($ptkp) {
        $ptkp = strtoupper(trim($ptkp));
        $map = array(
            'TK0' => 54000000,
            'TK1' => 58500000, 'K0' => 58500000,
            'TK2' => 63000000, 'K1' => 63000000,
            'TK3' => 67500000, 'K2' => 67500000,
            'K3'  => 72000000,
            'KI0' => 108000000,
            'KI1' => 112500000,
            'KI2' => 117000000,
            'KI3' => 121500000
        );
        return isset($map[$ptkp]) ? $map[$ptkp] : 54000000;
    }

    public function generate_dummy_attendance(){
        if($this->session->userdata('user_login_access') != False) {
            $month = $this->input->post('month');
            $year = $this->input->post('year');

            if(empty($month) || empty($year)) {
                $month = date('m');
                $year = date('Y');
            }

            $employees = $this->employee_model->emselect();
            $monthFormatted = sprintf("%02d", $month);
            $daysInMonth = date('t', strtotime("{$year}-{$monthFormatted}-01"));

            $insertedCount = 0;
            foreach ($employees as $emp) {
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $dayStr = sprintf("%02d", $d);
                    $dateStr = "{$year}-{$monthFormatted}-{$dayStr}";
                    
                    // Skip weekends
                    $dayOfWeek = date('w', strtotime($dateStr));
                    if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                        continue;
                    }

                    $existing = $this->attendance_model->getDuplicateVal($emp->em_code, $dateStr);
                    if (!$existing) {
                        $data = array(
                            'emp_id' => $emp->em_code,
                            'atten_date' => $dateStr,
                            'signin_time' => '08:00:00',
                            'signout_time' => '17:00:00',
                            'working_hour' => '480 min',
                            'place' => 'office',
                            'absence' => '0 min',
                            'overtime' => '0 min',
                            'status' => 'A'
                        );
                        $this->attendance_model->Add_AttendanceData($data);
                        $insertedCount++;
                    }
                }
            }
            echo json_encode(array('status' => 'success', 'message' => "Berhasil generate $insertedCount data absensi dummy untuk periode $monthFormatted-$year!"));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Unauthorized access'));
        }
    }
    public function Payslip_Report(){
        if($this->session->userdata('user_login_access') != False) {  
        $data=array();    
        $data['employee'] = $this->employee_model->emselect();
        $this->load->view('backend/salary_report',$data);
        }
    else{
        redirect(base_url() , 'refresh');
    }        
    }

    /**
     * Master & Referensi PPh 21 (PP 58/2023 TER & UU HPP No. 7/2021)
     */
    public function Pph21_Master() {
        if ($this->session->userdata('user_login_access') != False) {
            $data = array();
            
            // 1. Data Tarif TER PPh 21
            $data['ter_rates'] = $this->db->order_by('category', 'ASC')
                                          ->order_by('tier', 'ASC')
                                          ->get('ter_pph21')
                                          ->result();

            // 2. Data Referensi PTKP
            $data['ptkp_list'] = array(
                array('code' => 'TK/0', 'category' => 'A', 'amount' => 54000000, 'desc' => 'Tidak Kawin, 0 Tanggungan'),
                array('code' => 'TK/1', 'category' => 'A', 'amount' => 58500000, 'desc' => 'Tidak Kawin, 1 Tanggungan'),
                array('code' => 'TK/2', 'category' => 'B', 'amount' => 63000000, 'desc' => 'Tidak Kawin, 2 Tanggungan'),
                array('code' => 'TK/3', 'category' => 'B', 'amount' => 67500000, 'desc' => 'Tidak Kawin, 3 Tanggungan'),
                array('code' => 'K/0',  'category' => 'A', 'amount' => 58500000, 'desc' => 'Kawin, 0 Tanggungan'),
                array('code' => 'K/1',  'category' => 'B', 'amount' => 63000000, 'desc' => 'Kawin, 1 Tanggungan'),
                array('code' => 'K/2',  'category' => 'B', 'amount' => 67500000, 'desc' => 'Kawin, 2 Tanggungan'),
                array('code' => 'K/3',  'category' => 'C', 'amount' => 72000000, 'desc' => 'Kawin, 3 Tanggungan'),
            );

            // 3. Data Referensi Tarif Progresif Pasal 17 UU HPP
            $data['progresif_list'] = array(
                array('tier' => 'Lapisan I',   'range' => 's/d Rp 60.000.000',                     'rate' => '5%'),
                array('tier' => 'Lapisan II',  'range' => '> Rp 60.000.000 s/d Rp 250.000.000',    'rate' => '15%'),
                array('tier' => 'Lapisan III', 'range' => '> Rp 250.000.000 s/d Rp 500.000.000',   'rate' => '25%'),
                array('tier' => 'Lapisan IV',  'range' => '> Rp 500.000.000 s/d Rp 5.000.000.000', 'rate' => '30%'),
                array('tier' => 'Lapisan V',   'range' => '> Rp 5.000.000.000',                    'rate' => '35%'),
            );

            // 4. Data Rekap PPh 21 Karyawan (HRD Audit Overview)
            if (!$this->db->field_exists('em_npwp', 'employee')) {
                $this->db->query("ALTER TABLE `employee` ADD `em_npwp` VARCHAR(64) NULL DEFAULT NULL");
            }
            if (!$this->db->field_exists('em_ptkp', 'employee')) {
                $this->db->query("ALTER TABLE `employee` ADD `em_ptkp` VARCHAR(10) NOT NULL DEFAULT 'TK0'");
            }

            $sql = "SELECT e.em_id, e.em_code, e.first_name, e.last_name, 
                           COALESCE(e.em_npwp, '') AS em_npwp, 
                           COALESCE(e.em_marital_status, '') AS em_marital_status,
                           COALESCE(e.em_ptkp, 'TK0') AS ptkp_status, 
                           d.dep_name, des.des_name AS des_name,
                           s.total AS total_salary, a.basic AS basic_salary
                    FROM employee e
                    LEFT JOIN department d ON e.dep_id = d.id
                    LEFT JOIN designation des ON e.des_id = des.id
                    LEFT JOIN emp_salary s ON e.em_id = s.emp_id
                    LEFT JOIN addition a ON s.id = a.salary_id
                    WHERE e.status = 'ACTIVE'
                    ORDER BY e.first_name ASC";
            $employees = $this->db->query($sql)->result();

            $settings_val = $this->settings_model->GetSettingsValue();
            $bpjs_jp_cap = (isset($settings_val->bpjs_jp_cap) && $settings_val->bpjs_jp_cap > 0) ? (float)$settings_val->bpjs_jp_cap : 10042300;

            $rekap = array();
            foreach ($employees as $emp) {
                $ptkp = !empty($emp->ptkp_status) ? $emp->ptkp_status : 'TK0';
                $gaji_bruto = !empty($emp->total_salary) ? (float)$emp->total_salary : (!empty($emp->basic_salary) ? (float)$emp->basic_salary : 0);
                
                $ter_info = $this->payroll_model->hitung_pph21_ter($gaji_bruto, $ptkp);

                // Perhitungan Pajak Pertahun (Biaya Jabatan, Neto, PKP, PPh 21 Progresif)
                $biaya_jabatan = min(round($gaji_bruto * 0.05), 500000);
                $jht_karyawan = round($gaji_bruto * 0.02);
                $jp_base = min($gaji_bruto, $bpjs_jp_cap);
                $jp_karyawan = round($jp_base * 0.01);

                $neto_sebulan = max(0, $gaji_bruto - $biaya_jabatan - $jht_karyawan - $jp_karyawan);
                $neto_setahun = $neto_sebulan * 12;
                $ptkp_tahunan = $this->get_nominal_ptkp_val($ptkp);
                $pkp = max(0, $neto_setahun - $ptkp_tahunan);

                $pph21_setahun_progresif = $this->hitung_pph21_progresif($pkp);
                $pph21_sebulan_progresif = round($pph21_setahun_progresif / 12);
                
                $rekap[] = array(
                    'em_id'                   => $emp->em_id,
                    'em_code'                 => $emp->em_code,
                    'name'                    => trim($emp->first_name . ' ' . $emp->last_name),
                    'department'              => !empty($emp->dep_name) ? $emp->dep_name : '-',
                    'designation'             => !empty($emp->des_name) ? $emp->des_name : '-',
                    'npwp'                    => !empty($emp->em_npwp) ? $emp->em_npwp : 'Belum Ada',
                    'ptkp_status'             => $ptkp,
                    'ptkp_tahunan'            => $ptkp_tahunan,
                    'gaji_bruto'              => $gaji_bruto,
                    'biaya_jabatan'           => $biaya_jabatan,
                    'neto_sebulan'            => $neto_sebulan,
                    'neto_setahun'            => $neto_setahun,
                    'pkp'                     => $pkp,
                    'ter_category'            => $ter_info['category'],
                    'ter_rate'                => $ter_info['rate_percent'],
                    'pph21_sebulan'           => $ter_info['pph21_sebulan'],
                    'pph21_setahun_progresif' => $pph21_setahun_progresif,
                    'pph21_sebulan_progresif' => $pph21_sebulan_progresif
                );
            }
            $data['employee_rekap'] = $rekap;

            $this->load->view('backend/pph21_master', $data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }

}