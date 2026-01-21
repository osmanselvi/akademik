-- Add Indexes for Performance Optimization

-- 1. Frequent Filtering (is_approved) across main tables
-- Note: Using IF NOT EXISTS syntax implicitly via standard CREATE INDEX (MySQL 5.7+ supports IF NOT EXISTS but safely separate statements is most compatible)
-- We will just use CREATE INDEX and let it fail if exists (or use a procedure, but simple is better).
-- To avoid errors if they exist, we can use a more robust approach or just accept that re-running might show duplicates were not created.
-- Standard CREATE INDEX:

CREATE INDEX idx_is_approved_dergi_tanim ON dergi_tanim(is_approved);
CREATE INDEX idx_is_approved_online_makale ON online_makale(is_approved);
CREATE INDEX idx_is_approved_dergi_kunye ON dergi_kunye(is_approved);

-- 2. User Lookups
CREATE INDEX idx_user_email ON yonetim(email);

-- 3. Sorting / Ordering
-- Note: yayin_created_at is varchar(50) in schema dump (e.g. '30.06.2023'). Indexing it helps specific string matches but for range queries it might be suboptimal.
-- Ideally this should be a DATE column. Indexing it is still better than full scan if we filter by it.
CREATE INDEX idx_journal_date ON dergi_tanim(yayin_created_at);
