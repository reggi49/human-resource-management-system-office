<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Service Library — cURL wrapper to Python sidecar
 * 
 * Handles HMAC-SHA256 signature generation and sidecar communication.
 * If sidecar is unavailable, returns service_error — NO FALLBACK.
 */
class Face_service {

    private $CI;
    private $service_url;
    private $secret;
    private $timeout;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('face', TRUE);
        
        $this->service_url = $this->CI->config->item('face_service_url', 'face');
        $this->secret      = $this->CI->config->item('face_secret', 'face');
        $this->timeout     = $this->CI->config->item('face_service_timeout', 'face') ?: 25;

        if (empty($this->secret)) {
            log_message('error', 'Face_service: FACE_SECRET is not configured. All requests will fail.');
        }
    }

    /**
     * Check sidecar health
     * @return array|false
     */
    public function health() {
        $url = rtrim($this->service_url, '/') . '/health';
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $http_code !== 200) {
            log_message('error', "Face_service health check failed: {$error} (HTTP {$http_code})");
            return false;
        }

        return json_decode($response, true);
    }

    /**
     * Send image to sidecar for analysis
     * 
     * @param string $image_b64 Base64-encoded image (with or without data URI prefix)
     * @param string $mode      'enroll' or 'verify'
     * @param string|null $expected_pose 'front','up','down','left','right'
     * @param array|null  $frames Optional array of 3 base64 frames for multi-frame liveness verification
     * @return array  Always returns array with 'ok' key. On sidecar failure: ok=false, reason=service_error
     */
    public function analyze($image_b64, $mode = 'verify', $expected_pose = null, $frames = null) {
        if (empty($this->secret)) {
            return [
                'ok'     => false,
                'reason' => 'service_error',
                'detail' => 'FACE_SECRET not configured on server'
            ];
        }

        $body_array = [
            'image_b64'     => $image_b64,
            'mode'          => $mode,
        ];
        if ($expected_pose !== null) {
            $body_array['expected_pose'] = $expected_pose;
        }
        if (!empty($frames) && is_array($frames)) {
            $body_array['frames'] = array_values($frames);
            if (empty($image_b64) && isset($frames[2])) {
                $body_array['image_b64'] = $frames[2];
            }
        }

        $body = json_encode($body_array);
        $timestamp = (string)time();

        // HMAC = hmac_sha256(FACE_SECRET, timestamp + body)
        $message = $timestamp . $body;
        $signature = hash_hmac('sha256', $message, $this->secret);

        $url = rtrim($this->service_url, '/') . '/analyze';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Signature: ' . $signature,
                'X-Timestamp: ' . $timestamp,
            ],
        ]);

        $start = microtime(true);
        $response = curl_exec($ch);
        $elapsed_ms = (int)((microtime(true) - $start) * 1000);
        
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        // Sidecar unreachable — FAIL CLOSED, no fallback
        if ($curl_error) {
            log_message('error', "Face_service analyze failed: {$curl_error}");
            return [
                'ok'         => false,
                'reason'     => 'service_error',
                'detail'     => 'Sidecar unreachable: ' . $curl_error,
                'latency_ms' => $elapsed_ms,
            ];
        }

        // Auth/system errors from sidecar
        if ($http_code >= 400) {
            $decoded = json_decode($response, true) ?: [];
            log_message('error', "Face_service HTTP {$http_code}: " . json_encode($decoded));
            return [
                'ok'         => false,
                'reason'     => ($http_code === 401) ? 'auth_error' : 'service_error',
                'detail'     => isset($decoded['reason']) ? $decoded['reason'] : "HTTP {$http_code}",
                'latency_ms' => $elapsed_ms,
            ];
        }

        $result = json_decode($response, true);
        if (!is_array($result)) {
            log_message('error', "Face_service: invalid JSON response");
            return [
                'ok'         => false,
                'reason'     => 'service_error',
                'detail'     => 'Invalid JSON response from sidecar',
                'latency_ms' => $elapsed_ms,
            ];
        }

        $result['latency_ms'] = $elapsed_ms;
        return $result;
    }
}
