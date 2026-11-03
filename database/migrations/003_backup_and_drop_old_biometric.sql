-- ═══════════════════════════════════════════════════════════════════════════
-- HRMS Face Recognition — Migration 003: Backup & Drop Old Biometric Table
-- ═══════════════════════════════════════════════════════════════════════════
-- ⚠️ BACKUP FIRST! Run the backup command below BEFORE executing DROP.
-- 
-- Backup command (run from MySQL CLI or phpMyAdmin export):
--   mysqldump -u root -p hrms_db employee_face_signatures > backup_face_signatures_$(date +%Y%m%d_%H%M%S).sql
--
-- Or from PHP hosting cPanel → phpMyAdmin → Export → select employee_face_signatures
--
-- The old data is 100% INCOMPATIBLE with the new system:
--   - vector_data: 512-D luminance thumbnails (NOT identity embeddings)
--   - vector_web: 128-D face-api.js embeddings (different model, different dimensionality)
-- There is NO migration path. ALL employees must re-enroll.
-- ═══════════════════════════════════════════════════════════════════════════

-- Verify backup exists before proceeding
-- SELECT COUNT(*) AS old_records FROM employee_face_signatures;

-- Drop the old table
DROP TABLE IF EXISTS `employee_face_signatures`;
