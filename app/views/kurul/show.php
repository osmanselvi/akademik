<div class="container py-5">
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5 mb-3"><?= e($kurul->kurul) ?></h1>
            <div class="mx-auto bg-primary rounded-pill" style="width: 80px; height: 5px;"></div>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <?php if (empty($members)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">Bu kurul için henüz aktif üye kaydı bulunmamaktadır.</p>
            </div>
        <?php else: ?>
            <?php foreach ($members as $member): ?>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in">
                    <div class="modern-card border-0 shadow-sm p-4 h-100">
                        <div class="d-flex align-items-start">
                            <div class="avatar-lg bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                <span class="fs-3 fw-bold"><?= strtoupper(substr($member->ad_soyad, 0, 1)) ?></span>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1"><?= e($member->unvan_text) ?> <?= e($member->ad_soyad) ?></h5>
                                <div class="badge bg-soft-info text-dark mb-2"><?= e($member->gorev_text) ?></div>
                                
                                <?php if ($member->bolum_ad): ?>
                                    <p class="text-secondary small mb-2">
                                        <i class="bi bi-bank me-1"></i> <?= e($member->bolum_ad) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($member->aciklama): ?>
                                    <p class="text-muted small italic mb-3">"<?= e($member->aciklama) ?>"</p>
                                <?php endif; ?>

                                <div class="d-flex gap-2">
                                    <?php if ($member->email): ?>
                                        <a href="mailto:<?= e($member->email) ?>" class="btn btn-sm btn-outline-secondary rounded-pill" title="E-posta">
                                            <i class="bi bi-envelope"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member->orcid_number): ?>
                                        <a href="https://orcid.org/<?= e($member->orcid_number) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill" title="ORCID">
                                            <i class="bi bi-person-badge"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($member->web_page): ?>
                                        <a href="<?= e($member->web_page) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill" title="Web Sayfası">
                                            <i class="bi bi-globe"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(102, 126, 234, 0.1); }
.bg-soft-info { background-color: rgba(118, 75, 162, 0.1); }
.avatar-lg {
    width: 60px;
    height: 60px;
}
.italic { font-style: italic; }
</style>
