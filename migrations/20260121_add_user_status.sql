ALTER TABLE `yonetim` 
ADD COLUMN `is_active` TINYINT(1) DEFAULT 1 AFTER `is_approved`,
ADD COLUMN `is_verified` TINYINT(1) DEFAULT 0 AFTER `is_active`;

UPDATE `yonetim` SET `is_active` = 1 WHERE `id` > 0;
