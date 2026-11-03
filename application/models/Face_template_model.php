<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Template Model — CRUD for employee_face_templates
 * 
 * Each employee has up to 5 templates (one per pose).
 * Templates are stored SEPARATELY — NEVER averaged across poses.
 */
class Face_template_model extends CI_Model {

    private $table = 'employee_face_templates';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Insert or update a single pose template for an employee.
     */
    public function upsert_template($em_code, $pose, $embedding, $model_version, $det_score = null, $blur = null, $brightness = null) {
        if (!is_array($embedding) || count($embedding) !== 512) {
            throw new RuntimeException("Embedding must be 512-D array, got " . (is_array($embedding) ? count($embedding) : 'non-array'));
        }

        $now = date('Y-m-d H:i:s');

        // Check if exists
        $existing = $this->db->get_where($this->table, [
            'em_code'       => $em_code,
            'pose'          => $pose,
            'model_version' => $model_version,
        ])->row();

        $data = [
            'embedding'     => json_encode($embedding),
            'model_version' => $model_version,
            'det_score'     => $det_score,
            'blur'          => $blur,
            'brightness'    => $brightness,
            'is_active'     => 0, // Inactive until finalized
            'updated_at'    => $now,
        ];

        if ($existing) {
            $this->db->where('id', $existing->id)->update($this->table, $data);
            return $existing->id;
        } else {
            $data['em_code']    = $em_code;
            $data['pose']       = $pose;
            $data['created_at'] = $now;
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Activate all 5 templates for an employee in one transaction.
     * Returns false if not all 5 poses are present.
     */
    public function finalize_enrollment($em_code, $model_version) {
        $required_poses = ['front', 'up', 'down', 'left', 'right'];
        
        $templates = $this->db->get_where($this->table, [
            'em_code'       => $em_code,
            'model_version' => $model_version,
        ])->result();

        $existing_poses = [];
        foreach ($templates as $t) {
            $existing_poses[] = $t->pose;
        }

        $missing = array_diff($required_poses, $existing_poses);
        if (!empty($missing)) {
            return ['success' => false, 'missing_poses' => array_values($missing)];
        }

        // Deactivate any old model_version templates
        $this->db->where('em_code', $em_code)
                  ->where('model_version !=', $model_version)
                  ->update($this->table, ['is_active' => 0]);

        // Activate all 5 templates for current model version
        $this->db->where('em_code', $em_code)
                  ->where('model_version', $model_version)
                  ->update($this->table, ['is_active' => 1]);

        return ['success' => true, 'activated' => count($templates)];
    }

    /**
     * Get all active templates for a specific employee (for 1:1 matching).
     */
    public function get_employee_templates($em_code, $model_version) {
        $rows = $this->db->get_where($this->table, [
            'em_code'       => $em_code,
            'model_version' => $model_version,
            'is_active'     => 1,
        ])->result();

        $templates = [];
        foreach ($rows as $row) {
            $vec = json_decode($row->embedding, true);
            if (!is_array($vec) || count($vec) !== 512) {
                throw new RuntimeException("Database error: Template untuk karyawan {$em_code} berdimensi " . (is_array($vec) ? count($vec) : 'invalid') . ", diharapkan 512-D.");
            }
            $templates[] = $vec;
        }
        return $templates;
    }

    /**
     * Get all active templates grouped by em_code (for 1:N matching).
     * Returns: [em_code => [template1, template2, ...], ...]
     * Throws RuntimeException if ANY template in database has invalid dimension.
     */
    public function get_all_active_templates($model_version) {
        $rows = $this->db->select('em_code, embedding')
                          ->where('is_active', 1)
                          ->where('model_version', $model_version)
                          ->get($this->table)
                          ->result();

        $gallery = [];
        foreach ($rows as $row) {
            $vec = json_decode($row->embedding, true);
            if (!is_array($vec) || count($vec) !== 512) {
                throw new RuntimeException("Database error: Template karyawan {$row->em_code} berdimensi " . (is_array($vec) ? count($vec) : 'invalid') . ", diharapkan 512-D. Gagalkan matching.");
            }
            if (!isset($gallery[$row->em_code])) {
                $gallery[$row->em_code] = [];
            }
            $gallery[$row->em_code][] = $vec;
        }
        return $gallery;
    }

    /**
     * Check enrollment status for an employee.
     */
    public function get_enrollment_status($em_code, $model_version) {
        $rows = $this->db->get_where($this->table, [
            'em_code'       => $em_code,
            'model_version' => $model_version,
        ])->result();

        $poses = [];
        $active_count = 0;
        foreach ($rows as $r) {
            $poses[] = $r->pose;
            if ($r->is_active) $active_count++;
        }

        return [
            'enrolled_poses' => $poses,
            'total_poses'    => count($poses),
            'active_count'   => $active_count,
            'is_complete'    => (count($poses) >= 5 && $active_count >= 5),
        ];
    }

    /**
     * Delete all templates for an employee (for re-enrollment or data deletion).
     */
    public function delete_employee_templates($em_code) {
        $this->db->where('em_code', $em_code)->delete($this->table);
    }

    /**
     * Count enrolled employees.
     */
    public function count_enrolled($model_version) {
        return $this->db->select('COUNT(DISTINCT em_code) as cnt')
                         ->where('is_active', 1)
                         ->where('model_version', $model_version)
                         ->get($this->table)
                         ->row()
                         ->cnt;
    }
}
