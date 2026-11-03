-- =======================================================
-- Migration Script: Add Division Table, Employee Columns, 
-- Designations, and Seed Digital Production Division Employees
-- =======================================================

-- 1. Create table 'division'
CREATE TABLE IF NOT EXISTS `division` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `div_code` VARCHAR(32) DEFAULT NULL,
  `div_name` VARCHAR(128) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Division records (Code 05 and 07)
INSERT INTO `division` (`id`, `div_code`, `div_name`) VALUES
(5, '05', 'DIGITAL PRODUCTION DIVISION'),
(7, '07', 'DIGITAL PRODUCTION DIVISION')
ON DUPLICATE KEY UPDATE `div_code` = VALUES(`div_code`), `div_name` = VALUES(`div_name`);

-- 2. Add columns to 'employee' if not exists
SET @dbname = DATABASE();
SET @tablename = "employee";

-- emp_no (Nomor urut dari Excel / data master)
SET @prepexists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'emp_no');
SET @query = IF(@prepexists = 0, 'ALTER TABLE `employee` ADD COLUMN `emp_no` INT(11) NULL DEFAULT NULL AFTER `id`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- division_id (Relasi ke table division)
SET @prepexists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'division_id');
SET @query = IF(@prepexists = 0, 'ALTER TABLE `employee` ADD COLUMN `division_id` INT(11) NULL DEFAULT NULL AFTER `dep_id`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- birth_place (Tempat Lahir)
SET @prepexists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'birth_place');
SET @query = IF(@prepexists = 0, 'ALTER TABLE `employee` ADD COLUMN `birth_place` VARCHAR(128) NULL DEFAULT NULL AFTER `em_birthday`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ttl (Tempat, Tanggal Lahir lengkap)
SET @prepexists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = 'ttl');
SET @query = IF(@prepexists = 0, 'ALTER TABLE `employee` ADD COLUMN `ttl` VARCHAR(255) NULL DEFAULT NULL AFTER `birth_place`', 'SELECT 1');
PREPARE stmt FROM @query; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3. Seed Designations (Jabatan)
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Manager' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Manager');
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Supervisor' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Supervisor');
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Video Editor' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Video Editor');
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Programmer' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Programmer');
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Cameraman' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Cameraman');
INSERT INTO `designation` (`des_name`)
SELECT 'Digital Production Staff' WHERE NOT EXISTS (SELECT 1 FROM `designation` WHERE `des_name` = 'Digital Production Staff');

-- 4. Seed Department
INSERT INTO `department` (`dep_name`)
SELECT 'Digital Production' WHERE NOT EXISTS (SELECT 1 FROM `department` WHERE `dep_name` = 'Digital Production');

-- 5. Insert Employee Records with Login Credentials (default password: admin123456 -> SHA1: cd5ea73cd58f827fa78eef7197b8ee606c99b2e6)

-- Row 1: Edi Pamungkas (No 15)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  15, 'Pam2009', '20091105', 'Edi', 'Pamungkas',
  'edipamungkas@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'ADMIN', 'ACTIVE', 'Male',
  '0812-1059-117', '1969-07-05', 'Salatiga', 'Salatiga, 05 Juli 1969', '2009-11-23',
  'Pondok Jati Indah Blok A2-1 Jatikramat Jatiasih Bekasi',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Manager' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20091105');

-- Row 2: Muhamad Arif Erlangga (No 16)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  16, 'Erl2015', '20150605', 'Muhamad Arif', 'Erlangga',
  'ariferlangga@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '0812-1213-3663', '1975-06-11', 'Jakarta', 'Jakarta, 11 Juni 1975', '2015-06-08',
  'Jl. Palem Raya No.9 Rt.001 Rw.008 Petukangan Utara Jakarta Selatan',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Supervisor' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20150605');

-- Row 3: Hari Windariyadi (No 17)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  17, 'Win2014', '20140705', 'Hari', 'Windariyadi',
  'hariwindariyadi@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '0818-8084-94393', '1976-07-10', 'Lumajang', 'Lumajang, 10 Juli 1976', '2014-07-01',
  'Perumahan Griya Citra Kayumanis Blok B No.14 Jl. Raya Kencana 2 Mekarwangi Tanah Sereal Bogor',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Video Editor' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20140705');

-- Row 4: Ahmad Ridwan Elgifary (No 18)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  18, 'Elg2015', '20150305', 'Ahmad Ridwan', 'Elgifary',
  'ahmadelgifary@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '0878-7867-9390', '1977-06-24', 'Jakarta', 'Jakarta, 24 Juni 1977', '2015-03-02',
  'Jl. Lurah Ripan / Gudang Kapas No.12 Rt.003/Rw.017 Cilangkap Tapos Kota Depok',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Video Editor' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20150305');

-- Row 6: Bramastyo Kusumo (No 20)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  20, 'Kus2020', '20201205', 'Bramastyo', 'Kusumo',
  'bramastyokusumo@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '0821-1113-1902', '1992-05-03', 'Mojokerto', 'Mojokerto, 3 Mei 1992', '2020-12-01',
  'Jl. Kalimalang Bungur No.17 Harapan Jaya Bekasi Utara',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Programmer' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20201205');

-- Row 7: Aris Afrianto (No 21)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  21, 'Afr2021', '20210105', 'Aris', 'Afrianto',
  'arisafrianto@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '0822-1876-9546', '1996-04-23', 'Cirebon', 'Cirebon, 23 April 1996', '2021-01-04',
  'Jl. Angsana Kel. Utan Kayu Utara Matraman Jakarta Timur',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Programmer' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20210105');

-- Row 8: Muhammad Kamil Idris (No 22)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  22, 'Idr2024', '20240805', 'Muhammad Kamil', 'Idris',
  'kamilidris@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '08215477944', '1999-10-01', 'Jakarta', 'Jakarta, 01 Oktober 1999', '2024-08-12',
  'Jl. Kampung Duri Dalam No.13 Rt.010 RW.003 Kel. Duri Selatan Kec. Tambora Jakarta Barat',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  5,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Cameraman' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20240805');

-- Row 9: Akhbar Bhakti Banuadji (No 36)
INSERT INTO `employee` (
  `emp_no`, `em_id`, `em_code`, `first_name`, `last_name`, 
  `em_email`, `em_password`, `em_role`, `status`, `em_gender`, 
  `em_phone`, `em_birthday`, `birth_place`, `ttl`, `em_joining_date`, 
  `em_address`, `dep_id`, `division_id`, `des_id`
)
SELECT 
  36, 'Ban2012', '20120307', 'Akhbar Bhakti', 'Banuadji',
  'akhbarbanuadji@email.com', 'cd5ea73cd58f827fa78eef7197b8ee606c99b2e6', 'EMPLOYEE', 'ACTIVE', 'Male',
  '021-86611218', '1978-12-29', 'Jakarta', 'Jakarta, 29 Desember 1978', '2012-03-01',
  'Jl. Pondok Bambu Asri Blok A2 No.9 Pondok Bambu Jakarta Timur',
  (SELECT `id` FROM `department` WHERE `dep_name` = 'Digital Production' LIMIT 1),
  7,
  (SELECT `id` FROM `designation` WHERE `des_name` = 'Digital Production Staff' LIMIT 1)
WHERE NOT EXISTS (SELECT 1 FROM `employee` WHERE `em_code` = '20120307');

-- 6. Link to employee_roles for RBAC if table exists
INSERT IGNORE INTO `employee_roles` (`employee_id`, `role_id`, `scope`)
SELECT `id`, IF(`em_role` = 'ADMIN', 5, 6), IF(`em_role` = 'ADMIN', 'DEPARTMENT', 'SELF')
FROM `employee`
WHERE `em_code` IN ('20091105','20150605','20140705','20150305','20180405','20201205','20210105','20240805','20120307');
