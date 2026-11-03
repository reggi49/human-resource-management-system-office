<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluation_model extends CI_Model {

    private $table = 'evaluation_periods';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all evaluation periods
     * @return array
     */
    public function get_all_periods()
    {
        $this->db->order_by('created_at', 'desc');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Add a new evaluation period
     * @param array $data
     * @return bool
     */
    public function add_period($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update an evaluation period
     * @param int $period_id
     * @param array $data
     * @return bool
     */
    public function update_period($period_id, $data)
    {
        $this->db->where('period_id', $period_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete an evaluation period
     * @param int $period_id
     * @return bool
     */
    public function delete_period($period_id)
    {
        $this->db->where('period_id', $period_id);
        return $this->db->delete($this->table);
    }

    /**
     * Get single evaluation period by ID
     * @param int $period_id
     * @return object|null
     */
    public function get_period_by_id($period_id)
    {
        $query = $this->db->get_where($this->table, array('period_id' => $period_id));
        return $query->row();
    }
}
