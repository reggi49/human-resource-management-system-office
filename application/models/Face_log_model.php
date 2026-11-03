<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Log Model — Audit trail for EVERY face recognition attempt
 * 
 * Records both successful and failed attempts with scores, 
 * runner-up candidates, thresholds, and latency.
 */
class Face_log_model extends CI_Model {

    private $table = 'face_recognition_log';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Log a face recognition attempt.
     * 
     * @param array $data All log fields
     * @return int Insert ID
     */
    public function log_attempt($data) {
        $defaults = [
            'em_code'         => null,
            'runner_up_code'  => null,
            'purpose'         => 'verify',
            'attendance_type' => null,
            'decision'        => 'service_error',
            'best_score'      => null,
            'second_score'    => null,
            'score_margin'    => null,
            'liveness_score'  => null,
            'matched_pose'    => null,
            'det_score'       => null,
            'blur'            => null,
            'yaw'             => null,
            'pitch'           => null,
            'roll'            => null,
            'model_version'   => null,
            'threshold_used'  => null,
            'margin_used'     => null,
            'latency_ms'      => null,
            'device_id'       => null,
            'ip'              => null,
            'user_agent'      => null,
            'lat'             => null,
            'lng'             => null,
            'snapshot_path'   => null,
            'created_at'      => date('Y-m-d H:i:s'), // SERVER time
        ];

        $record = array_merge($defaults, $data);
        $this->db->insert($this->table, $record);
        return $this->db->insert_id();
    }

    /**
     * Get recent logs for admin dashboard.
     */
    public function get_recent_logs($limit = 50, $em_code = null, $decision = null) {
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);

        if ($em_code) {
            $this->db->where('em_code', $em_code);
        }
        if ($decision) {
            $this->db->where('decision', $decision);
        }

        return $this->db->get($this->table)->result();
    }

    /**
     * Get failure rate per employee (for bias detection).
     */
    public function get_failure_rates($days = 30) {
        $since = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $sql = "SELECT 
                    em_code,
                    COUNT(*) as total_attempts,
                    SUM(CASE WHEN decision = 'accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN decision != 'accepted' THEN 1 ELSE 0 END) as rejected,
                    ROUND(SUM(CASE WHEN decision != 'accepted' THEN 1 ELSE 0 END) / COUNT(*) * 100, 1) as fail_rate_pct
                FROM {$this->table}
                WHERE em_code IS NOT NULL 
                  AND created_at >= ?
                  AND purpose = 'verify'
                GROUP BY em_code
                HAVING total_attempts >= 3
                ORDER BY fail_rate_pct DESC";

        return $this->db->query($sql, [$since])->result();
    }

    /**
     * Get confusion pairs (employees frequently mistaken for each other).
     */
    public function get_confusion_pairs($days = 30) {
        $since = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $sql = "SELECT 
                    em_code, runner_up_code, 
                    COUNT(*) as confusion_count,
                    AVG(score_margin) as avg_margin
                FROM {$this->table}
                WHERE decision = 'rejected_ambiguous'
                  AND em_code IS NOT NULL 
                  AND runner_up_code IS NOT NULL
                  AND created_at >= ?
                GROUP BY em_code, runner_up_code
                ORDER BY confusion_count DESC
                LIMIT 20";

        return $this->db->query($sql, [$since])->result();
    }
}
