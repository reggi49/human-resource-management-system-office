/*
 Navicat Premium Data Transfer

 Source Server         : lokal
 Source Server Type    : MySQL
 Source Server Version : 100421 (10.4.21-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : gen-hr

 Target Server Type    : MySQL
 Target Server Version : 100421 (10.4.21-MariaDB)
 File Encoding         : 65001

 Date: 23/11/2024 09:54:13
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of addition
-- ----------------------------
BEGIN;
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of address
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assets
-- ----------------------------
BEGIN;
COMMIT;

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
INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES (1, 'ASSETS', 'TAB');
INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES (2, 'ASSETS', 'Computer');
INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES (3, 'ASSETS', 'Laptop');
INSERT INTO `assets_category` (`cat_id`, `cat_status`, `cat_name`) VALUES (4, 'LOGISTIC', 'tab');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assign_leave
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assign_task
-- ----------------------------
BEGIN;
COMMIT;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1012 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attendance
-- ----------------------------
BEGIN;
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (993, '4', '2018-04-09', '11:00:00', '19:29:00', '247 min', 'office', '232 min', '149 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (994, '20', '2018-04-12', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (995, '20', '2018-04-13', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (996, '20', '2018-04-14', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (997, '20', '2018-04-15', '09:00:00', '17:00:00', '480', '', NULL, NULL, NULL, 'E');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (998, '2', '2018-02-20', '10:00:00', '17:00:00', '0 min', 'office', '429 min', '0 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (999, '3', '2018-02-21', '09:00:00', '18:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1000, '4', '2018-02-22', '09:00:00', '19:00:00', '247 min', 'office', '232 min', '149 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1001, '12', '2018-03-02', '01:00:00', '03:00:00', '396 min', 'office', '83 min', '142 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1002, '13', '2018-03-03', '02:00:00', '04:00:00', '480 min', 'office', '0 min', '34 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1003, '14', '2018-03-04', '03:00:00', '05:00:00', '399 min', 'office', '80 min', '152 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1004, '15', '2018-03-05', '04:00:00', '06:00:00', '480 min', 'office', '0 min', '133 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1005, '16', '2018-03-06', '05:00:00', '07:00:00', '0 min', 'office', '480 min', '0 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1006, '17', '2018-03-07', '06:00:00', '08:00:00', '480 min', 'office', '0 min', '286 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1007, '18', '2018-03-08', '07:00:00', '09:00:00', '431 min', 'office', '0 min', '140 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1008, '19', '2018-03-09', '08:00:00', '10:00:00', '377 min', 'office', '102 min', '192 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1009, '24', '2018-03-14', '01:00:00', '15:00:00', '433 min', 'office', '0 min', '140 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1010, '25', '2018-03-15', '02:00:00', '16:00:00', '480 min', 'office', '0 min', '0 min', NULL, 'A');
INSERT INTO `attendance` (`id`, `emp_id`, `atten_date`, `signin_time`, `signout_time`, `working_hour`, `place`, `absence`, `overtime`, `earnleave`, `status`) VALUES (1011, '26', '2018-02-20', '03:00:00', '17:00:00', '339 min', 'office', '140 min', '178 min', NULL, 'A');
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bank_info
-- ----------------------------
BEGIN;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of deduction
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dep_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of department
-- ----------------------------
BEGIN;
INSERT INTO `department` (`id`, `dep_name`) VALUES (2, 'Administration');
INSERT INTO `department` (`id`, `dep_name`) VALUES (3, 'Finance, HR, & Admininstration');
INSERT INTO `department` (`id`, `dep_name`) VALUES (4, 'Research');
INSERT INTO `department` (`id`, `dep_name`) VALUES (5, 'Information Technology');
INSERT INTO `department` (`id`, `dep_name`) VALUES (6, 'Support');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of desciplinary
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for designation
-- ----------------------------
DROP TABLE IF EXISTS `designation`;
CREATE TABLE `designation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `des_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of designation
-- ----------------------------
BEGIN;
INSERT INTO `designation` (`id`, `des_name`) VALUES (2, 'Vice Chairman');
INSERT INTO `designation` (`id`, `des_name`) VALUES (3, 'Chief Executive Officer (CEO)');
INSERT INTO `designation` (`id`, `des_name`) VALUES (4, 'Chief Finance & Admin Officer');
INSERT INTO `designation` (`id`, `des_name`) VALUES (5, 'Sr. Finance & Admin Officer - I');
INSERT INTO `designation` (`id`, `des_name`) VALUES (6, 'Jr. Finance & Admin Officer');
INSERT INTO `designation` (`id`, `des_name`) VALUES (7, 'Senior Research Associate-1');
INSERT INTO `designation` (`id`, `des_name`) VALUES (8, 'Research Associate-1');
INSERT INTO `designation` (`id`, `des_name`) VALUES (9, 'Junior Research Associate');
INSERT INTO `designation` (`id`, `des_name`) VALUES (10, 'Research Assistant');
INSERT INTO `designation` (`id`, `des_name`) VALUES (11, 'Sr. Office Assistant');
INSERT INTO `designation` (`id`, `des_name`) VALUES (12, 'Office Assistant');
INSERT INTO `designation` (`id`, `des_name`) VALUES (13, 'IT Analyst');
INSERT INTO `designation` (`id`, `des_name`) VALUES (14, 'Cook');
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
INSERT INTO `earned_leave` (`id`, `em_id`, `present_date`, `hour`, `status`) VALUES (26, 'Mir1685', '0', '0', '1');
INSERT INTO `earned_leave` (`id`, `em_id`, `present_date`, `hour`, `status`) VALUES (27, 'Rah1682', '0', '0', '1');
INSERT INTO `earned_leave` (`id`, `em_id`, `present_date`, `hour`, `status`) VALUES (28, 'Doe1754', '3', '100', '1');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of education
-- ----------------------------
BEGIN;
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
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_experience
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of emp_leave
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for emp_penalty
-- ----------------------------
DROP TABLE IF EXISTS `emp_penalty`;
CREATE TABLE `emp_penalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `penalty_id` int(11) NOT NULL,
  `penalty_desc` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_penalty
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of emp_salary
-- ----------------------------
BEGIN;
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
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for employee
-- ----------------------------
DROP TABLE IF EXISTS `employee`;
CREATE TABLE `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `em_id` varchar(64) DEFAULT NULL,
  `em_code` varchar(64) DEFAULT NULL,
  `des_id` int(11) DEFAULT NULL,
  `dep_id` int(11) DEFAULT NULL,
  `first_name` varchar(128) DEFAULT NULL,
  `last_name` varchar(128) DEFAULT NULL,
  `em_email` varchar(64) DEFAULT NULL,
  `em_password` varchar(512) NOT NULL,
  `em_role` enum('ADMIN','EMPLOYEE','SUPER ADMIN') NOT NULL DEFAULT 'EMPLOYEE',
  `em_address` varchar(512) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `em_gender` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `em_phone` varchar(64) DEFAULT NULL,
  `em_birthday` varchar(128) DEFAULT NULL,
  `em_blood_group` enum('O+','O-','A+','A-','B+','B-','AB+','OB+') DEFAULT NULL,
  `em_joining_date` varchar(128) DEFAULT NULL,
  `em_contact_end` varchar(128) DEFAULT NULL,
  `em_image` varchar(128) DEFAULT NULL,
  `em_nid` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of employee
-- ----------------------------
BEGIN;
INSERT INTO `employee` (`id`, `em_id`, `em_code`, `des_id`, `dep_id`, `first_name`, `last_name`, `em_email`, `em_password`, `em_role`, `em_address`, `status`, `em_gender`, `em_phone`, `em_birthday`, `em_blood_group`, `em_joining_date`, `em_contact_end`, `em_image`, `em_nid`) VALUES (10, 'Soy1332', '99', 0, 0, 'Bagus', 'Nagabonar', 'superadmin@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'SUPER ADMIN', NULL, 'ACTIVE', 'Female', '01723177901', '2017-12-26', 'B+', '2018-01-06', '2018-01-06', 'Soy1332.jpg', '132154566556');
INSERT INTO `employee` (`id`, `em_id`, `em_code`, `des_id`, `dep_id`, `first_name`, `last_name`, `em_email`, `em_password`, `em_role`, `em_address`, `status`, `em_gender`, `em_phone`, `em_birthday`, `em_blood_group`, `em_joining_date`, `em_contact_end`, `em_image`, `em_nid`) VALUES (36, 'Doe1753', '123456', 12, 2, 'Gibran', 'Rakamars', 'admin@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'ADMIN', NULL, 'ACTIVE', 'Male', 'admin123456', '2019-02-13', 'O+', '2019-02-15', '2019-02-22', 'Doe1753.jpg', '01253568955555');
INSERT INTO `employee` (`id`, `em_id`, `em_code`, `des_id`, `dep_id`, `first_name`, `last_name`, `em_email`, `em_password`, `em_role`, `em_address`, `status`, `em_gender`, `em_phone`, `em_birthday`, `em_blood_group`, `em_joining_date`, `em_contact_end`, `em_image`, `em_nid`) VALUES (37, 'Doe1754', '123444', 12, 2, 'Dewa', 'Natapraja', 'employee@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', NULL, 'ACTIVE', 'Male', 'abc123456', '2019-02-13', 'O+', '2019-02-15', '2019-02-22', 'Doe1753.jpg', '01253568955555');
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of employee_file
-- ----------------------------
BEGIN;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of field_visit
-- ----------------------------
BEGIN;
COMMIT;

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
INSERT INTO `holiday` (`id`, `holiday_name`, `from_date`, `to_date`, `number_of_days`, `year`) VALUES (1, 'Aids Day', '2017-12-21', '2017-12-29', '2', '2017');
INSERT INTO `holiday` (`id`, `holiday_name`, `from_date`, `to_date`, `number_of_days`, `year`) VALUES (3, 'Language Day', '2018-02-21', '2018-02-21', '1', '02-2018');
INSERT INTO `holiday` (`id`, `holiday_name`, `from_date`, `to_date`, `number_of_days`, `year`) VALUES (4, 'independent day', '2018-03-26', '', '1', '03-2018');
COMMIT;

-- ----------------------------
-- Table structure for leave_types
-- ----------------------------
DROP TABLE IF EXISTS `leave_types`;
CREATE TABLE `leave_types` (
  `type_id` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `leave_day` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of leave_types
-- ----------------------------
BEGIN;
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (1, 'Casual Leave', '21', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (2, 'Sick Leave', '15', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (3, 'Maternity Leave', '90', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (4, 'Paternal Leave', '7', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (5, 'Earned leave', '12', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (7, 'Public Holiday', '0', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (8, 'Optional Leave', '0', 1);
INSERT INTO `leave_types` (`type_id`, `name`, `leave_day`, `status`) VALUES (9, 'Leave without Pay', '12', 1);
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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of loan
-- ----------------------------
BEGIN;
COMMIT;

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
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES (27, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-21', 'dsf dsf ds fds fdsf ds', '1', 'sf ds fsd fds fsd fdsf ds fsd');
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES (28, 33, 'Isl1385', '4008291', '5000', NULL, '2018-04-04', 'dsdsff dsf ds f', '0', 'f dsfdsf dsfs');
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES (29, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-04-05', NULL, '4', NULL);
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES (30, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-05', NULL, '3', NULL);
INSERT INTO `loan_installment` (`id`, `loan_id`, `emp_id`, `loan_number`, `install_amount`, `pay_amount`, `app_date`, `receiver`, `install_no`, `notes`) VALUES (31, 34, 'EMP1254478', '18194827', '5000', NULL, '2018-06-07', NULL, '2', NULL);
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
INSERT INTO `logistic_asset` (`log_id`, `name`, `qty`, `entry_date`) VALUES (1, 'Lubricant', '30', '12/25/17');
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logistic_assign
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file_url` varchar(256) DEFAULT NULL,
  `date` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of notice
-- ----------------------------
BEGIN;
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
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pay_salary
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for penalty
-- ----------------------------
DROP TABLE IF EXISTS `penalty`;
CREATE TABLE `penalty` (
  `id` int(11) NOT NULL,
  `penalty_name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of penalty
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_expenses
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_notes
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pro_task
-- ----------------------------
BEGIN;
COMMIT;

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
BEGIN;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of project
-- ----------------------------
BEGIN;
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project_file
-- ----------------------------
BEGIN;
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
INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES (1, 'Hourly', '2017-11-22');
INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES (2, 'Monthly', '2017-12-30');
INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES (3, 'hhfgf', '2017-12-29');
INSERT INTO `salary_type` (`id`, `salary_type`, `create_date`) VALUES (4, 'Hourly', '2018-03-31');
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
BEGIN;
INSERT INTO `settings` (`id`, `sitelogo`, `sitetitle`, `description`, `copyright`, `contact`, `currency`, `symbol`, `system_email`, `address`, `address2`) VALUES (1, 'HRPAYROLL11.png', 'Bramastyo Dev Ops', 'Semoga maju terus tidak pernah mundur sukses selalu', 'Bramastyo DevOps', '0', 'Rupiah', 'Rp', 'admin@email.xom', 'Jakarta', 'Utara');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of social_media
-- ----------------------------
BEGIN;
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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of to-do_list
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
