<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recruitment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_jobs() {
        $this->db->order_by('id', 'DESC');
        return $this->db->get('job_postings')->result();
    }

    public function get_job($id) {
        return $this->db->get_where('job_postings', array('id' => $id))->row();
    }

    public function insert_job($data) {
        $this->db->insert('job_postings', $data);
        return $this->db->insert_id();
    }

    public function update_job($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('job_postings', $data);
    }

    public function delete_job($id) {
        return $this->db->delete('job_postings', array('id' => $id));
    }

    public function get_candidates_by_job($job_id) {
        $this->db->where('job_id', $job_id);
        $this->db->order_by('match_score', 'DESC');
        return $this->db->get('applicants')->result();
    }

    public function insert_applicant($data) {
        $this->db->insert('applicants', $data);
        return $this->db->insert_id();
    }

    public function update_applicant($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('applicants', $data);
    }

    public function count_candidates($job_id) {
        $this->db->where('job_id', $job_id);
        return $this->db->count_all_results('applicants');
    }
}
