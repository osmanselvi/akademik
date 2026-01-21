<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
        <li class="breadcrumb-item"><a href="/dergiler">Dergiler</a></li>
        <li class="breadcrumb-item active"><?= e($dergi->dergi_tanim) ?></li>
    </ol>
</nav>

<!-- Dergi Header -->
<div class="row mb-4">
    <div class="col-md-3">
        <?php if (!empty($dergi->dergi_kapak)): ?>
            <img src="<?= get_image_url($dergi->dergi_kapak) ?>" 
                 class="img-fluid rounded shadow" 
                 alt="Dergi Kapağı">
        <?php endif; ?>
        
        <div class="mt-3">
            <?php if ($dergi->is_approved == 1): ?>
                <span class="badge bg-success w-100 py-2">
                    <i class="bi bi-star-fill"></i> Güncel Sayı
                </span>
            <?php else: ?>
                <span class="badge bg-secondary w-100 py-2">
                    <i class="bi bi-archive"></i> Geçmiş Sayı
                </span>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($dergi->yayin_created_at)): ?>
            <div class="mt-2 text-center">
                <small class="text-muted">
                    <i class="bi bi-calendar3"></i> Yayınlanma: 
                    <br><strong><?= formatDate($dergi->yayin_created_at) ?></strong>
                </small>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($dergi->jenerikdosyasi)): ?>
            <div class="mt-3">
                <a href="<?= get_image_url($dergi->jenerikdosyasi) ?>" 
                   class="btn btn-danger btn-sm w-100" 
                   target="_blank">
                    <i class="bi bi-file-pdf"></i> Jenerik İndir
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-9">
        <h1 class="display-6 mb-3"><?= e($dergi->dergi_tanim) ?></h1>
        
        <?php if (!empty($dergi->ing_baslik)): ?>
            <h4 class="text-muted mb-4"><?= e($dergi->ing_baslik) ?></h4>
        <?php endif; ?>
        
        <!-- Makale Listesi -->
        <h3 class="mt-4 mb-3">
            <i class="bi bi-list-ul"></i> Makaleler
            <?php if (!empty($makaleler)): ?>
                <span class="badge bg-primary"><?= count($makaleler) ?></span>
            <?php endif; ?>
        </h3>
        
        <?php if (!empty($makaleler)): ?>
            <div class="list-group">
                <?php foreach ($makaleler as $index => $makale): ?>
                    <div class="list-group-item list-group-item-action py-3">
                        <div class="d-flex flex-column flex-md-row w-100 justify-content-between align-items-center">
                            <div class="flex-grow-1 mb-3 mb-md-0 me-md-4">
                                <h5 class="mb-2 fw-bold text-uppercase-custom">
                                    <?php if ($makale->makale_turu == 2): ?>
                                        <a href="/makale/<?= $makale->id ?>" class="text-decoration-none text-dark hover-primary">
                                            <?= e($makale->makale_baslik) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= e($makale->makale_baslik) ?>
                                    <?php endif; ?>
                                </h5>
                                
                                <?php if (!empty($makale->makale_yazar)): ?>
                                    <p class="mb-2 text-primary small">
                                        <i class="bi bi-person-fill"></i> 
                                        <?= e($makale->makale_yazar) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($makale->makale_ozet)): ?>
                                    <p class="mb-0 small text-secondary">
                                        <?= str_limit(strip_tags($makale->makale_ozet), 160) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-shrink-0">
                                <?php if (!empty($makale->dosya)): ?>
                                    <a href="<?= get_image_url($makale->dosya) ?>" 
                                       class="btn btn-outline-danger px-4 rounded-pill d-flex align-items-center justify-content-center" 
                                       target="_blank">
                                        <i class="bi bi-file-pdf me-2"></i> PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Bu sayıda henüz makale bulunmamaktadır.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Back Button -->
<div class="row mt-4">
    <div class="col-12">
        <a href="/dergiler" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Dergi Listesine Dön
        </a>
    </div>
</div>
