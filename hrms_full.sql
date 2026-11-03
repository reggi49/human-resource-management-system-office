/*
 Navicat Premium / DBram Dump SQL

 Source Server         : MBtech.app (127.0.0.1:3306)
 Source Server Type    : MySQL
 Source Server Version : 5.7.42-0ubuntu0.18.04.1
 Source Host           : 127.0.0.1:3306
 Source Schema         : hrms

 Target Server Type    : MySQL
 Target Server Version : 5.7.42-0ubuntu0.18.04.1
 File Encoding         : 65001

 Date: 2026-11-03 04:17:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for addition
-- ----------------------------
DROP TABLE IF EXISTS `addition`;
CREATE TABLE `addition` (
  `addi_id` int(14) NOT NULL AUTO_INCREMENT,
  `salary_id` int(14) NOT NULL,
  `basic` varchar(128) DEFAULT NULL,
  `medical` varchar(64) DEFAULT NULL,
  `house_rent` varchar(64) DEFAULT NULL,
  `conveyance` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`addi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of addition
-- ----------------------------
BEGIN;
INSERT INTO `addition` (`addi_id`, `salary_id`, `basic`, `medical`, `house_rent`, `conveyance`) VALUES
(25, 28, '', '', '', '');
COMMIT;

-- ----------------------------
-- Table structure for address
-- ----------------------------
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(64) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `country` varchar(128) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `type` enum('Present','Permanent') DEFAULT 'Present',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of address
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for applicants
-- ----------------------------
DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `cv_file` varchar(255) NOT NULL,
  `skills_json` text,
  `experience_summary` text,
  `match_score` int(11) DEFAULT '0',
  `match_reason` text,
  `status` enum('APPLIED','SHORTLISTED','REJECTED','TESTED') NOT NULL DEFAULT 'APPLIED',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `applicants_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of applicants
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `ass_id` int(14) NOT NULL AUTO_INCREMENT,
  `catid` varchar(14) NOT NULL,
  `ass_name` varchar(256) DEFAULT NULL,
  `ass_brand` varchar(128) DEFAULT NULL,
  `ass_model` varchar(256) DEFAULT NULL,
  `ass_code` varchar(256) DEFAULT NULL,
  `configuration` varchar(512) DEFAULT NULL,
  `purchasing_date` varchar(128) DEFAULT NULL,
  `ass_price` varchar(128) DEFAULT NULL,
  `ass_qty` varchar(64) DEFAULT NULL,
  `in_stock` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ass_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assets
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for assets_category
-- ----------------------------
DROP TABLE IF EXISTS `assets_category`;
CREATE TABLE `assets_category` (
  `cat_id` int(14) NOT NULL AUTO_INCREMENT,
  `cat_status` enum('ASSETS','LOGISTIC') NOT NULL DEFAULT 'ASSETS',
  `cat_name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assets_category
-- ----------------------------
BEGIN;
INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES
(1, 'ASSETS', 'TAB'),
(2, 'ASSETS', 'Computer'),
(3, 'ASSETS', 'Laptop'),
(4, 'LOGISTIC', 'tab');
COMMIT;

-- ----------------------------
-- Table structure for assign_leave
-- ----------------------------
DROP TABLE IF EXISTS `assign_leave`;
CREATE TABLE `assign_leave` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(11) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(14) NOT NULL,
  `day` varchar(256) DEFAULT NULL,
  `hour` varchar(255) NOT NULL,
  `total_day` varchar(64) DEFAULT NULL,
  `dateyear` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assign_leave
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for assign_task
-- ----------------------------
DROP TABLE IF EXISTS `assign_task`;
CREATE TABLE `assign_task` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `task_id` int(14) NOT NULL,
  `project_id` int(14) NOT NULL,
  `assign_user` varchar(64) DEFAULT NULL,
  `user_type` enum('Team Head','Collaborators') NOT NULL DEFAULT 'Collaborators',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assign_task
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for attendance
-- ----------------------------
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(64) DEFAULT NULL,
  `atten_date` varchar(64) DEFAULT NULL,
  `signin_time` time DEFAULT NULL,
  `signout_time` time DEFAULT NULL,
  `working_hour` varchar(64) DEFAULT NULL,
  `place` varchar(255) NOT NULL,
  `absence` varchar(128) DEFAULT NULL,
  `overtime` varchar(128) DEFAULT NULL,
  `earnleave` varchar(128) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `lat_in` varchar(64) DEFAULT NULL,
  `lng_in` varchar(64) DEFAULT NULL,
  `lat_out` varchar(64) DEFAULT NULL,
  `lng_out` varchar(64) DEFAULT NULL,
  `photo_in` varchar(255) DEFAULT NULL,
  `face_score_in` float DEFAULT NULL,
  `photo_out` varchar(255) DEFAULT NULL,
  `face_score_out` float DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `capture_in` varchar(255) DEFAULT NULL,
  `capture_out` varchar(255) DEFAULT NULL,
  `device_in` varchar(32) DEFAULT 'WEB',
  `device_out` varchar(32) DEFAULT 'WEB',
  `mode` varchar(32) DEFAULT 'WEB',
  `liveness_in` float DEFAULT NULL,
  `liveness_out` float DEFAULT NULL,
  `face_log_in_id` bigint(20) DEFAULT NULL,
  `face_log_out_id` bigint(20) DEFAULT NULL,
  `verified_by` enum('face','pin','manual_hr') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attendance
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for bank_info
-- ----------------------------
DROP TABLE IF EXISTS `bank_info`;
CREATE TABLE `bank_info` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `holder_name` varchar(256) DEFAULT NULL,
  `bank_name` varchar(256) DEFAULT NULL,
  `branch_name` varchar(256) DEFAULT NULL,
  `account_number` varchar(256) DEFAULT NULL,
  `account_type` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bank_info
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for company_data
-- ----------------------------
DROP TABLE IF EXISTS `company_data`;
CREATE TABLE `company_data` (
  `id_company` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) DEFAULT NULL,
  `lat` varchar(100) DEFAULT NULL,
  `lng` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_company`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of company_data
-- ----------------------------
BEGIN;
INSERT INTO `company_data` (`id_company`, `company_name`, `lat`, `lng`) VALUES
(1, 'MBtech', '-6.160981', '106.904365');
COMMIT;

-- ----------------------------
-- Table structure for deduction
-- ----------------------------
DROP TABLE IF EXISTS `deduction`;
CREATE TABLE `deduction` (
  `de_id` int(14) NOT NULL AUTO_INCREMENT,
  `salary_id` int(14) NOT NULL,
  `provident_fund` varchar(64) DEFAULT NULL,
  `bima` varchar(64) DEFAULT NULL,
  `tax` varchar(64) DEFAULT NULL,
  `others` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`de_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of deduction
-- ----------------------------
BEGIN;
INSERT INTO `deduction` (`de_id`, `salary_id`, `provident_fund`, `bima`, `tax`, `others`) VALUES
(25, 28, '', '', '', '');
COMMIT;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(64) NOT NULL,
  `manager_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of department
-- ----------------------------
BEGIN;
INSERT INTO `department` (`id`, `dep_name`, `manager_id`) VALUES
(2, 'Administration', NULL),
(3, 'Finance, HR, & Admininstration', NULL),
(4, 'Research', NULL),
(5, 'Information Technology', NULL),
(6, 'Support', NULL),
(7, 'Digital Production', 'Pam2009');
COMMIT;

-- ----------------------------
-- Table structure for desciplinary
-- ----------------------------
DROP TABLE IF EXISTS `desciplinary`;
CREATE TABLE `desciplinary` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `action` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of desciplinary
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for designation
-- ----------------------------
DROP TABLE IF EXISTS `designation`;
CREATE TABLE `designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `des_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of designation
-- ----------------------------
BEGIN;
INSERT INTO `designation` (`id`, `des_name`) VALUES
(2, 'Vice Chairman'),
(3, 'Chief Executive Officer (CEO)'),
(4, 'Chief Finance & Admin Officer'),
(5, 'Sr. Finance & Admin Officer - I'),
(6, 'Jr. Finance & Admin Officer'),
(7, 'Senior Research Associate-1'),
(8, 'Research Associate-1'),
(9, 'Junior Research Associate'),
(10, 'Research Assistant'),
(11, 'Sr. Office Assistant'),
(12, 'Office Assistant'),
(13, 'IT Analyst'),
(14, 'Cook'),
(15, 'Digital Production Manager'),
(16, 'Digital Production Supervisor'),
(17, 'Digital Production Video Editor'),
(18, 'Digital Production Programmer'),
(19, 'Digital Production Cameraman'),
(20, 'Digital Production Staff');
COMMIT;

-- ----------------------------
-- Table structure for division
-- ----------------------------
DROP TABLE IF EXISTS `division`;
CREATE TABLE `division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `div_code` varchar(32) DEFAULT NULL,
  `div_name` varchar(128) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of division
-- ----------------------------
BEGIN;
INSERT INTO `division` (`id`, `div_code`, `div_name`, `created_at`) VALUES
(5, '05', 'DIGITAL PRODUCTION DIVISION', '2026-09-10 13:21:48'),
(7, '07', 'DIGITAL PRODUCTION DIVISION', '2026-09-10 13:21:48');
COMMIT;

-- ----------------------------
-- Table structure for earned_leave
-- ----------------------------
DROP TABLE IF EXISTS `earned_leave`;
CREATE TABLE `earned_leave` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `present_date` varchar(64) DEFAULT NULL,
  `hour` varchar(64) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of earned_leave
-- ----------------------------
BEGIN;
INSERT INTO `earned_leave` (`id`, `em_id`, `present_date`, `hour`, `status`) VALUES
(26, 'Mir1685', '0', '0', '1'),
(27, 'Rah1682', '0', '0', '1'),
(28, 'edr1432', '0', '0', '1');
COMMIT;

-- ----------------------------
-- Table structure for education
-- ----------------------------
DROP TABLE IF EXISTS `education`;
CREATE TABLE `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(128) DEFAULT NULL,
  `edu_type` varchar(256) DEFAULT NULL,
  `institute` varchar(256) DEFAULT NULL,
  `result` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of education
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_no` int(11) DEFAULT NULL,
  `em_id` varchar(64) DEFAULT NULL,
  `em_code` varchar(64) DEFAULT NULL,
  `des_id` int(11) DEFAULT NULL,
  `dep_id` int(11) DEFAULT NULL,
  `division_id` int(11) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `em_email` varchar(64) DEFAULT NULL,
  `em_password` varchar(512) NOT NULL,
  `pin` varchar(64) NOT NULL DEFAULT '1234',
  `em_role` enum('ADMIN','EMPLOYEE','SUPER ADMIN') NOT NULL DEFAULT 'EMPLOYEE',
  `em_address` varchar(512) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `em_gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `em_phone` varchar(64) DEFAULT NULL,
  `em_birthday` varchar(128) DEFAULT NULL,
  `birth_place` varchar(128) DEFAULT NULL,
  `ttl` varchar(255) DEFAULT NULL,
  `em_blood_group` enum('O+','O-','A+','A-','B+','B-','AB+','OB+') DEFAULT NULL,
  `em_joining_date` varchar(128) DEFAULT NULL,
  `em_contact_end` varchar(128) DEFAULT NULL,
  `em_image` varchar(128) DEFAULT NULL,
  `em_status` varchar(20) NOT NULL DEFAULT 'active',
  `em_nid` varchar(64) DEFAULT NULL,
  `em_marital_status` varchar(64) DEFAULT NULL,
  `em_dependents` int(11) DEFAULT '0',
  `em_ptkp` varchar(32) DEFAULT 'TK0',
  `em_bpjs_kes` varchar(64) DEFAULT NULL,
  `em_bpjs_tk` varchar(64) DEFAULT NULL,
  `bpjs_status` varchar(32) DEFAULT 'ACTIVE',
  `em_npwp` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee
-- ----------------------------
BEGIN;
INSERT INTO `employee` (`id`, `emp_no`, `em_id`, `em_code`, `des_id`, `dep_id`, `division_id`, `first_name`, `last_name`, `em_email`, `em_password`, `pin`, `em_role`, `em_address`, `status`, `em_gender`, `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_blood_group`, `em_joining_date`, `em_contact_end`, `em_image`, `em_status`, `em_nid`, `em_marital_status`, `em_dependents`, `em_ptkp`, `em_bpjs_kes`, `em_bpjs_tk`, `bpjs_status`, `em_npwp`) VALUES
(10, NULL, 'Soy1332', '99', 0, 0, NULL, 'Dir', 'Soyeb', 'nawjeshbd@gmail.com', '996a3778768a2c3ea7c5b586fdfc92051dfdd39c', '1234', 'SUPER ADMIN', NULL, 'ACTIVE', 'Female', '01723177901', '2017-12-26', NULL, NULL, 'B+', '2018-01-06', '2018-01-06', 'Soy1332.jpg', 'active', '132154566556', NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(36, NULL, 'Doe1753', '123456', 12, 2, NULL, 'Jhon', 'Pantau', 'johnpantau', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'ADMIN', NULL, 'ACTIVE', 'Male', 'admin123456', '2019-02-13', NULL, NULL, 'O+', '2019-02-15', '2019-02-22', 'Doe1753.jpg', 'active', '01253568955555', NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(37, NULL, 'Doe1754', '123444', 12, 2, NULL, 'Jhon', 'Doe', 'employee@gmail.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', 'abc123456', '2019-02-13', NULL, NULL, 'O+', '2019-02-15', '2019-02-22', 'Doe1753.jpg', 'active', '01253568955555', NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(38, 15, 'Pam2009', '20091105', 15, 7, 5, 'Edi', 'Pamungkas', 'edi', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'ADMIN', 'Pondok Jati Indah Blok A2-1 Jatikramat Jatiasih Bekasi', 'ACTIVE', 'Male', '0812-1059-117', '1969-07-05', 'Salatiga', 'Salatiga, 05 Juli 1969', NULL, '2009-11-23', NULL, NULL, 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(39, 16, 'Erl2015', '20150605', 16, 7, 5, 'Muhamad Arif', 'Erlangga', 'arif', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Palem Raya No.9 Rt.001 Rw.008 Petukangan Utara Jakarta Selatan', 'ACTIVE', 'Male', '0812-1213-3663', '1975-06-11', 'Jakarta', 'Jakarta, 11 Juni 1975', NULL, '2015-06-08', NULL, 'face_3d_20150605.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(40, 17, 'Win2014', '20140705', 17, 7, 5, 'Hari', 'Windariyadi', 'hariwin', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Perumahan Griya Citra Kayumanis Blok B No.14 Jl. Raya Kencana 2 Mekarwangi Tanah Sereal Bogor', 'ACTIVE', 'Male', '0818-8084-94393', '1976-07-10', 'Lumajang', 'Lumajang, 10 Juli 1976', NULL, '2014-07-01', NULL, 'face_3d_20140705.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(41, 18, 'Elg2015', '20150305', 17, 7, 5, 'Ahmad Ridwan', 'Elgifary', 'elgi', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Lurah Ripan / Gudang Kapas No.12 Rt.003/Rw.017 Cilangkap Tapos Kota Depok', 'ACTIVE', 'Male', '0878-7867-9390', '1977-06-24', 'Jakarta', 'Jakarta, 24 Juni 1977', NULL, '2015-03-02', NULL, 'face_3d_20150305.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(43, 20, 'Kus2020', '20201205', 18, 7, 5, 'Bramastyo', 'Kusumo', 'bkoes', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Kalimalang Bungur No.17 Harapan Jaya Bekasi Utara', 'ACTIVE', 'Male', '0821-1113-1902', '1992-05-03', 'Mojokerto', 'Mojokerto, 3 Mei 1992', NULL, '2020-12-01', NULL, 'face_3d_20201205.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(44, 21, 'Afr2021', '20210105', 18, 7, 5, 'Aris', 'Afrianto', 'aris', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Angsana Kel. Utan Kayu Utara Matraman Jakarta Timur', 'ACTIVE', 'Male', '0822-1876-9546', '1996-04-23', 'Cirebon', 'Cirebon, 23 April 1996', NULL, '2021-01-04', NULL, NULL, 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(45, 22, 'Idr2024', '20240805', 19, 7, 5, 'Muhammad Kamil', 'Idris', 'kamil', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Kampung Duri Dalam No.13 Rt.010 RW.003 Kel. Duri Selatan Kec. Tambora Jakarta Barat', 'ACTIVE', 'Male', '08215477944', '1999-10-01', 'Jakarta', 'Jakarta, 01 Oktober 1999', NULL, '2024-08-12', NULL, 'face_3d_20240805.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL),
(46, 36, 'Ban2012', '20120307', 20, 7, 5, 'Akhbar Bhakti', 'Banuadji', 'akbar', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', '1234', 'EMPLOYEE', 'Jl. Pondok Bambu Asri Blok A2 No.9 Pondok Bambu Jakarta Timur', 'ACTIVE', 'Male', '021-86611218', '1978-12-29', 'Jakarta', 'Jakarta, 29 Desember 1978', NULL, '2012-03-01', NULL, 'face_3d_20120307.jpg', 'active', NULL, NULL, 0, 'TK0', NULL, NULL, 'ACTIVE', NULL);
COMMIT;

-- ----------------------------
-- Table structure for employee_face_signatures
-- ----------------------------
DROP TABLE IF EXISTS `employee_face_signatures`;
CREATE TABLE `employee_face_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(50) NOT NULL,
  `vector_data` longtext NOT NULL,
  `vector_web` longtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `em_id` (`em_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of employee_face_signatures
-- ----------------------------
BEGIN;
INSERT INTO `employee_face_signatures` (`id`, `em_id`, `vector_data`, `vector_web`, `created_at`, `updated_at`) VALUES
(1, 'Soy1332', 'vector_data_1', 'vector_web_1', '2023-01-01 00:00:00', '2023-01-01 00:00:00'),
(2, 'Doe1753', 'vector_data_2', 'vector_web_2', '2023-01-01 00:00:00', '2023-01-01 00:00:00'),
(3, 'Doe1754', 'vector_data_3', 'vector_web_3', '2023-01-01 00:00:00', '2023-01-01 

-- ----------------------------
-- Table structure for employee_face_templates
-- ----------------------------
DROP TABLE IF EXISTS `employee_face_templates`;
CREATE TABLE `employee_face_templates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `em_code` varchar(64) NOT NULL COMMENT 'FK ke employee.em_code',
  `pose` enum('front','up','down','left','right') NOT NULL COMMENT 'Pose pendaftaran',
  `embedding` json NOT NULL COMMENT 'Array 512 float L2-normalized',
  `model_version` varchar(64) NOT NULL DEFAULT 'buffalo_l-w600k_r50-v1',
  `det_score` float DEFAULT NULL COMMENT 'Skor deteksi wajah SCRFD',
  `blur` float DEFAULT NULL COMMENT 'Skor ketajaman gambar (Laplacian)',
  `brightness` float DEFAULT NULL COMMENT 'Tingkat kecerahan gambar',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = aktif, 0 = nonaktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_emp_pose` (`em_code`,`pose`,`model_version`),
  KEY `idx_match` (`is_active`,`model_version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of employee_face_templates
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for employee_file
-- ----------------------------
DROP TABLE IF EXISTS `employee_file`;
CREATE TABLE `employee_file` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `file_title` varchar(512) DEFAULT NULL,
  `file_url` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of employee_file
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for employee_roles
-- ----------------------------
DROP TABLE IF EXISTS `employee_roles`;
CREATE TABLE `employee_roles` (
  `employee_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `scope` enum('SELF','SUBORDINATE','DEPARTMENT','ORGANIZATION') NOT NULL DEFAULT 'SELF',
  PRIMARY KEY (`employee_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `employee_roles_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of employee_roles
-- ----------------------------
BEGIN;
INSERT INTO `employee_roles` (`employee_id`, `role_id`, `scope`) VALUES
(36, 1, 'ORGANIZATION'),
(38, 5, 'DEPARTMENT'),
(39, 6, 'SELF'),
(40, 6, 'SELF'),
(41, 6, 'SELF'),
(42, 6, 'SELF'),
(43, 6, 'SELF'),
(44, 6, 'SELF'),
(45, 6, 'SELF'),
(46, 6, 'SELF');
COMMIT;

-- ----------------------------
-- Table structure for emp_assets
-- ----------------------------
DROP TABLE IF EXISTS `emp_assets`;
CREATE TABLE `emp_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `assets_id` int(11) NOT NULL,
  `given_date` date NOT NULL,
  `return_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_assets
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for emp_experience
-- ----------------------------
DROP TABLE IF EXISTS `emp_experience`;
CREATE TABLE `emp_experience` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(256) DEFAULT NULL,
  `exp_company` varchar(128) DEFAULT NULL,
  `exp_com_position` varchar(128) DEFAULT NULL,
  `exp_com_address` varchar(128) DEFAULT NULL,
  `exp_workduration` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_experience
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for emp_leave
-- ----------------------------
DROP TABLE IF EXISTS `emp_leave`;
CREATE TABLE `emp_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `typeid` int(14) NOT NULL,
  `leave_type` varchar(64) DEFAULT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `end_date` varchar(64) DEFAULT NULL,
  `leave_duration` varchar(128) DEFAULT NULL,
  `apply_date` varchar(64) DEFAULT NULL,
  `reason` varchar(1024) DEFAULT NULL,
  `leave_status` enum('Approve','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of emp_leave
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for emp_penalty
-- ----------------------------
DROP TABLE IF EXISTS `emp_penalty`;
CREATE TABLE `emp_penalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `penalty_id` int(11) NOT NULL,
  `penalty_desc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_penalty
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for emp_salary
-- ----------------------------
DROP TABLE IF EXISTS `emp_salary`;
CREATE TABLE `emp_salary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(11) NOT NULL,
  `total` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_salary
-- ----------------------------
BEGIN;
INSERT INTO `emp_salary` (`id`, `emp_id`, `type_id`, `total`) VALUES
(25, 'Soy1332', 2, '8000000'),
(26, 'Doe1753', 2, '6000000'),
(27, 'Doe1754', 2, '5500000'),
(28, 'Soy1332', 2, '8000000');
COMMIT;

-- ----------------------------
-- Table structure for emp_training
-- ----------------------------
DROP TABLE IF EXISTS `emp_training`;
CREATE TABLE `emp_training` (
  `id` int(11) NOT NULL,
  `trainig_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_training
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for evaluation_periods
-- ----------------------------
DROP TABLE IF EXISTS `evaluation_periods`;
CREATE TABLE `evaluation_periods` (
  `period_id` int(11) NOT NULL AUTO_INCREMENT,
  `period_name` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of evaluation_periods
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for face_nonce
-- ----------------------------
DROP TABLE IF EXISTS `face_nonce`;
CREATE TABLE `face_nonce` (
  `nonce` char(32) NOT NULL,
  `em_code` varchar(64) DEFAULT NULL,
  `purpose` enum('enroll','verify') NOT NULL,
  `challenge` varchar(32) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`nonce`),
  KEY `idx_expiry` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of face_nonce
-- ----------------------------
BEGIN;
INSERT INTO `face_nonce` (`nonce`, `em_code`, `purpose`, `challenge`, `used`, `expires_at`, `created_at`) VALUES
('0e66b4a2aeca71a02f01c6f43927334f', NULL, 'verify', '8604ebd2e6a19b55a387e857ead450f0', 1, '2026-09-12 13:51:45', '2026-09-12 13:51:30'),
('33070d0defceae3e1ce343bcfa01f25e', NULL, 'verify', '4501971f30631856bc6dcfd89f0ee1c9', 0, '2026-09-12 13:53:11', '2026-09-12 13:52:56'),
('5690f814b47f44e672261a6040b24606', NULL, 'verify', '2dc9621c5f605bd6bc1ad300117e4216', 1, '2026-09-12 13:51:25', '2026-09-12 13:51:10'),
('7a309f9c9de654c40e768acf7f127221', NULL, 'verify', '965301f7b7b5c6e1c1dcdf2b12abe575', 1, '2026-09-12 13:51:39', '2026-09-12 13:51:24'),
('8c45b67ca19eb822c205279871d6ca1d', NULL, 'verify', 'c889f970e13ecf69c2b1fbba40e746e1', 0, '2026-09-12 13:51:52', '2026-09-12 13:51:37');
COMMIT;

-- ----------------------------
-- Table structure for face_recognition_log
-- ----------------------------
DROP TABLE IF EXISTS `face_recognition_log`;
CREATE TABLE `face_recognition_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `em_code` varchar(64) DEFAULT NULL COMMENT 'Kandidat terbaik (NULL jika gagal)',
  `runner_up_code` varchar(64) DEFAULT NULL COMMENT 'Kandidat kedua untuk audit ambigu',
  `purpose` enum('enroll','verify') NOT NULL,
  `attendance_type` enum('in','out') DEFAULT NULL,
  `decision` enum('accepted','rejected_threshold','rejected_ambiguous','rejected_spoof','rejected_quality','rejected_liveness','rejected_nonce','rejected_multiface','service_error') NOT NULL,
  `best_score` float DEFAULT NULL COMMENT 'Skor kemiripan tertinggi',
  `second_score` float DEFAULT NULL COMMENT 'Skor kemiripan kedua',
  `score_margin` float DEFAULT NULL COMMENT 'Selisih skor (best - second)',
  `liveness_score` float DEFAULT NULL,
  `matched_pose` varchar(16) DEFAULT NULL,
  `det_score` float DEFAULT NULL,
  `blur` float DEFAULT NULL,
  `yaw` float DEFAULT NULL,
  `pitch` float DEFAULT NULL,
  `roll` float DEFAULT NULL,
  `model_version` varchar(64) DEFAULT NULL,
  `threshold_used` float DEFAULT NULL,
  `margin_used` float DEFAULT NULL,
  `latency_ms` int(11) DEFAULT NULL,
  `device_id` varchar(64) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `snapshot_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_emp_date` (`em_code`,`created_at`),
  KEY `idx_decision` (`decision`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of face_recognition_log
-- ----------------------------
BEGIN;
INSERT INTO `face_recognition_log` (`id`, `em_code`, `runner_up_code`, `purpose`, `attendance_type`, `decision`, `best_score`, `second_score`, `score_margin`, `liveness_score`, `matched_pose`, `det_score`, `blur`, `yaw`, `pitch`, `roll`, `model_version`, `threshold_used`, `margin_used`, `latency_ms`, `device_id`, `ip`, `user_agent`, `lat`, `lng`, `snapshot_path`, `created_at`) VALUES
(1, NULL, NULL, 'verify', NULL, 'service_error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '103.84.235.222', 'Mozilla/5.0 (iPad; CPU OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/153.0.8010.24 Mobile/15E148 Safari/604.1', NULL, NULL, NULL, '2026-09-12 13:51:19'),
(2, NULL, NULL, 'verify', NULL, 'service_error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '103.84.235.222', 'Mozilla/5.0 (iPad; CPU OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/153.0.8010.24 Mobile/15E148 Safari/604.1', NULL, NULL, NULL, '2026-09-12 13:51:24'),
(3, NULL, NULL, 'verify', NULL, 'service_error', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '103.84.235.222', 'Mozilla/5.0 (iPad; CPU OS 26_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/153.0.8010.24 Mobile/15E148 Safari/604.1', NULL, NULL, NULL, '2026-09-12 13:51:32'),
(4, NULL, NULL, 'verify', NULL, 'rejected_nonce', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '103.84.235.222', NULL, NULL, NULL, NULL, '2026-09-12 13:52:51');
COMMIT;

-- ----------------------------
-- Table structure for field_visit
-- ----------------------------
DROP TABLE IF EXISTS `field_visit`;
CREATE TABLE `field_visit` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(256) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `field_location` varchar(512) NOT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `approx_end_date` varchar(28) NOT NULL,
  `total_days` varchar(64) DEFAULT NULL,
  `notes` varchar(500) NOT NULL,
  `actual_return_date` varchar(28) NOT NULL,
  `status` enum('Approved','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve',
  `attendance_updated` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of field_visit
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for holiday
-- ----------------------------
DROP TABLE IF EXISTS `holiday`;
CREATE TABLE `holiday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(256) DEFAULT NULL,
  `from_date` varchar(64) DEFAULT NULL,
  `to_date` varchar(64) DEFAULT NULL,
  `number_of_days` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of holiday
-- ----------------------------
BEGIN;
INSERT INTO `holiday` (`id`, `holiday_name`, `from_date`, `to_date`, `number_of_days`, `year`) VALUES
(1, 'Aids Day', '2017-12-21', '2017-12-29', '2', '2017'),
(3, 'Language Day', '2018-02-21', '2018-02-21', '1', '02-2018'),
(4, 'independent day', '2018-03-26', '', '1', '03-2018');
COMMIT;

-- ----------------------------
-- Table structure for job_postings
-- ----------------------------
DROP TABLE IF EXISTS `job_postings`;
CREATE TABLE `job_postings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_title` varchar(150) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `requirements` text NOT NULL,
  `closing_date` date DEFAULT NULL,
  `status` enum('OPEN','CLOSED','PROCESSING','COMPLETED') NOT NULL DEFAULT 'OPEN',
  `folder_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of job_postings
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for kpis
-- ----------------------------
DROP TABLE IF EXISTS `kpis`;
CREATE TABLE `kpis` (
  `kpi_id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi_name` varchar(255) NOT NULL,
  `kpi_category` varchar(50) DEFAULT NULL,
  `kpi_level` enum('Individu','Organisasi','Perusahaan') NOT NULL,
  `description` text,
  `target_value` decimal(10,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`kpi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of kpis
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for leave_types
-- ----------------------------
DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `type_id` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `leave_day` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of leave_types
-- ----------------------------
BEGIN;
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES
(1, 'Casual Leave', '21', 1),
(2, 'Sick Leave', '15', 1),
(3, 'Maternity Leave', '90', 1),
(4, 'Paternal Leave', '7', 1),
(5, 'Earned leave', '', 1),
(7, 'Public Holiday', '', 1),
(8, 'Optional Leave', '', 1),
(9, 'Leave without Pay', '', 1);
COMMIT;

-- ----------------------------
-- Table structure for loan
-- ----------------------------
DROP TABLE IF EXISTS `loan`;
CREATE TABLE `loan` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(256) DEFAULT NULL,
  `amount` varchar(256) DEFAULT NULL,
  `interest_percentage` varchar(256) DEFAULT NULL,
  `total_amount` varchar(64) DEFAULT NULL,
  `total_pay` varchar(64) DEFAULT NULL,
  `total_due` varchar(64) DEFAULT NULL,
  `installment` varchar(256) DEFAULT NULL,
  `loan_number` varchar(256) DEFAULT NULL,
  `loan_details` varchar(256) DEFAULT NULL,
  `approve_date` varchar(256) DEFAULT NULL,
  `install_period` varchar(256) DEFAULT NULL,
  `status` enum('Granted','Deny','Pause','Done') NOT NULL DEFAULT 'Pause',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of loan
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for loan_installment
-- ----------------------------
DROP TABLE IF EXISTS `loan_installment`;
CREATE TABLE `loan_installment` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `loan_id` int(14) NOT NULL,
  `emp_id` varchar(64) DEFAULT NULL,
  `loan_number` varchar(256) DEFAULT NULL,
  `install_amount` varchar(256) DEFAULT NULL,
  `pay_amount` varchar(64) DEFAULT NULL,
  `app_date` varchar(256) DEFAULT NULL,
  `receiver` varchar(256) DEFAULT NULL,
  `install_no` varchar(256) DEFAULT NULL,
  `notes` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of loan_installment
-- ----------------------------
BEGIN;
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES
(27, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-21', 'dsf dsf ds fds fdsf ds', '1', 'sf ds fsd fds fsd fdsf ds fsd'),
(28, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-04', 'dsdsff dsf ds f', '0', 'f dsfdsf dsfs'),
(29, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-04-05', NULL, '4', NULL),
(30, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-05', NULL, '3', NULL),
(31, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-07', NULL, '2', NULL);
COMMIT;

-- ----------------------------
-- Table structure for logistic_asset
-- ----------------------------
DROP TABLE IF EXISTS `logistic_asset`;
CREATE TABLE `logistic_asset` (
  `log_id` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `qty` varchar(64) DEFAULT NULL,
  `entry_date` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logistic_asset
-- ----------------------------
BEGIN;
INSERT INTO `logistic_asset` (`log_id`, `name`, `qty`, `entry_date`) VALUES
(1, 'Lubricant', '30', '12/25/17');
COMMIT;

-- ----------------------------
-- Table structure for logistic_assign
-- ----------------------------
DROP TABLE IF EXISTS `logistic_assign`;
CREATE TABLE `logistic_assign` (
  `ass_id` int(14) NOT NULL AUTO_INCREMENT,
  `asset_id` int(14) NOT NULL,
  `assign_id` varchar(64) DEFAULT NULL,
  `project_id` int(14) NOT NULL,
  `task_id` int(14) NOT NULL,
  `log_qty` varchar(64) DEFAULT NULL,
  `start_date` varchar(64) DEFAULT NULL,
  `end_date` varchar(64) DEFAULT NULL,
  `back_date` varchar(64) DEFAULT NULL,
  `back_qty` varchar(64) DEFAULT NULL,
  `remarks` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`ass_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logistic_assign
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text,
  `file_url` varchar(256) DEFAULT NULL,
  `date` varchar(64) DEFAULT NULL,
  `is_mandatory` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notice
-- ----------------------------
BEGIN;
INSERT INTO `notice` (`id`, `title`, `description`, `file_url`, `date`, `is_mandatory`) VALUES
(4, 'Surat Edaran Libur Bersama & Penyesuaian Hari Kerja', 'Diberitahukan kepada seluruh karyawan bahwa sehubungan dengan agenda Libur Nasional dan Cuti Bersama, kantor akan libur pada tanggal yang tertera. Seluruh karyawan wajib membaca, memahami, dan membubuhkan tanda tangan digital persetujuan dengan PIN masing-masing.', 'edaran_libur.png', '2026-09-10', 1);
COMMIT;

-- ----------------------------
-- Table structure for notice_signatures
-- ----------------------------
DROP TABLE IF EXISTS `notice_signatures`;
CREATE TABLE `notice_signatures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_id` int(11) NOT NULL,
  `em_id` varchar(64) NOT NULL,
  `signed_at` datetime NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `status` varchar(20) NOT NULL DEFAULT 'SIGNED',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_notice_employee` (`notice_id`,`em_id`),
  KEY `idx_notice` (`notice_id`),
  KEY `idx_employee` (`em_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice_signatures
-- ----------------------------
BEGIN;
INSERT INTO `notice_signatures` (`id`, `notice_id`, `em_id`, `signed_at`, `ip_address`, `user_agent`, `status`) VALUES
(2, 4, 'Kus2020', '2026-09-10 15:54:12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'SIGNED'),
(3, 4, 'Reg2018', '2026-09-11 10:24:42', '103.84.235.222', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:155.0) Gecko/20100101 Firefox/155.0', 'SIGNED'),
(4, 4, 'Idr2024', '2026-09-11 13:24:09', '103.84.235.222', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.3 Safari/605.1.15', 'SIGNED');
COMMIT;

-- ----------------------------
-- Table structure for owner
-- ----------------------------
DROP TABLE IF EXISTS `owner`;
CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(64) NOT NULL,
  `owner_position` varchar(64) DEFAULT NULL,
  `note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of owner
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for pay_salary
-- ----------------------------
DROP TABLE IF EXISTS `pay_salary`;
CREATE TABLE `pay_salary` (
  `pay_id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(64) DEFAULT NULL,
  `type_id` int(14) NOT NULL,
  `month` varchar(64) DEFAULT NULL,
  `year` varchar(64) DEFAULT NULL,
  `paid_date` varchar(64) DEFAULT NULL,
  `total_days` varchar(64) DEFAULT NULL,
  `basic` varchar(64) DEFAULT NULL,
  `medical` varchar(64) DEFAULT NULL,
  `house_rent` varchar(64) DEFAULT NULL,
  `bonus` varchar(64) DEFAULT NULL,
  `bima` varchar(64) DEFAULT NULL,
  `tax` varchar(64) DEFAULT NULL,
  `provident_fund` varchar(64) DEFAULT NULL,
  `loan` varchar(64) DEFAULT NULL,
  `total_pay` varchar(128) DEFAULT NULL,
  `addition` int(128) NOT NULL,
  `diduction` int(128) NOT NULL,
  `status` enum('Paid','Process') DEFAULT 'Process',
  `paid_type` enum('Hand Cash','Bank') NOT NULL DEFAULT 'Bank',
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pay_salary
-- ----------------------------
BEGIN;
INSERT INTO `pay_salary` (`pay_id`, `emp_id`, `type_id`, `month`, `year`, `paid_date`, `total_days`, `basic`, `medical`, `house_rent`, `bonus`, `bima`, `tax`, `provident_fund`, `loan`, `total_pay`, `addition`, `diduction`, `status`, `paid_type`) VALUES
(32, 'Doe1753', 0, 'July', '2026', '2026-07-29', NULL, '6000000', NULL, NULL, NULL, '240000', '160250', NULL, NULL, '7899750', 0, 0, 'Paid', 'Bank');
COMMIT;

-- ----------------------------
-- Table structure for penalty
-- ----------------------------
DROP TABLE IF EXISTS `penalty`;
CREATE TABLE `penalty` (
  `id` int(11) NOT NULL,
  `penalty_name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of penalty
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of permissions
-- ----------------------------
BEGIN;
INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`) VALUES
(1, 'View Payroll', 'payroll.view', 'payroll', 'View salary slips and payroll history', '2026-08-11 21:35:57'),
(2, 'Manage Payroll', 'payroll.manage', 'payroll', 'Process payroll, edit salary components', '2026-08-11 21:35:57'),
(3, 'View Employee', 'employee.view', 'employee', 'View employee list and details', '2026-08-11 21:35:57'),
(4, 'Manage Employee', 'employee.manage', 'employee', 'Add, edit, or terminate employee accounts', '2026-08-11 21:35:57'),
(5, 'View Attendance', 'attendance.view', 'attendance', 'View attendance logs', '2026-08-11 21:35:57'),
(6, 'Approve Leave', 'leave.approve', 'leave', 'Approve or reject leave requests', '2026-08-11 21:35:57'),
(7, 'Apply Leave', 'leave.apply', 'leave', 'Submit leave requests', '2026-08-11 21:35:57'),
(8, 'Manage System Settings', 'settings.manage', 'system', 'Configure system parameters and RBAC', '2026-08-11 21:35:57'),
(9, 'Manage Recruitment', 'recruitment.manage', 'recruitment', 'Manage job postings, bulk CV scanner, and candidates', '2026-08-12 09:38:21');
COMMIT;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `pro_name` varchar(128) DEFAULT NULL,
  `pro_start_date` varchar(128) DEFAULT NULL,
  `pro_end_date` varchar(128) DEFAULT NULL,
  `pro_description` varchar(1024) DEFAULT NULL,
  `pro_summary` varchar(512) DEFAULT NULL,
  `pro_status` enum('upcoming','complete','running') NOT NULL DEFAULT 'running',
  `progress` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of project
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for project_file
-- ----------------------------
DROP TABLE IF EXISTS `project_file`;
CREATE TABLE `project_file` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `pro_id` int(14) NOT NULL,
  `file_details` varchar(1028) DEFAULT NULL,
  `file_url` varchar(256) DEFAULT NULL,
  `assigned_to` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_file
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for pro_expenses
-- ----------------------------
DROP TABLE IF EXISTS `pro_expenses`;
CREATE TABLE `pro_expenses` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `pro_id` int(14) NOT NULL,
  `assign_to` varchar(64) DEFAULT NULL,
  `details` varchar(512) DEFAULT NULL,
  `amount` varchar(256) DEFAULT NULL,
  `date` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_expenses
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for pro_notes
-- ----------------------------
DROP TABLE IF EXISTS `pro_notes`;
CREATE TABLE `pro_notes` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `assign_to` varchar(64) DEFAULT NULL,
  `pro_id` int(14) NOT NULL,
  `details` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_notes
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for pro_task
-- ----------------------------
DROP TABLE IF EXISTS `pro_task`;
CREATE TABLE `pro_task` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `pro_id` int(14) NOT NULL,
  `task_title` varchar(256) DEFAULT NULL,
  `start_date` varchar(128) DEFAULT NULL,
  `end_date` varchar(128) DEFAULT NULL,
  `image` varchar(128) DEFAULT NULL,
  `description` varchar(2048) DEFAULT NULL,
  `task_type` enum('Office','Field') NOT NULL DEFAULT 'Office',
  `status` enum('running','complete','cancel') DEFAULT 'running',
  `location` varchar(512) DEFAULT NULL,
  `return_date` varchar(128) DEFAULT NULL,
  `total_days` varchar(128) DEFAULT NULL,
  `create_date` varchar(128) DEFAULT NULL,
  `approve_status` enum('Approved','Not Approve','Rejected') NOT NULL DEFAULT 'Not Approve',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_task
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for pro_task_assets
-- ----------------------------
DROP TABLE IF EXISTS `pro_task_assets`;
CREATE TABLE `pro_task_assets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pro_task_id` int(11) NOT NULL,
  `assign_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of pro_task_assets
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for reimbursement
-- ----------------------------
DROP TABLE IF EXISTS `reimbursement`;
CREATE TABLE `reimbursement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_id` int(11) DEFAULT NULL,
  `em_id` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(64) DEFAULT 'Umum',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `receipt_image` varchar(255) DEFAULT NULL,
  `description` text,
  `ai_analysis` text,
  `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of reimbursement
-- ----------------------------
BEGIN;
INSERT INTO `reimbursement` (`id`, `batch_id`, `em_id`, `title`, `category`, `amount`, `receipt_image`, `description`, `ai_analysis`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Doe1753', 'Tol Kunciran 2 & Tomang', 'Transportasi', '18500.00', 'receipt_1786418635_8196.jpg', '2x Transaksi Gerbang Tol - Kunciran 2 (Rp 8.000) dan Tomang (Rp 10.500), Golongan 1, e-Toll Mandiri, tanggal 25 Agustus 2024', '{"merchant":"PT Jasamarga Indonesia Highway Corporation","title":"Tol Kunciran 2 & Tomang","amount":18500,"date":"2024-08-25","category":"Transportasi","description":"2x Transaksi Gerbang Tol - Kunciran 2 (Rp 8.000) dan Tomang (Rp 10.500), Golongan 1, e-Toll Mandiri, tanggal 25 Agustus 2024"}', 'PENDING', '2026-08-11 10:33:15', '2026-08-11 10:33:15'),
(2, 1, 'Doe1753', 'Bensin Pertalite', 'Transportasi', '500000.00', 'receipt_1786418704_9927.jpeg', 'Pembelian BBM Pertalite volume 50 Liter @Rp 10.000/Liter, plat nomor F 8757NG, waktu 22:00:27', '{"merchant":"Pertamina SPBU H.IR Juanda","title":"Bensin Pertalite","amount":500000,"date":"2023-10-12","category":"Transportasi","description":"Pembelian BBM Pertalite volume 50 Liter @Rp 10.000/Liter, plat nomor F 8757NG, waktu 22:00:27"}', 'PENDING', '2026-08-11 10:33:15', '2026-08-11 10:33:15');
COMMIT;

-- ----------------------------
-- Table structure for reimbursement_batch
-- ----------------------------
DROP TABLE IF EXISTS `reimbursement_batch`;
CREATE TABLE `reimbursement_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch_code` varchar(64) NOT NULL,
  `em_id` varchar(64) NOT NULL,
  `batch_title` varchar(255) NOT NULL,
  `total_items` int(11) NOT NULL DEFAULT '0',
  `grand_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `batch_code` (`batch_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of reimbursement_batch
-- ----------------------------
BEGIN;
INSERT INTO `reimbursement_batch` (`id`, `batch_code`, `em_id`, `batch_title`, `total_items`, `grand_total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'RB-20260811-9994', 'Doe1753', 'Klaim Operasional Mingguan - 11 Aug 2026', 2, '518500.00', 'PENDING', '2026-08-11 10:33:15', '2026-08-11 10:33:15');
COMMIT;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of roles
-- ----------------------------
BEGIN;
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`) VALUES
(1, 'Super Admin', 'super_admin', 'Full system administrative access', '2026-08-11 21:35:57'),
(2, 'HR Manager', 'hr_manager', 'Manage HR operations, employees, and leaves', '2026-08-11 21:35:57'),
(3, 'HR Staff', 'hr_staff', 'Perform daily HR tasks and view employee records', '2026-08-11 21:35:57'),
(4, 'Finance / Payroll', 'finance', 'Manage salaries, payroll, and financial reports', '2026-08-11 21:35:57'),
(5, 'Department Manager', 'dept_manager', 'Manage team members within assigned department', '2026-08-11 21:35:57'),
(6, 'Employee', 'employee', 'Standard employee self-service access', '2026-08-11 21:35:57');
COMMIT;

-- ----------------------------
-- Table structure for role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of role_permissions
-- ----------------------------
BEGIN;
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(4, 1),
(1, 2),
(4, 2),
(1, 3),
(2, 3),
(3, 3),
(5, 3),
(1, 4),
(2, 4),
(1, 5),
(2, 5),
(3, 5),
(5, 5),
(6, 5),
(1, 6),
(2, 6),
(5, 6),
(1, 7),
(2, 7),
(3, 7),
(5, 7),
(6, 7),
(1, 8),
(1, 9),
(2, 9);
COMMIT;

-- ----------------------------
-- Table structure for salary_type
-- ----------------------------
DROP TABLE IF EXISTS `salary_type`;
CREATE TABLE `salary_type` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `salary_type` varchar(256) DEFAULT NULL,
  `create_date` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of salary_type
-- ----------------------------
BEGIN;
INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES
(1, 'Hourly', '2017-11-22'),
(2, 'Monthly', '2017-12-30'),
(3, 'hhfgf', '2017-12-29'),
(4, 'Hourly', '2018-03-31');
COMMIT;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sitelogo` varchar(128) DEFAULT NULL,
  `sitetitle` varchar(256) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `copyright` varchar(128) DEFAULT NULL,
  `contact` varchar(128) DEFAULT NULL,
  `currency` varchar(128) DEFAULT NULL,
  `symbol` varchar(64) DEFAULT NULL,
  `system_email` varchar(128) DEFAULT NULL,
  `address` varchar(256) DEFAULT NULL,
  `address2` varchar(256) NOT NULL,
  `bpjs_jp_cap` double NOT NULL DEFAULT '10042300',
  `bpjs_kes_cap` double NOT NULL DEFAULT '12000000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
BEGIN;
INSERT INTO `settings` (`id`, `sitelogo`, `sitetitle`, `description`, `copyright`, `contact`, `currency`, `symbol`, `system_email`, `address`, `address2`, `bpjs_jp_cap`, `bpjs_kes_cap`) VALUES
(1, 'HRPAYROLL11.png', 'Development Research Initiative (dRi)', 'Prochesta Foundation aims at the upliftment & betterment of people living below the poverty line.', 'GenIT Bangladesh', '017112233445', 'BDT', '$', 'contact@dri-int.org', 'House 39/7 (First Floor) Hazi Ali Hossain Road', 'Dhaka', 10042300, 12000000);
COMMIT;

-- ----------------------------
-- Table structure for social_media
-- ----------------------------
DROP TABLE IF EXISTS `social_media`;
CREATE TABLE `social_media` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `emp_id` varchar(64) DEFAULT NULL,
  `facebook` varchar(256) DEFAULT NULL,
  `twitter` varchar(256) DEFAULT NULL,
  `google_plus` varchar(512) DEFAULT NULL,
  `skype_id` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of social_media
-- ----------------------------
-- (0 records)

-- ----------------------------
-- Table structure for ter_pph21
-- ----------------------------
DROP TABLE IF EXISTS `ter_pph21`;
CREATE TABLE `ter_pph21` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('A','B','C') NOT NULL,
  `tier` int(11) NOT NULL,
  `min_gross` decimal(15,2) NOT NULL,
  `max_gross` decimal(15,2) NOT NULL,
  `rate` decimal(6,4) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ter_pph21
-- ----------------------------
BEGIN;
INSERT INTO `ter_pph21` (`id`, `category`, `tier`, `min_gross`, `max_gross`, `rate`, `created_at`) VALUES
(1, 'A', 1, '0.00', '5400000.00', '0.0000', '2026-07-30 12:07:45'),
(2, 'A', 2, '5400001.00', '5650000.00', '0.0025', '2026-07-30 12:07:45'),
(3, 'A', 3, '5650001.00', '5950000.00', '0.0050', '2026-07-30 12:07:45'),
(4, 'A', 4, '5950001.00', '6300000.00', '0.0075', '2026-07-30 12:07:45'),
(5, 'A', 5, '6300001.00', '6750000.00', '0.0100', '2026-07-30 12:07:45'),
(6, 'A', 6, '6750001.00', '7500000.00', '0.0125', '2026-07-30 12:07:45'),
(7, 'A', 7, '7500001.00', '8550000.00', '0.0150', '2026-07-30 12:07:45'),
(8, 'A', 8, '8550001.00', '9650000.00', '0.0175', '2026-07-30 12:07:45'),
(9, 'A', 9, '9650001.00', '10050000.00', '0.0200', '2026-07-30 12:07:45'),
(10, 'A', 10, '10050001.00', '10350000.00', '0.0225', '2026-07-30 12:07:45'),
(11, 'A', 11, '10350001.00', '10700000.00', '0.0250', '2026-07-30 12:07:45'),
(12, 'A', 12, '10700001.00', '11050000.00', '0.0300', '2026-07-30 12:07:45'),
(13, 'A', 13, '11050001.00', '11600000.00', '0.0350', '2026-07-30 12:07:45'),
(14, 'A', 14, '11600001.00', '12500000.00', '0.0400', '2026-07-30 12:07:45'),
(15, 'A', 15, '12500001.00', '13750000.00', '0.0500', '2026-07-30 12:07:45'),
(16, 'A', 16, '13750001.00', '15100000.00', '0.0600', '2026-07-30 12:07:45'),
(17, 'A', 17, '15100001.00', '16950000.00', '0.0700', '2026-07-30 12:07:45'),
(18, 'A', 18, '16950001.00', '19750000.00', '0.0800', '2026-07-30 12:07:45'),
(19, 'A', 19, '19750001.00', '24150000.00', '0.0900', '2026-07-30 12:07:45'),
(20, 'A', 20, '24150001.00', '26450000.00', '0.1000', '2026-07-30 12:07:45'),
(21, 'A', 21, '26450001.00', '28000000.00', '0.1100', '2026-07-30 12:07:45'),
(22, 'A', 22, '28000001.00', '30050000.00', '0.1200', '2026-07-30 12:07:45'),
(23, 'A', 23, '30050001.00', '32400000.00', '0.1300', '2026-07-30 12:07:45'),
(24, 'A', 24, '32400001.00', '35400000.00', '0.1400', '2026-07-30 12:07:45'),
(25, 'A', 25, '35400001.00', '39100000.00', '0.1500', '2026-07-30 12:07:45'),
(26, 'A', 26, '39100001.00', '43850000.00', '0.1600', '2026-07-30 12:07:45'),
(27, 'A', 27, '43850001.00', '47800000.00', '0.1700', '2026-07-30 12:07:45'),
(28, 'A', 28, '47800001.00', '51400000.00', '0.1800', '2026-07-30 12:07:45'),
(29, 'A', 29, '51400001.00', '56300000.00', '0.1900', '2026-07-30 12:07:45'),
(30, 'A', 30, '56300001.00', '62200000.00', '0.2000', '2026-07-30 12:07:45'),
(31, 'A', 31, '62200001.00', '68600000.00', '0.2100', '2026-07-30 12:07:45'),
(32, 'A', 32, '68600001.00', '77500000.00', '0.2200', '2026-07-30 12:07:45'),
(33, 'A', 33, '77500001.00', '89000000.00', '0.2300', '2026-07-30 12:07:45'),
(34, 'A', 34, '89000001.00', '103000000.00', '0.2400', '2026-07-30 12:07:45'),
(35, 'A', 35, '103000001.00', '125000000.00', '0.2500', '2026-07-30 12:07:45'),
(36, 'A', 36, '125000001.00', '157000000.00', '0.2600', '2026-07-30 12:07:45'),
(37, 'A', 37, '157000001.00', '206000000.00', '0.2700', '2026-07-30 12:07:45'),
(38, 'A', 38, '206000001.00', '337000000.00', '0.2800', '2026-07-30 12:07:45'),
(39, 'A', 39, '337000001.00', '454000000.00', '0.2900', '2026-07-30 12:07:45'),
(40, 'A', 40, '454000001.00', '550000000.00', '0.3000', '2026-07-30 12:07:45'),
(41, 'A', 41, '550000001.00', '695000000.00', '0.3100', '2026-07-30 12:07:45'),
(42, 'A', 42, '695000001.00', '910000000.00', '0.3200', '2026-07-30 12:07:45'),
(43, 'A', 42, '910000001.00', '1400000000.00', '0.3300', '2026-07-30 12:07:45'),
(44, 'A', 43, '1400000001.00', '999999999999.00', '0.3400', '2026-07-30 12:07:45'),
(45, 'B', 1, '0.00', '6200000.00', '0.0000', '2026-07-30 12:07:45'),
(46, 'B', 2, '6200001.00', '6500000.00', '0.0025', '2026-07-30 12:07:45'),
(47, 'B', 3, '6500001.00', '6850000.00', '0.0050', '2026-07-30 12:07:45'),
(48, 'B', 4, '6850001.00', '7300000.00', '0.0075', '2026-07-30 12:07:45'),
(49, 'B', 5, '7300001.00', '9200000.00', '0.0100', '2026-07-30 12:07:45'),
(50, 'B', 6, '9200001.00', '10750000.00', '0.0150', '2026-07-30 12:07:45'),
(51, 'B', 7, '10750001.00', '11250000.00', '0.0200', '2026-07-30 12:07:45'),
(52, 'B', 8, '11250001.00', '11600000.00', '0.0250', '2026-07-30 12:07:45'),
(53, 'B', 9, '11600001.00', '12600000.00', '0.0300', '2026-07-30 12:07:45'),
(54, 'B', 10, '12600001.00', '13600000.00', '0.0400', '2026-07-30 12:07:45'),
(55, 'B', 11, '13600001.00', '14950000.00', '0.0500', '2026-07-30 12:07:45'),
(56, 'B', 12, '14950001.00', '16400000.00', '0.0600', '2026-07-30 12:07:45'),
(57, 'B', 13, '16400001.00', '18450000.00', '0.0700', '2026-07-30 12:07:45'),
(58, 'B', 14, '18450001.00', '21850000.00', '0.0800', '2026-07-30 12:07:45'),
(59, 'B', 15, '21850001.00', '26000000.00', '0.0900', '2026-07-30 12:07:45'),
(60, 'B', 16, '26000001.00', '27700000.00', '0.1000', '2026-07-30 12:07:45'),
(61, 'B', 17, '27700001.00', '29350000.00', '0.1100', '2026-07-30 12:07:45'),
(62, 'B', 18, '29350001.00', '31450000.00', '0.1200', '2026-07-30 12:07:45'),
(63, 'B', 19, '31450001.00', '33950000.00', '0.1300', '2026-07-30 12:07:45'),
(64, 'B', 20, '33950001.00', '37100000.00', '0.1400', '2026-07-30 12:07:45'),
(65, 'B', 21, '37100001.00', '41100000.00', '0.1500', '2026-07-30 12:07:45'),
(66, 'B', 22, '41100001.00', '45800000.00', '0.1600', '2026-07-30 12:07:45'),
(67, 'B', 23, '45800001.00', '49500000.00', '0.1700', '2026-07-30 12:07:45'),
(68, 'B', 24, '49500001.00', '53800000.00', '0.1800', '2026-07-30 12:07:45'),
(69, 'B', 25, '53800001.00', '58500000.00', '0.1900', '2026-07-30 12:07:45'),
(70, 'B', 26, '58500001.00', '64000000.00', '0.2000', '2026-07-30 12:07:45'),
(71, 'B', 27, '64000001.00', '71000000.00', '0.2100', '2026-07-30 12:07:45'),
(72, 'B', 28, '71000001.00', '80000000.00', '0.2200', '2026-07-30 12:07:45'),
(73, 'B', 29, '80000001.00', '93000000.00', '0.2300', '2026-07-30 12:07:45'),
(74, 'B', 30, '93000001.00', '109000000.00', '0.2400', '2026-07-30 12:07:45'),
(75, 'B', 31, '109000001.00', '129000000.00', '0.2500', '2026-07-30 12:07:45'),
(76, 'B', 32, '129000001.00', '163000000.00', '0.2600', '2026-07-30 12:07:45'),
(77, 'B', 33, '163000001.00', '211000000.00', '0.2700', '2026-07-30 12:07:45'),
(78, 'B', 34, '211000001.00', '374000000.00', '0.2800', '2026-07-30 12:07:45'),
(79, 'B', 35, '374000001.00', '459000000.00', '0.2900', '2026-07-30 12:07:45'),
(80, 'B', 36, '459000001.00', '555000000.00', '0.3000', '2026-07-30 12:07:45'),
(81, 'B', 37, '555000001.00', '704000000.00', '0.3100', '2026-07-30 12:07:45'),
(82, 'B', 38, '704000001.00', '957000000.00', '0.3200', '2026-07-30 12:07:45'),
(83, 'B', 39, '957000001.00', '1405000000.00', '0.3300', '2026-07-30 12:07:45'),
(84, 'B', 40, '1405000001.00', '999999999999.00', '0.3400', '2026-07-30 12:07:45'),
(85, 'C', 1, '0.00', '6600000.00', '0.0000', '2026-07-30 12:07:45'),
(86, 'C', 2, '6600001.00', '6950000.00', '0.0025', '2026-07-30 12:07:45'),
(87, 'C', 3, '6950001.00', '7350000.00', '0.0050', '2026-07-30 12:07:45'),
(88, 'C', 4, '7350001.00', '7800000.00', '0.0075', '2026-07-30 12:07:45'),
(89, 'C', 5, '7800001.00', '8850000.00', '0.0100', '2026-07-30 12:07:45'),
(90, 'C', 6, '8850001.00', '9800000.00', '0.0125', '2026-07-30 12:07:45'),
(91, 'C', 7, '9800001.00', '10950000.00', '0.0150', '2026-07-30 12:07:45'),
(92, 'C', 8, '10950001.00', '11200000.00', '0.0175', '2026-07-30 12:07:45'),
(93, 'C', 9, '11200001.00', '12050000.00', '0.0200', '2026-07-30 12:07:45'),
(94, 'C', 10, '12050001.00', '12950000.00', '0.0300', '2026-07-30 12:07:45'),
(95, 'C', 11, '12950001.00', '14150000.00', '0.0400', '2026-07-30 12:07:45'),
(96, 'C', 12, '14150001.00', '15550000.00', '0.0500', '2026-07-30 12:07:45'),
(97, 'C', 13, '15550001.00', '17050000.00', '0.0600', '2026-07-30 12:07:45'),
(98, 'C', 14, '17050001.00', '19500000.00', '0.0700', '2026-07-30 12:07:45'),
(99, 'C', 15, '19500001.00', '22700000.00', '0.0800', '2026-07-30 12:07:45'),
(100, 'C', 16, '22700001.00', '26600000.00', '0.0900', '2026-07-30 12:07:45'),
(101, 'C', 17, '26600001.00', '28100000.00', '0.1000', '2026-07-30 12:07:45'),
(102, 'C', 18, '28100001.00', '30100000.00', '0.1100', '2026-07-30 12:07:45'),
(103, 'C', 19, '30100001.00', '32600000.00', '0.1200', '2026-07-30 12:07:45'),
(104, 'C', 20, '32600001.00', '35400000.00', '0.1300', '2026-07-30 12:07:45'),
(105, 'C', 21, '35400001.00', '38900000.00', '0.1400', '2026-07-30 12:07:45'),
(106, 'C', 22, '38900001.00', '43000000.00', '0.1500', '2026-07-30 12:07:45'),
(107, 'C', 23, '43000001.00', '47400000.00', '0.1600', '2026-07-30 12:07:45'),
(108, 'C', 24, '47400001.00', '51200000.00', '0.1700', '2026-07-30 12:07:45'),
(109, 'C', 25, '51200001.00', '55800000.00', '0.1800', '2026-07-30 12:07:45'),
(110, 'C', 26, '55800001.00', '60400000.00', '0.1900', '2026-07-30 12:07:45'),
(111, 'C', 27, '60400001.00', '66700000.00', '0.2000', '2026-07-30 12:07:45'),
(112, 'C', 28, '66700001.00', '74500000.00', '0.2100', '2026-07-30 12:07:45'),
(113, 'C', 29, '74500001.00', '83200000.00', '0.2200', '2026-07-30 12:07:45'),
(114, 'C', 30, '83200001.00', '95600000.00', '0.2300', '2026-07-30 12:07:45'),
(115, 'C', 31, '95600001.00', '110000000.00', '0.2400', '2026-07-30 12:07:45'),
(116, 'C', 32, '110000001.00', '134000000.00', '0.2500', '2026-07-30 12:07:45'),
(117, 'C', 33, '134000001.00', '169000000.00', '0.2600', '2026-07-30 12:07:45'),
(118, 'C', 34, '169000001.00', '221000000.00', '0.2700', '2026-07-30 12:07:45'),
(119, 'C', 35, '221000001.00', '390000000.00', '0.2800', '2026-07-30 12:07:45'),
(120, 'C', 36, '390000001.00', '463000000.00', '0.2900', '2026-07-30 12:07:45'),
(121, 'C', 37, '463000001.00', '561000000.00', '0.3000', '2026-07-30 12:07:45'),
(122, 'C', 38, '561000001.00', '709000000.00', '0.3100', '2026-07-30 12:07:45'),
(123, 'C', 39, '709000001.00', '965000000.00', '0.3200', '2026-07-30 12:07:45'),
(124, 'C', 40, '965000001.00', '1419000000.00', '0.3300', '2026-07-30 12:07:45'),
(125, 'C', 41, '1419000001.00', '999999999999.00', '0.3400', '2026-07-30 12:07:45');
COMMIT;

-- ----------------------------
-- Table structure for to-do_list
-- ----------------------------
DROP TABLE IF EXISTS `to-do_list`;
CREATE TABLE `to-do_list` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(64) DEFAULT NULL,
  `to_dodata` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(128) DEFAULT NULL,
  `value` varchar(14) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of to-do_list
-- ----------------------------
-- (0 records)

SET FOREIGN_KEY_CHECKS = 1;
