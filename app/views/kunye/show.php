<div class="container py-5">
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5 mb-3">Dergi Künyesi</h1>
            <div class="mx-auto bg-primary rounded-pill" style="width: 80px; height: 5px;"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <?php if (empty($kunye)): ?>
                <div class="text-center py-5">
                    <p class="text-muted fs-5">Künye bilgisi bulunmamaktadır.</p>
                </div>
            <?php else: ?>
                <?php foreach($kunye as $kategori => $entries): ?>
                    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                <i class="bi bi-chevron-right me-2"></i><?= e($kategori) ?>
                            </h5>
                            <div class="ms-4">
                                <?php foreach($entries as $entry): ?>
                                    <p class="mb-2 text-dark">
                                        <?php if (in_array(strtolower($kategori), ['web adresi', 'e-posta adresi'])): ?>
                                            <?php if (filter_var($entry->ad_soyad, FILTER_VALIDATE_EMAIL)): ?>
                                                <a href="mailto:<?= e($entry->ad_soyad) ?>" class="text-decoration-none">
                                                    <?= e($entry->ad_soyad) ?>
                                                </a>
                                            <?php elseif (filter_var($entry->ad_soyad, FILTER_VALIDATE_URL)): ?>
                                                <a href="<?= e($entry->ad_soyad) ?>" target="_blank" class="text-decoration-none">
                                                    <?= e($entry->ad_soyad) ?>
                                                </a>
                                            <?php else: ?>
                                                <?= e($entry->ad_soyad) ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?= e($entry->ad_soyad) ?>
                                        <?php endif; ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
