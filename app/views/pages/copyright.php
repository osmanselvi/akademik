<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 class="fw-bold display-5 mb-3">Telif Devir Sözleşmesi</h1>
            <div class="mx-auto bg-primary rounded-pill mb-4" style="width: 80px; height: 5px;"></div>
            <p class="lead text-muted">Yayın Hakkı Devir Formu</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Legacy Download Section (Kept for convenience as it was in previous version) -->
            <div class="alert alert-warning shadow-sm border-0 rounded-4 mb-4" data-aos="fade-up">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i>
                    <div>
                        <strong class="d-block">Önemli Hatırlatma</strong>
                        Makalenizi sisteme yüklerken imzalanmış telif devir formunu da eklemeniz gerekmektedir.
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3 text-primary">
                                <i class="bi bi-person-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Tek Yazarlı Form</h5>
                                <a href="/belgeler/tekyazarli_telifhakkisozlesme.docx" class="text-decoration-none small fw-bold">
                                    <i class="bi bi-download me-1"></i> Dosyayı İndir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3 text-success">
                                <i class="bi bi-people-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Çok Yazarlı Form</h5>
                                <a href="/belgeler/cokyazarli_telifhakkisozlesme.docx" class="text-decoration-none small fw-bold text-success">
                                    <i class="bi bi-download me-1"></i> Dosyayı İndir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Agreement Content -->
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-header bg-warning py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-bookmark-check-fill me-2"></i> Sözleşme Maddeleri</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if (!empty($groupedTerms)): ?>
                        <?php foreach($groupedTerms as $baslik => $items): ?>
                            <div class="mb-5">
                                <h4 class="fw-bold text-primary mb-3 border-bottom pb-2"><?= e($baslik) ?></h4>
                                <ul class="list-unstyled">
                                    <?php foreach($items as $item): ?>
                                        <li class="mb-3 position-relative ps-4">
                                            <i class="bi bi-check2-circle text-success position-absolute start-0 top-0 mt-1"></i>
                                            <div class="lh-lg text-justify">
                                                <?= nl2br(htmlspecialchars_decode($item->metin)) ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-info-circle fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted">Sözleşme maddeleri henüz eklenmemiştir.</p>
                        </div>
                    <?php endif; ?>

                    <div class="alert alert-info mt-4 mb-0 rounded-4 border-0 shadow-sm">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Sorularınız için <a href="mailto:bilgi@edebiyatbilim.com" class="alert-link">bilgi@edebiyatbilim.com</a> adresinden bizimle iletişime geçebilirsiniz.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-justify {
    text-align: justify;
}
</style>
