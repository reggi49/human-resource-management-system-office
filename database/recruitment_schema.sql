-- ==========================================
-- HRMS Recruitment & Batch AI CV Scanner Module
-- Database Schema Migration Script
-- ==========================================

-- 1. Table: job_postings
CREATE TABLE IF NOT EXISTS `job_postings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_title` VARCHAR(150) NOT NULL,
  `department` VARCHAR(100) NULL,
  `requirements` TEXT NOT NULL,
  `closing_date` DATE NULL,
  `status` ENUM('OPEN', 'CLOSED', 'PROCESSING', 'COMPLETED') NOT NULL DEFAULT 'OPEN',
  `folder_path` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: applicants
CREATE TABLE IF NOT EXISTS `applicants` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_id` INT NOT NULL,
  `name` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `phone` VARCHAR(30) NULL,
  `cv_file` VARCHAR(255) NOT NULL,
  `skills_json` TEXT NULL,
  `experience_summary` TEXT NULL,
  `match_score` INT DEFAULT 0,
  `match_reason` TEXT NULL,
  `status` ENUM('APPLIED', 'SHORTLISTED', 'REJECTED', 'TESTED') NOT NULL DEFAULT 'APPLIED',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`job_id`) REFERENCES `job_postings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Register RBAC Permission for Recruitment
INSERT IGNORE INTO `permissions` (`id`, `name`, `slug`, `module`, `description`) VALUES
(9, 'Manage Recruitment', 'recruitment.manage', 'recruitment', 'Manage job postings, bulk CV scanner, and candidates');

-- Attach permission to Super Admin (1) and HR Manager (2)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 9),
(2, 9);
