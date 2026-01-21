-- 1. Orphan Data Cleanup
-- Users with invalid Groups
DELETE FROM yonetim WHERE grup_id NOT IN (SELECT id FROM usergroup);

-- Articles with invalid Journals
DELETE FROM online_makale WHERE dergi_tanim NOT IN (SELECT id FROM dergi_tanim);

-- Articles with invalid Types (Corrected column name)
DELETE FROM online_makale WHERE makale_turu NOT IN (SELECT id FROM dergi_makale_tur);

-- Board Members with invalid Titles
DELETE FROM dergi_kunye WHERE baslik_id NOT IN (SELECT id FROM dergi_kunye_baslik);

-- 2. Add Foreign Key Constraints

-- Users -> Groups
ALTER TABLE yonetim 
ADD CONSTRAINT fk_user_group 
FOREIGN KEY (grup_id) REFERENCES usergroup(id);

-- Articles -> Journals
ALTER TABLE online_makale 
ADD CONSTRAINT fk_article_journal 
FOREIGN KEY (dergi_tanim) REFERENCES dergi_tanim(id) ON DELETE CASCADE;

-- Articles -> Article Types (Corrected column name)
ALTER TABLE online_makale 
ADD CONSTRAINT fk_article_type 
FOREIGN KEY (makale_turu) REFERENCES dergi_makale_tur(id);

-- Board -> Board Titles
ALTER TABLE dergi_kunye 
ADD CONSTRAINT fk_board_title 
FOREIGN KEY (baslik_id) REFERENCES dergi_kunye_baslik(id);
