<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Nonce Library — Single-use nonce with 15-second TTL
 * 
 * Prevents replay attacks and ensures each verification attempt is unique.
 */
class Face_nonce {

    private $CI;
    private $ttl;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('face', TRUE);
        $this->ttl = $this->CI->config->item('nonce_ttl', 'face') ?: 15;
    }

    /**
     * Generate a new nonce with random challenge.
     * 
     * @param string $purpose 'enroll' or 'verify'
     * @param string|null $em_code Employee code (null for 1:N identification)
     * @return array ['nonce' => string, 'challenge' => string, 'expires_at' => string]
     */
    public function generate($purpose = 'verify', $em_code = null) {
        // Clean expired nonces first (housekeeping)
        $this->cleanup();

        $nonce = bin2hex(random_bytes(16)); // 32 chars
        $challenge = bin2hex(random_bytes(16)); // 32 chars
        $now = date('Y-m-d H:i:s');
        $expires = date('Y-m-d H:i:s', time() + $this->ttl);

        $this->CI->db->insert('face_nonce', [
            'nonce'      => $nonce,
            'em_code'    => $em_code,
            'purpose'    => $purpose,
            'challenge'  => $challenge,
            'used'       => 0,
            'expires_at' => $expires,
            'created_at' => $now,
        ]);

        return [
            'nonce'      => $nonce,
            'challenge'  => $challenge,
            'expires_at' => $expires,
        ];
    }

    /**
     * Validate and consume a nonce (single-use).
     * 
     * @param string $nonce
     * @param string $purpose 'enroll' or 'verify'
     * @return array ['valid' => bool, 'reason' => string|null, 'em_code' => string|null, 'challenge' => string|null]
     */
    public function validate($nonce, $purpose = 'verify') {
        if (empty($nonce) || strlen($nonce) !== 32) {
            return ['valid' => false, 'reason' => 'invalid_nonce_format', 'em_code' => null, 'challenge' => null];
        }

        $now = date('Y-m-d H:i:s');

        // ATOMIC UPDATE to consume nonce: only succeeds if used = 0, expires_at >= NOW(), and purpose matches.
        // Prevents race conditions from concurrent parallel requests.
        $this->CI->db->query("
            UPDATE face_nonce 
            SET used = 1 
            WHERE nonce = ? 
              AND used = 0 
              AND expires_at >= ? 
              AND purpose = ?
        ", [$nonce, $now, $purpose]);

        if ($this->CI->db->affected_rows() === 1) {
            // Successfully consumed atomically
            $row = $this->CI->db->get_where('face_nonce', ['nonce' => $nonce])->row();
            return [
                'valid'     => true,
                'reason'    => null,
                'em_code'   => $row ? $row->em_code : null,
                'challenge' => $row ? $row->challenge : null,
            ];
        }

        // If affected_rows === 0, investigate reason for audit logging
        $row = $this->CI->db->get_where('face_nonce', ['nonce' => $nonce])->row();
        if (!$row) {
            return ['valid' => false, 'reason' => 'nonce_not_found', 'em_code' => null, 'challenge' => null];
        }
        if ($row->used) {
            return ['valid' => false, 'reason' => 'rejected_nonce', 'em_code' => $row->em_code, 'challenge' => $row->challenge];
        }
        if (strtotime($row->expires_at) < time()) {
            return ['valid' => false, 'reason' => 'nonce_expired', 'em_code' => $row->em_code, 'challenge' => $row->challenge];
        }
        if ($row->purpose !== $purpose) {
            return ['valid' => false, 'reason' => 'nonce_purpose_mismatch', 'em_code' => $row->em_code, 'challenge' => $row->challenge];
        }

        return ['valid' => false, 'reason' => 'rejected_nonce', 'em_code' => null, 'challenge' => null];
    }

    /**
     * Clean up expired nonces (older than 1 hour to keep audit trail briefly)
     */
    public function cleanup() {
        $cutoff = date('Y-m-d H:i:s', time() - 3600);
        $this->CI->db->where('expires_at <', $cutoff)->delete('face_nonce');
    }
}
