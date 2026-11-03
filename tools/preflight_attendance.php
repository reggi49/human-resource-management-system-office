<?php
/**
 * HRMS Face Recognition — Preflight Attendance Check
 * ===================================================
 * READ-ONLY analysis of the attendance table before risky migrations.
 * Reports: invalid dates, duplicates, charset issues.
 * 
 * RUN: php tools/preflight_attendance.php
 * Or via browser: http://yourdomain.com/tools/preflight_attendance.php
 * 
 * ⚠️ This script DOES NOT modify any data.
 */

// Bootstrap CI3 minimally
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SCRIPT_NAME'] = '/index.php';
define('BASEPATH', dirname(__FILE__) . '/../system/');
define('APPPATH', dirname(__FILE__) . '/../application/');
define('FCPATH', dirname(__FILE__) . '/../');
define('ENVIRONMENT', 'production');

// Load database config
require_once APPPATH . 'config/database.php';

$dbconf = $db['default'];
$pdo = new PDO(
    "mysql:host={$dbconf['hostname']};dbname={$dbconf['database']};charset=utf8",
    $dbconf['username'],
    $dbconf['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

echo "═══════════════════════════════════════════════════════════════\n";
echo " HRMS Preflight Attendance Check — READ ONLY\n";
echo " " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// ─── 1. Total rows ───────────────────────────────────────────────────────
$total = $pdo->query("SELECT COUNT(*) FROM attendance")->fetchColumn();
echo "📊 Total attendance rows: {$total}\n\n";

// ─── 2. Check atten_date column type ─────────────────────────────────────
$col_info = $pdo->query("SHOW COLUMNS FROM attendance LIKE 'atten_date'")->fetch(PDO::FETCH_ASSOC);
echo "📋 Column atten_date type: {$col_info['Type']}\n";
$is_varchar = (stripos($col_info['Type'], 'varchar') !== false || stripos($col_info['Type'], 'char') !== false);
echo "   Is VARCHAR: " . ($is_varchar ? "YES — needs migration" : "NO — already DATE type") . "\n\n";

// ─── 3. Invalid date formats ─────────────────────────────────────────────
if ($is_varchar) {
    $invalid_dates = $pdo->query("
        SELECT id, emp_id, atten_date, signin_time, signout_time 
        FROM attendance 
        WHERE atten_date NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
        OR STR_TO_DATE(atten_date, '%Y-%m-%d') IS NULL
        LIMIT 50
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $invalid_count = $pdo->query("
        SELECT COUNT(*) FROM attendance 
        WHERE atten_date NOT REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
        OR STR_TO_DATE(atten_date, '%Y-%m-%d') IS NULL
    ")->fetchColumn();
    
    echo "⚠️  Invalid date format rows: {$invalid_count}\n";
    if (!empty($invalid_dates)) {
        echo "   Sample invalid dates:\n";
        foreach ($invalid_dates as $row) {
            echo "   - ID={$row['id']} emp_id={$row['emp_id']} atten_date='{$row['atten_date']}'\n";
        }
    }
    echo "\n";
} else {
    echo "✅ atten_date is already DATE type — no format issues.\n\n";
}

// ─── 4. Duplicate (emp_id, atten_date) ───────────────────────────────────
$duplicates = $pdo->query("
    SELECT emp_id, atten_date, COUNT(*) as cnt 
    FROM attendance 
    GROUP BY emp_id, atten_date 
    HAVING cnt > 1
    ORDER BY cnt DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);

$dup_total = $pdo->query("
    SELECT COUNT(*) FROM (
        SELECT emp_id, atten_date 
        FROM attendance 
        GROUP BY emp_id, atten_date 
        HAVING COUNT(*) > 1
    ) AS dups
")->fetchColumn();

echo "⚠️  Duplicate (emp_id, atten_date) pairs: {$dup_total}\n";
if (!empty($duplicates)) {
    echo "   Worst duplicates:\n";
    foreach (array_slice($duplicates, 0, 20) as $dup) {
        echo "   - emp_id={$dup['emp_id']} date={$dup['atten_date']} count={$dup['cnt']}\n";
    }
}
echo "\n";

// ─── 5. Charset check ────────────────────────────────────────────────────
$table_info = $pdo->query("
    SELECT TABLE_COLLATION 
    FROM INFORMATION_SCHEMA.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'attendance'
")->fetchColumn();

echo "📋 Table charset/collation: {$table_info}\n";
$needs_charset = (stripos($table_info, 'utf8mb4') === false);
echo "   Needs utf8mb4 migration: " . ($needs_charset ? "YES" : "NO — already utf8mb4") . "\n\n";

// ─── 6. Existing columns check ───────────────────────────────────────────
echo "📋 Existing columns in attendance:\n";
$columns = $pdo->query("SHOW COLUMNS FROM attendance")->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo "   - {$col['Field']} ({$col['Type']}) " . ($col['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
}
echo "\n";

// ─── 7. Date range ───────────────────────────────────────────────────────
$date_range = $pdo->query("SELECT MIN(atten_date) as min_date, MAX(atten_date) as max_date FROM attendance")->fetch(PDO::FETCH_ASSOC);
echo "📋 Date range: {$date_range['min_date']} to {$date_range['max_date']}\n\n";

// ─── Summary ─────────────────────────────────────────────────────────────
echo "═══════════════════════════════════════════════════════════════\n";
echo " SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n";

$issues = 0;
if ($is_varchar) { echo " ❌ atten_date is VARCHAR — needs type change\n"; $issues++; }
if (isset($invalid_count) && $invalid_count > 0) { echo " ❌ {$invalid_count} rows with invalid date format\n"; $issues++; }
if ($dup_total > 0) { echo " ❌ {$dup_total} duplicate (emp_id, atten_date) pairs\n"; $issues++; }
if ($needs_charset) { echo " ⚠️  Table needs charset migration to utf8mb4\n"; $issues++; }

if ($issues === 0) {
    echo " ✅ No issues found — safe to apply migration 004.\n";
} else {
    echo "\n ⛔ RESOLVE {$issues} ISSUE(S) ABOVE BEFORE RUNNING migration 004.\n";
    echo " DO NOT apply risky changes until all issues are fixed.\n";
    echo " Consult database/migrations/004_risky_attendance_changes.sql\n";
}

echo "\n";
echo "This script is READ-ONLY. No data was modified.\n";
echo "═══════════════════════════════════════════════════════════════\n";
