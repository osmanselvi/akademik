-- Migration: Increase password column length for Bcrypt hashes
-- Table: yonetim
-- Column: sifre

ALTER TABLE yonetim MODIFY COLUMN sifre VARCHAR(255) NOT NULL;
