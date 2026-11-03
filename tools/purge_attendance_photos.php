<?php
/**
 * HRMS — Purge old attendance photos
 * 
 * Deletes photo files older than N days (default 90).
 * Attendance records are preserved — only the JPG files are removed.
 * 
 * RUN via cron: php tools/purge_attendance_photos.php
 * Optional: php tools/purge_attendance_photos.php --days=30 --dry-run
 */

$base_dir = dirname(__FILE__) . '/../uploads/attendance/';
$days = 90;
$dry_run = false;

// Parse CLI args
foreach ($argv ?? [] as $arg) {
    if (preg_match('/--days=(\d+)/', $arg, $m)) $days = (int)$m[1];
    if ($arg === '--dry-run') $dry_run = true;
}

$cutoff = time() - ($days * 86400);
$cutoff_date = date('Y-m-d', $cutoff);

echo "═══════════════════════════════════════════════════════\n";
echo " HRMS Photo Purge — Files older than {$days} days\n";
echo " Cutoff date: {$cutoff_date}\n";
echo " Mode: " . ($dry_run ? 'DRY RUN' : 'DELETE') . "\n";
echo "═══════════════════════════════════════════════════════\n\n";

$deleted = 0;
$skipped = 0;
$errors = 0;
$bytes_freed = 0;

// Walk year/month/day directories
$years = glob($base_dir . '20*', GLOB_ONLYDIR);
foreach ($years as $year_dir) {
    $months = glob($year_dir . '/*', GLOB_ONLYDIR);
    foreach ($months as $month_dir) {
        $day_dirs = glob($month_dir . '/*', GLOB_ONLYDIR);
        foreach ($day_dirs as $day_dir) {
            // Extract date from path: YYYY/MM/DD
            $parts = array_slice(explode('/', $day_dir), -3);
            $dir_date = implode('-', $parts);
            
            if (strtotime($dir_date) === false || strtotime($dir_date) >= $cutoff) {
                $skipped++;
                continue;
            }

            $files = glob($day_dir . '/*.jpg');
            foreach ($files as $file) {
                $size = filesize($file);
                if ($dry_run) {
                    echo "[DRY] Would delete: {$file} (" . round($size/1024) . " KB)\n";
                } else {
                    if (@unlink($file)) {
                        $deleted++;
                        $bytes_freed += $size;
                    } else {
                        echo "[ERR] Failed to delete: {$file}\n";
                        $errors++;
                    }
                }
            }

            // Remove empty directory
            if (!$dry_run && count(glob($day_dir . '/*')) === 0) {
                @rmdir($day_dir);
            }
        }
        if (!$dry_run && count(glob($month_dir . '/*')) === 0) {
            @rmdir($month_dir);
        }
    }
    if (!$dry_run && count(glob($year_dir . '/*')) === 0) {
        @rmdir($year_dir);
    }
}

echo "\n═══════════════════════════════════════════════════════\n";
echo " Deleted: {$deleted} files\n";
echo " Freed: " . round($bytes_freed / 1024 / 1024, 1) . " MB\n";
echo " Errors: {$errors}\n";
echo "═══════════════════════════════════════════════════════\n";
