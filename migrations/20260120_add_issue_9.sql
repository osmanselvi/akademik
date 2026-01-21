-- Migration: Add 9th journal issue (Edebiyat Bilimleri 9. Sayı)
-- Run with: php migrate.php
INSERT INTO dergi_tanim (id, dergi_tanim, is_approved, dergi_kapak, yayin_created_at)
VALUES (9, 'Edebiyat Bilimleri 9. Sayı', 1, 'dergikapaks9_on.png', CURRENT_DATE);

-- If you have PDF articles for this issue, add them to `online_makale` table, e.g.:
-- INSERT INTO online_makale (id, dergi_tanim, makale_baslik, makale_yazar, dosya, makale_turu, is_approved)
-- VALUES (NULL, 9, 'Makale Başlığı', 'Yazar Adı', 'makaleler/9_makaleler.pdf', 1, 1);
