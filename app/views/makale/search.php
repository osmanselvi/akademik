<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                    <li class="breadcrumb-item active">Arama Sonuçları</li>
                </ol>
            </nav>

            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Filtrele</h5>
                            <form action="/makale/ara" method="GET">
                                <input type="hidden" name="q" value="<?= e($query) ?>">
                                
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">YAYIN YILI ARALIĞI</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <select name="start_year" class="form-select bg-light border-0 py-2 rounded-3 small">
                                                <option value="">Başlangıç</option>
                                                <?php foreach($years as $y): ?>
                                                    <option value="<?= $y->yil ?>" <?= ($filters['start_year'] ?? '') == $y->yil ? 'selected' : '' ?>>
                                                        <?= $y->yil ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="end_year" class="form-select bg-light border-0 py-2 rounded-3 small">
                                                <option value="">Bitiş</option>
                                                <?php foreach($years as $y): ?>
                                                    <option value="<?= $y->yil ?>" <?= ($filters['end_year'] ?? '') == $y->yil ? 'selected' : '' ?>>
                                                        <?= $y->yil ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">DERGİ / SAYI</label>
                                    <select name="journal_id" class="form-select bg-light border-0 py-2 rounded-3">
                                        <option value="">Tümü</option>
                                        <?php foreach($journals as $j): ?>
                                            <option value="<?= $j->id ?>" <?= ($filters['journal_id'] ?? '') == $j->id ? 'selected' : '' ?>>
                                                <?= e($j->dergi_tanim) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm">
                                    Filtrele <i class="bi bi-funnel ms-1"></i>
                                </button>
                                
                                <?php if (!empty($_GET['start_year']) || !empty($_GET['end_year']) || !empty($_GET['journal_id'])): ?>
                                    <a href="/makale/ara?q=<?= urlencode($query) ?>" class="btn btn-link w-100 mt-2 text-decoration-none text-muted small">
                                        Filtreleri Temizle
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Results Column -->
                <div class="col-lg-9">
                    <div class="search-header mb-4" data-aos="fade-up">
                        <h1 class="display-6 fw-bold mb-2 text-gradient">Arama Sonuçları</h1>
                        <p class="text-secondary">
                            <?php if (!empty($query)): ?>
                                "<strong><?= e($query) ?></strong>" araması için 
                            <?php endif; ?>
                            
                            <?php if (!empty($_GET['start_year']) || !empty($_GET['end_year'])): ?>
                                <span class="badge bg-soft-secondary text-secondary fw-normal">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?= e($filters['start_year']) ?> - <?= e($filters['end_year']) ?>
                                </span>
                            <?php endif; ?>

                            <span class="badge bg-primary rounded-pill"><?= count($results) ?></span> sonuç bulundu.
                        </p>
                    </div>

                    <div class="search-results">
                        <?php if (empty($results)): ?>
                        <div class="text-center py-5" data-aos="zoom-in">
                            <div class="mb-4">
                                <i class="bi bi-search text-light" style="font-size: 5rem;"></i>
                            </div>
                            <h3>Sonuç Bulunamadı</h3>
                            <p class="text-secondary">Farklı anahtar kelimeler veya filtreler deneyebilirsiniz.</p>
                            <a href="/makale/ara?q=" class="btn btn-primary mt-3">Aramayı Sıfırla</a>
                        </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach($results as $index => $item): ?>
                                <div class="col-12" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-lift">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-file-text me-1"></i> MAKALE
                                                </div>
                                                <?php if (isset($item->yayin_yili)): ?>
                                                    <span class="text-muted small">
                                                        <i class="bi bi-calendar3 me-1"></i> <?= $item->yayin_yili ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <h4 class="fw-bold mb-3 text-uppercase-custom">
                                                <a href="/makale/<?= $item->id ?>" class="text-dark text-decoration-none">
                                                    <?= e($item->makale_baslik) ?>
                                                </a>
                                            </h4>
                                            
                                            <p class="text-secondary mb-3">
                                                <i class="bi bi-person me-1"></i> <?= e($item->makale_yazar) ?>
                                            </p>
                                            
                                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                                <div class="small text-muted text-truncate me-3">
                                                    <i class="bi bi-journal-bookmark me-1"></i> <?= e($item->dergi_adi ?? 'Edebiyat Bilimleri') ?>
                                                </div>
                                                <a href="/makale/<?= $item->id ?>" class="btn btn-outline-primary btn-sm px-4 rounded-pill flex-shrink-0">
                                                    İncele <i class="bi bi-chevron-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gradient {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.bg-soft-primary {
    background-color: rgba(52, 152, 219, 0.1);
}
.hover-lift {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
}
@media (max-width: 768px) {
    .card-body { padding: 1.5rem !important; }
    h4 { font-size: 1.25rem !important; }
    .display-6 { font-size: 1.75rem !important; }
}
</style>
