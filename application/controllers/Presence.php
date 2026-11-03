<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presence extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->db->query("SET time_zone = '+07:00'");
        $this->load->model('settings_model');
    }

    public function index() {
        redirect('face');
    }
}



