-- ═══════════════════════════════════════════════════════════════════════════
-- HRMS Face Recognition — Migration 002: Safe ALTER on attendance table
-- ═══════════════════════════════════════════════════════════════════════════
-- SAFE to run: only ADDS new nullable columns. Does NOT modify existing data.
-- ═══════════════════════════════════════════════════════════════════════════

-- Add face score columns (after photo columns)
ALTER TABLE `attendance`
    ADD COLUMN IF NOT EXISTS `face_score_in` FLOAT NULL AFTER `photo_in`,
    ADD COLUMN IF NOT EXISTS `face_score_out` FLOAT NULL AFTER `photo_out`;

-- Add liveness score columns
ALTER TABLE `attendance`
    ADD COLUMN IF NOT EXISTS `liveness_in` FLOAT NULL,
    ADD COLUMN IF NOT EXISTS `liveness_out` FLOAT NULL;

-- Add foreign keys to face_recognition_log
ALTER TABLE `attendance`
    ADD COLUMN IF NOT EXISTS `face_log_in_id` BIGINT NULL,
    ADD COLUMN IF NOT EXISTS `face_log_out_id` BIGINT NULL;

-- Add verification method tracking
ALTER TABLE `attendance`
    ADD COLUMN IF NOT EXISTS `verified_by` ENUM('face','pin','manual_hr') NULL;
