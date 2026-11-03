-- ═══════════════════════════════════════════════════════════════════════════
-- HRMS Face Recognition — Migration 004: RISKY Attendance Changes
-- ═══════════════════════════════════════════════════════════════════════════
-- ⚠️⚠️⚠️ DO NOT RUN THIS AUTOMATICALLY ⚠️⚠️⚠️
-- 
-- This migration changes column types on the production attendance table
-- which is used for payroll. Run tools/preflight_attendance.php FIRST
-- to identify data issues, then apply manually after resolving them.
--
-- REQUIRED BEFORE RUNNING:
-- 1. Full database backup
-- 2. Run tools/preflight_attendance.php and resolve all issues
-- 3. Test on staging/clone database first
-- 4. Schedule maintenance window
-- ═══════════════════════════════════════════════════════════════════════════

-- Step 1: Fix invalid date formats (run preflight first to see which rows)
-- UPDATE attendance SET atten_date = STR_TO_DATE(atten_date, '%Y-%m-%d') 
--   WHERE atten_date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$';

-- Step 2: Change atten_date from VARCHAR to DATE
-- ALTER TABLE attendance MODIFY COLUMN atten_date DATE NOT NULL;

-- Step 3: Add unique constraint (resolve duplicates first!)
-- ALTER TABLE attendance ADD UNIQUE KEY uq_emp_date (emp_id, atten_date);

-- Step 4: Change charset to utf8mb4
-- ALTER TABLE attendance CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
