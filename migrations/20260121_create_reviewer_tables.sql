-- Create makale_hakem table (Reviewer Assignments)
CREATE TABLE IF NOT EXISTS `makale_hakem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `makale_id` int(11) NOT NULL,
  `hakem_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:Pending, 1:Accepted, 2:Rejected, 3:Completed',
  `deadline` date NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_makale` (`makale_id`),
  KEY `idx_hakem` (`hakem_id`),
  CONSTRAINT `fk_review_article` FOREIGN KEY (`makale_id`) REFERENCES `gonderilen_makale` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_reviewer` FOREIGN KEY (`hakem_id`) REFERENCES `yonetim` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create hakem_degerlendirme table (Main Evaluation Form)
CREATE TABLE IF NOT EXISTS `hakem_degerlendirme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `makale_hakem_id` int(11) NOT NULL,
  `karar` tinyint(4) NOT NULL COMMENT '1:Accept, 2:Minor Rev, 3:Major Rev, 4:Reject',
  `notlar_yazar` text,
  `notlar_editor` text,
  `dosya` varchar(255) NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_review_assign` (`makale_hakem_id`),
  CONSTRAINT `fk_eval_assign` FOREIGN KEY (`makale_hakem_id`) REFERENCES `makale_hakem` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create hakem_degerlendirme_cevap table (Criteria Scores)
CREATE TABLE IF NOT EXISTS `hakem_degerlendirme_cevap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degerlendirme_id` int(11) NOT NULL,
  `kriter_id` int(11) NOT NULL,
  `puan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_eval` (`degerlendirme_id`),
  KEY `idx_criteria` (`kriter_id`),
  CONSTRAINT `fk_top_eval` FOREIGN KEY (`degerlendirme_id`) REFERENCES `hakem_degerlendirme` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_top_criteria` FOREIGN KEY (`kriter_id`) REFERENCES `hakem_kriter` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
