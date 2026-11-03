<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gemini AI CV Parser Library for CodeIgniter 3
 */
class Gemini_cv_parser {

    private $CI;
    private $api_key;
    private $api_url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->config->load('config', TRUE);
        $this->api_key = $this->CI->config->item('gemini_api_key') ?: getenv('GEMINI_API_KEY');
    }

    /**
     * Parse CV text/content and score against job requirements
     *
     * @param string $cv_text Raw text extracted from CV or PDF file path
     * @param string $job_title Title of job posting
     * @param string $requirements Criteria and qualifications
     * @return array Structured candidate data
     */
    public function parse_and_score($cv_text, $job_title, $requirements) {
        if (empty($this->api_key)) {
            // Fallback parsing if API key is not configured
            return $this->fallback_parse($cv_text, $job_title);
        }

        $prompt = "Anda adalah Asisten HR AI Professional. Analisis CV berikut dan cocokkan secara obyektif dengan Kriteria Pekerjaan.

KRITERIA PEKERJAAN ($job_title):
$requirements

ISI TEKS CV PELAMAR:
$cv_text

Kembalikan jawaban HANYA berupa JSON valid tanpa format markdown tambahan, dengan struktur persis seperti berikut:
{
  \"name\": \"Nama Lengkap Pelamar\",
  \"email\": \"email@pelamar.com\",
  \"phone\": \"08xxx\",
  \"skills\": [\"Skill1\", \"Skill2\"],
  \"summary\": \"Ringkasan pengalaman kandidat dalam 2 kalimat\",
  \"match_score\": 85,
  \"match_reason\": \"Alasan kesesuaian kandidat dengan kriteria lowongan\"
}";

        $payload = array(
            'contents' => array(
                array(
                    'parts' => array(
                        array('text' => $prompt)
                    )
                )
            ),
            'generationConfig' => array(
                'temperature' => 0.2,
                'responseMimeType' => 'application/json'
            )
        );

        $url = $this->api_url . '?key=' . $this->api_key;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200 && $response) {
            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $raw_json = $result['candidates'][0]['content']['parts'][0]['text'];
                $parsed = json_decode($raw_json, true);
                if (is_array($parsed)) {
                    return array(
                        'name' => isset($parsed['name']) ? $parsed['name'] : 'Unknown Candidate',
                        'email' => isset($parsed['email']) ? $parsed['email'] : '',
                        'phone' => isset($parsed['phone']) ? $parsed['phone'] : '',
                        'skills' => isset($parsed['skills']) ? json_encode($parsed['skills']) : '[]',
                        'summary' => isset($parsed['summary']) ? $parsed['summary'] : '',
                        'match_score' => isset($parsed['match_score']) ? intval($parsed['match_score']) : 50,
                        'match_reason' => isset($parsed['match_reason']) ? $parsed['match_reason'] : ''
                    );
                }
            }
        }

        // Fallback if API call fails or times out
        return $this->fallback_parse($cv_text, $job_title);
    }

    /**
     * Fallback parser if Gemini API Key is missing or unavailable
     */
    private function fallback_parse($cv_text, $job_title) {
        // Simple regex extraction for email and phone
        preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $cv_text, $email_matches);
        preg_match('/(\+?62|0)[0-9]{8,12}/', $cv_text, $phone_matches);

        // Derive name from first line or filename
        $lines = explode("\n", trim($cv_text));
        $first_line = !empty($lines[0]) ? trim($lines[0]) : 'Pelamar Baru';

        return array(
            'name' => (strlen($first_line) < 50) ? $first_line : 'Pelamar CV',
            'email' => !empty($email_matches[0]) ? $email_matches[0] : '',
            'phone' => !empty($phone_matches[0]) ? $phone_matches[0] : '',
            'skills' => json_encode(array('PDF Processed')),
            'summary' => 'Berhasil diekstrak dari berkas CV pelamar (API Key Gemini belum diset).',
            'match_score' => 70,
            'match_reason' => 'Diproses melalui parser lokal fallback. Membutuhkan API Key Gemini untuk penilaian AI otomatis.'
        );
    }
}
