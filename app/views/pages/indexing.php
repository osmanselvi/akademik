<div class="container py-5">
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-4 mb-3">Dizinler</h1>
            <div class="mx-auto bg-primary rounded-pill mb-4" style="width: 80px; height: 5px;"></div>
            <p class="lead text-muted">Dergimizin tarandığı indeksler ve veritabanları</p>
        </div>
    </div>

    <div class="row g-4">
        <?php if (empty($dizinler)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> Henüz dizin bilgisi bulunmamaktadır.
                </div>
            </div>
        <?php else: ?>
            <?php $delay = 0; foreach($dizinler as $dizin): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <?php if (isset($dizin->dizin_logo) && $dizin->dizin_logo): ?>
                            <div class="mb-3">
                                <img src="<?= get_image_url($dizin->dizin_logo) ?>" alt="<?= e($dizin->dizin_adi) ?>" 
                                     class="img-fluid" style="max-height: 80px; object-fit: contain;">
                            </div>
                        <?php else: ?>
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="bi bi-database text-primary fs-3"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="fw-bold mb-2"><?= e($dizin->dizin_adi) ?></h5>
                        <?php if (isset($dizin->dizin_link) && $dizin->dizin_link): ?>
                            <a href="<?= e($dizin->dizin_link) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill mt-2">
                                <i class="bi bi-link-45deg me-1"></i> Web Sitesi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $delay += 100; endforeach; ?>
        <?php endif; ?>
    </div>
</div>
