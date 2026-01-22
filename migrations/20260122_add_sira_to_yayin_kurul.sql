ALTER TABLE yayin_kurul ADD COLUMN sira INT DEFAULT 0 AFTER id;
CREATE INDEX idx_sira ON yayin_kurul(sira);
