-- ==========================================
-- HRMS Matrix RBAC (Roles, Permissions & Scopes)
-- Database Schema & Migration Script
-- ==========================================

-- 1. Table: roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `module` VARCHAR(50) NOT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table: role_permissions (Pivot)
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table: employee_roles (User Role & Scope assignment)
CREATE TABLE IF NOT EXISTS `employee_roles` (
  `employee_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  `scope` ENUM('SELF', 'SUBORDINATE', 'DEPARTMENT', 'ORGANIZATION') NOT NULL DEFAULT 'SELF',
  PRIMARY KEY (`employee_id`, `role_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- SEED DEFAULT ROLES
-- ==========================================
INSERT IGNORE INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Super Admin', 'super_admin', 'Full system administrative access'),
(2, 'HR Manager', 'hr_manager', 'Manage HR operations, employees, and leaves'),
(3, 'HR Staff', 'hr_staff', 'Perform daily HR tasks and view employee records'),
(4, 'Finance / Payroll', 'finance', 'Manage salaries, payroll, and financial reports'),
(5, 'Department Manager', 'dept_manager', 'Manage team members within assigned department'),
(6, 'Employee', 'employee', 'Standard employee self-service access');

-- ==========================================
-- SEED DEFAULT PERMISSIONS
-- ==========================================
INSERT IGNORE INTO `permissions` (`id`, `name`, `slug`, `module`, `description`) VALUES
-- Payroll Module
(1, 'View Payroll', 'payroll.view', 'payroll', 'View salary slips and payroll history'),
(2, 'Manage Payroll', 'payroll.manage', 'payroll', 'Process payroll, edit salary components'),
-- Employee Module
(3, 'View Employee', 'employee.view', 'employee', 'View employee list and details'),
(4, 'Manage Employee', 'employee.manage', 'employee', 'Add, edit, or terminate employee accounts'),
-- Attendance & Leave Module
(5, 'View Attendance', 'attendance.view', 'attendance', 'View attendance logs'),
(6, 'Approve Leave', 'leave.approve', 'leave', 'Approve or reject leave requests'),
(7, 'Apply Leave', 'leave.apply', 'leave', 'Submit leave requests'),
-- System Settings
(8, 'Manage System Settings', 'settings.manage', 'system', 'Configure system parameters and RBAC');

-- ==========================================
-- SEED ROLE PERMISSIONS
-- ==========================================
-- Super Admin: All permissions
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7), (1,8);

-- HR Manager: Employee, Attendance, Leave, View Payroll
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(2,1), (2,3), (2,4), (2,5), (2,6), (2,7);

-- HR Staff: View Employee, View Attendance, Apply Leave
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(3,3), (3,5), (3,7);

-- Finance: Payroll view & manage
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(4,1), (4,2);

-- Dept Manager: View Employee, View Attendance, Approve Leave, Apply Leave
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(5,3), (5,5), (5,6), (5,7);

-- Employee: Apply Leave, View Attendance (Self)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(6,5), (6,7);
