<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="display-5">
            <i class="bi bi-journals"></i> Dergi Sayıları
        </h1>
        <p class="text-muted">Yayınlanmış tüm dergi sayılarını görüntüleyin</p>
    </div>
</div>

<?php if (!empty($dergiler)): ?>
    <div class="row">
        <?php foreach ($dergiler as $dergi): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="modern-card h-100">
                    <div class="card-image">
                        <?php if (!empty($dergi->dergi_kapak)): ?>
                            <img src="<?= get_image_url($dergi->dergi_kapak) ?>" 
                                 alt="Dergi Kapağı">
                        <?php else: ?>
                            <div class="card-placeholder">
                                <i class="bi bi-journal-text"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-overlay">
                            <a href="/dergi/<?= $dergi->id ?>" class="overlay-btn">
                                <i class="bi bi-eye"></i> İncele
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-content d-flex flex-column">
                        <h5 class="card-title text-uppercase-custom"><?= e($dergi->dergi_tanim) ?></h5>
                        
                        <?php if (!empty($dergi->ing_baslik)): ?>
                            <p class="text-muted small mb-3"><?= e($dergi->ing_baslik) ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-auto">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <?php if ($dergi->is_approved == 1): ?>
                                    <span class="badge bg-success">Güncel Sayı</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Geçmiş Sayı</span>
                                <?php endif; ?>
                                
                                <?php if (!empty($dergi->yayin_created_at)): ?>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-calendar text-primary"></i> 
                                        <?= formatDate($dergi->yayin_created_at) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (isset($dergi->makale_sayisi)): ?>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-file-text text-primary"></i> 
                                        <?= $dergi->makale_sayisi ?> Makale
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <a href="/dergi/<?= $dergi->id ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                <i class="bi bi-eye"></i> Sayıyı Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> Henüz yayınlanmış dergi bulunmamaktadır.
    </div>
<?php endif; ?>
