CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `variables` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial Seed Data
INSERT INTO `email_templates` (`code`, `name`, `subject`, `body`, `variables`) VALUES
('reviewer_assignment', 'Hakem Atama Bildirimi', 'Makale Değerlendirme Daveti: {{makale_baslik}}', '<p>Sayın <strong>{{ad_soyad}}</strong>,</p><p>Dergimize gönderilen <strong>{{makale_baslik}}</strong> başlıklı makaleyi değerlendirmek üzere hakem olarak atanmış bulunmaktasınız.</p><p>Değerlendirme süreci için son tarih: <strong>{{son_tarih}}</strong> olarak belirlenmiştir.</p><p>Aşağıdaki bağlantıdan sisteme giriş yaparak değerlendirme sürecini başlatabilirsiniz:</p><p><a href="{{link}}">Makaleyi Görüntüle</a></p><p>Saygılarımızla,<br>{{dergi_adi}} Editörlüğü</p>', '{"ad_soyad": "Hakem Adı", "makale_baslik": "Makale Başlığı", "son_tarih": "Değerlendirme Son Tarihi", "link": "Hakem Paneli Linki", "dergi_adi": "Dergi Adı"}'),

('submission_received', 'Makale Gönderim Onayı', 'Makaleniz Alındı: {{makale_baslik}}', '<p>Sayın <strong>{{yazar_adi}}</strong>,</p><p><strong>{{makale_baslik}}</strong> başlıklı makaleniz dergimize başarıyla ulaşmıştır. Editör ön inceleme süreci başlamıştır.</p><p>Süreci panelinizden takip edebilirsiniz.</p><p>Saygılarımızla,<br>{{dergi_adi}}</p>', '{"yazar_adi": "Yazar Adı", "makale_baslik": "Makale Başlığı", "dergi_adi": "Dergi Adı"}');
