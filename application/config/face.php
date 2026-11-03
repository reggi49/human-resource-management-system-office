<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Recognition Configuration
 * 
 * All values read from environment variables.
 * Set via .env file or server environment.
 * NEVER hardcode secrets in this file.
 */

// Auto-load .env file if present
$fcpath = defined('FCPATH') ? FCPATH : dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;
$env_path = $fcpath . '.env';
if (file_exists($env_path)) {
    $env_lines = @file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($env_lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if (getenv($name) === false) {
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Sidecar service URL (e.g. http://127.0.0.1:8000 if same host)
$config['face_service_url'] = getenv('FACE_SERVICE_URL') ?: 'http://127.0.0.1:8000';

// HMAC shared secret — MUST match FACE_SECRET on the sidecar
$config['face_secret'] = getenv('FACE_SECRET') ?: 'dev-secret-hrms-local-2026';

// Cosine similarity threshold — NILAI AWAL, BELUM DIKALIBRASI
// Run tools/calibrate.py with real data to determine optimal value
$config['face_threshold'] = (float)(getenv('FACE_THRESHOLD') ?: '0.40');

// Minimum margin between best and second-best score — NILAI AWAL, BELUM DIKALIBRASI
// If margin < this, decision = rejected_ambiguous (offer NIK+PIN fallback)
$config['face_min_margin'] = (float)(getenv('FACE_MIN_MARGIN') ?: '0.06');

// Matching mode: '1toN' (identification) or '1to1' (verification after NIK input)
// If 1:N accuracy is insufficient after calibration, switch to '1to1' — do NOT lower threshold
$config['face_mode'] = getenv('FACE_MODE') ?: '1toN';

// Photo retention in days (default 90, per UU PDP compliance)
$config['photo_retention_days'] = (int)(getenv('PHOTO_RETENTION_DAYS') ?: '90');

// Nonce TTL in seconds
$config['nonce_ttl'] = 15;

// Active model version — must match sidecar MODEL_VERSION
$config['face_model_version'] = 'buffalo_l-w600k_r50-v1';

// Service timeout in seconds
$config['face_service_timeout'] = 25;
