-- Add statistics columns to online_makale
ALTER TABLE `online_makale` 
ADD COLUMN `view_count` INT(11) NOT NULL DEFAULT 0 AFTER `is_approved`,
ADD COLUMN `download_count` INT(11) NOT NULL DEFAULT 0 AFTER `view_count`;

-- Add index for performance
CREATE INDEX `idx_view_count` ON `online_makale` (`view_count`);
CREATE INDEX `idx_download_count` ON `online_makale` (`download_count`);
