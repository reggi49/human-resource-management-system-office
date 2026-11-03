-- ══════════════════════════════════════════════════════════════════════════════
-- SETUP DATABASE FACE RECOGNITION HRMS (RAW SQL QUERY)
-- ══════════════════════════════════════════════════════════════════════════════
-- Petunjuk:
-- 1. Buka phpMyAdmin -> Pilih database HRMS Anda -> Tab 'SQL'.
-- 2. Salin dan tempel (copy-paste) seluruh query di bawah ini, lalu klik 'Go' / 'Kirim'.
-- 3. Atau jalankan via terminal SSH: mysql -u <user> -p <nama_db> < database_setup.sql
-- ══════════════════════════════════════════════════════════════════════════════

-- ─────────────────────────────────────────────────────────────────────────────
-- 1. BUAT TABEL TEMPLATE BIOMETRIK WAJAH (5 Pose per Karyawan, 512-D ArcFace)
-- ─────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `employee_face_templates` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `em_code` VARCHAR(64) NOT NULL COMMENT 'FK ke employee.em_code',
    `pose` ENUM('front','up','down','left','right') NOT NULL COMMENT 'Pose pendaftaran',
    `embedding` JSON NOT NULL COMMENT 'Array 512 float L2-normalized',
    `model_version` VARCHAR(64) NOT NULL DEFAULT 'buffalo_l-w600k_r50-v1',
    `det_score` FLOAT NULL COMMENT 'Skor deteksi wajah SCRFD',
    `blur` FLOAT NULL COMMENT 'Skor ketajaman gambar (Laplacian)',
    `brightness` FLOAT NULL COMMENT 'Tingkat kecerahan gambar',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 = aktif, 0 = nonaktif',
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    UNIQUE KEY `uq_emp_pose` (`em_code`, `pose`, `model_version`),
    INDEX `idx_match` (`is_active`, `model_version`),
    CONSTRAINT `chk_dim` CHECK (JSON_LENGTH(`embedding`) = 512)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─────────────────────────────────────────────────────────────────────────────
-- 2. BUAT TABEL LOG AUDIT PENGENALAN WAJAH & PRESENSI
-- ─────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `face_recognition_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `em_code` VARCHAR(64) NULL COMMENT 'Kandidat terbaik (NULL jika gagal)',
    `runner_up_code` VARCHAR(64) NULL COMMENT 'Kandidat terbaik kedua untuk audit ambigu',
    `purpose` ENUM('enroll','verify') NOT NULL,
    `attendance_type` ENUM('in','out') NULL,
    `decision` ENUM(
        'accepted',
        'rejected_threshold',
        'rejected_ambiguous',
        'rejected_spoof',
        'rejected_quality',
        'rejected_liveness',
        'rejected_nonce',
        'rejected_multiface',
        'service_error'
    ) NOT NULL,
    `best_score` FLOAT NULL COMMENT 'Skor kemiripan tertinggi',
    `second_score` FLOAT NULL COMMENT 'Skor kemiripan kedua',
    `score_margin` FLOAT NULL COMMENT 'Selisih skor (best - second)',
    `liveness_score` FLOAT NULL,
    `matched_pose` VARCHAR(16) NULL,
    `det_score` FLOAT NULL,
    `blur` FLOAT NULL,
    `yaw` FLOAT NULL,
    `pitch` FLOAT NULL,
    `roll` FLOAT NULL,
    `model_version` VARCHAR(64) NULL,
    `threshold_used` FLOAT NULL,
    `margin_used` FLOAT NULL,
    `latency_ms` INT NULL,
    `device_id` VARCHAR(64) NULL,
    `ip` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `lat` DECIMAL(10,7) NULL,
    `lng` DECIMAL(10,7) NULL,
    `snapshot_path` VARCHAR(255) NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `idx_emp_date` (`em_code`, `created_at`),
    INDEX `idx_decision` (`decision`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─────────────────────────────────────────────────────────────────────────────
-- 3. BUAT TABEL NONCE CHALLENGE (Anti-Replay & Anti-Bypass)
-- ─────────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `face_nonce` (
    `nonce` CHAR(32) PRIMARY KEY,
    `em_code` VARCHAR(64) NULL,
    `purpose` ENUM('enroll','verify') NOT NULL,
    `challenge` VARCHAR(32) NOT NULL,
    `used` TINYINT(1) NOT NULL DEFAULT 0,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `idx_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─────────────────────────────────────────────────────────────────────────────
-- 4. TAMBAH KOLOM BIOMETRIK KE TABEL ATTENDANCE
-- ─────────────────────────────────────────────────────────────────────────────
-- Catatan: Query ini menambahkan kolom-kolom baru ke tabel attendance lama.
-- Aman dijalankan di produksi karena bertipe NULL dan tidak mengubah data lama.
ALTER TABLE `attendance`
    ADD COLUMN `face_score_in` FLOAT NULL AFTER `photo_in`,
    ADD COLUMN `face_score_out` FLOAT NULL AFTER `photo_out`,
    ADD COLUMN `liveness_in` FLOAT NULL,
    ADD COLUMN `liveness_out` FLOAT NULL,
    ADD COLUMN `face_log_in_id` BIGINT NULL,
    ADD COLUMN `face_log_out_id` BIGINT NULL,
    ADD COLUMN `verified_by` ENUM('face','pin','manual_hr') NULL;
