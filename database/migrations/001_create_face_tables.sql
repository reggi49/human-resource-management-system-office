-- ═══════════════════════════════════════════════════════════════════════════
-- HRMS Face Recognition — Migration 001: Create New Face Tables
-- ═══════════════════════════════════════════════════════════════════════════
-- RUN THIS FIRST. Safe to run on production — creates NEW tables only.
-- Does NOT modify or drop any existing tables.
-- ═══════════════════════════════════════════════════════════════════════════

-- 1. Face Templates: 5 SEPARATE rows per employee (one per pose)
--    FIXES ROOT CAUSE (a): No more centroid averaging across poses.
--    UNIQUE on (em_code, pose, model_version) — NOT on em_code alone.
CREATE TABLE IF NOT EXISTS `employee_face_templates` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `em_code` VARCHAR(64) NOT NULL COMMENT 'FK to employee.em_code',
    `pose` ENUM('front','up','down','left','right') NOT NULL,
    `embedding` JSON NOT NULL COMMENT '512 float values, L2-normalized',
    `model_version` VARCHAR(64) NOT NULL COMMENT 'e.g. buffalo_l-w600k_r50-v1',
    `det_score` FLOAT NULL COMMENT 'Detection confidence from SCRFD',
    `blur` FLOAT NULL COMMENT 'Laplacian variance of face crop',
    `brightness` FLOAT NULL COMMENT 'Mean brightness of face crop',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NULL,
    UNIQUE KEY `uq_emp_pose` (`em_code`, `pose`, `model_version`),
    INDEX `idx_match` (`is_active`, `model_version`),
    CONSTRAINT `chk_dim` CHECK (JSON_LENGTH(`embedding`) = 512)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Face Recognition Audit Log
--    FIXES ROOT CAUSE (e): Every attempt logged including failures.
--    runner_up_code recorded to detect systematic misidentification patterns.
CREATE TABLE IF NOT EXISTS `face_recognition_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `em_code` VARCHAR(64) NULL COMMENT 'NULL if unrecognized',
    `runner_up_code` VARCHAR(64) NULL COMMENT 'Second-best candidate for audit',
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
    `best_score` FLOAT NULL,
    `second_score` FLOAT NULL,
    `score_margin` FLOAT NULL,
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
    `created_at` DATETIME NOT NULL COMMENT 'Server time, not client time',
    INDEX `idx_emp_date` (`em_code`, `created_at`),
    INDEX `idx_decision` (`decision`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Nonce Table — single-use, 15-second TTL
CREATE TABLE IF NOT EXISTS `face_nonce` (
    `nonce` CHAR(32) PRIMARY KEY,
    `em_code` VARCHAR(64) NULL,
    `purpose` ENUM('enroll','verify') NOT NULL,
    `challenge` VARCHAR(32) NOT NULL COMMENT 'Random challenge string for anti-cheat',
    `used` TINYINT(1) NOT NULL DEFAULT 0,
    `expires_at` DATETIME NOT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `idx_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
