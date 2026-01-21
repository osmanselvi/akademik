<!-- Custom Styles for Archive Page -->
<style>
    .archive-header {
        position: relative;
        padding: 50px 0;
        margin: -30px -15px 40px -15px;
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        color: white;
        border-radius: 0 0 40px 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .archive-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('/images/pattern.png'); /* If you have a subtle pattern */
        opacity: 0.05;
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .journal-grid-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #edf2f7;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .journal-grid-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: #667eea;
    }

    .card-cover-wrapper {
        position: relative;
        height: 320px;
        overflow: hidden;
    }

    .card-cover-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .journal-grid-card:hover .card-cover-wrapper img {
        transform: scale(1.1);
    }

    .card-badge-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 15px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .card-overlay-modern {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
        display: flex;
        align-items: flex-end;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .journal-grid-card:hover .card-overlay-modern {
        opacity: 1;
    }

    .view-details-btn {
        background: white;
        color: #1a202c;
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        text-align: center;
        transition: all 0.3s ease;
        transform: translateY(10px);
    }

    .journal-grid-card:hover .view-details-btn {
        transform: translateY(0);
    }

    .journal-info-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .journal-title-link {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2d3748;
        text-decoration: none;
        margin-bottom: 8px;
        line-height: 1.3;
        transition: color 0.3s ease;
    }

    .journal-grid-card:hover .journal-title-link {
        color: #667eea;
    }

    .journal-meta {
        font-size: 0.85rem;
        color: #718096;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f7fafc;
    }

    .meta-tag {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .meta-tag i {
        color: #667eea;
    }

    .search-filter-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 10px 20px;
        margin-top: 20px;
    }
</style>

<!-- Page Header -->
<div class="archive-header">
    <div class="container header-content">
        <div class="row align-items-center">
            <div class="col-md-7">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider-color: rgba(255,255,255,0.5);">
                        <li class="breadcrumb-item"><a href="/" class="text-white-50 text-decoration-none small">Ana Sayfa</a></li>
                        <li class="breadcrumb-item active text-white small" aria-current="page">Dergi Arşivi</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold mb-2">Dergi Arşivi</h1>
                <p class="lead text-white-50 mb-0">Edebiyat Bilimleri Dergisi'nin tüm sayıları burada listelenir.</p>
            </div>
            <div class="col-md-5 text-md-end mt-4 mt-md-0">
                <div class="d-inline-flex align-items-center gap-3 bg-white bg-opacity-10 p-3 rounded-4">
                    <div class="text-start">
                        <div class="text-white-50 small">Toplam Sayı</div>
                        <div class="h3 fw-bold mb-0"><?= count($dergiler ?? []) ?></div>
                    </div>
                    <div class="vr opacity-25" style="height: 40px;"></div>
                    <i class="bi bi-journals display-6 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <?php if (!empty($dergiler)): ?>
        <div class="row g-4 mt-n2">
            <?php foreach ($dergiler as $dergi): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="journal-grid-card">
                        <!-- Cover Section -->
                        <div class="card-cover-wrapper">
                            <div class="card-badge-overlay">
                                <?php if ($dergi->is_approved == 1): ?>
                                    <span class="badge bg-success status-badge">Güncel Sayı</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark shadow-sm status-badge">Arşiv</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($dergi->dergi_kapak)): ?>
                                <img src="<?= get_image_url($dergi->dergi_kapak) ?>" 
                                     alt="<?= e($dergi->dergi_tanim) ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="bg-light h-100 d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-journal-text display-1 opacity-25"></i>
                                </div>
                            <?php endif; ?>

                            <div class="card-overlay-modern">
                                <a href="/dergi/<?= $dergi->id ?>" class="view-details-btn">
                                    Sayıyı İncele <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Content Section -->
                        <div class="journal-info-body">
                            <a href="/dergi/<?= $dergi->id ?>" class="journal-title-link">
                                <?= e($dergi->dergi_tanim) ?>
                            </a>
                            
                            <?php if (!empty($dergi->ing_baslik)): ?>
                                <p class="text-muted small mb-4 line-clamp-2"><?= e($dergi->ing_baslik) ?></p>
                            <?php else: ?>
                                <p class="text-white-50 small mb-4">Açıklama bulunmuyor.</p>
                            <?php endif; ?>
                            
                            <div class="journal-meta">
                                <div class="meta-tag">
                                    <i class="bi bi-calendar-event"></i>
                                    <span><?= formatDate($dergi->yayin_created_at, 'Y') ?></span>
                                </div>
                                
                                <?php if (isset($dergi->makale_sayisi)): ?>
                                    <div class="meta-tag">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span><?= $dergi->makale_sayisi ?> Makale</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm">
            <div class="text-secondary small">
                <?= $paginator->getInfo() ?>
            </div>
            <div>
                <?= $paginator->getLinks() ?>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="display-1 text-muted mb-4 opacity-25">
                <i class="bi bi-folder-x"></i>
            </div>
            <h3>Henüz Yayınlanmış Dergi Bulunmuyor</h3>
            <p class="text-muted">Daha sonra tekrar kontrol ediniz.</p>
            <a href="/" class="btn btn-primary mt-3 px-5 py-2 rounded-pill">Ana Sayfaya Dön</a>
        </div>
    <?php endif; ?>
</div>

