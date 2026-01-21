<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4" data-aos="fade-down">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                    <li class="breadcrumb-item"><a href="/dergi/<?= $makale->dergi_tanim_id ?? $makale->dergi_tanim ?>">Dergi Sayısı</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Makale Detayı</li>
                </ol>
            </nav>

            <article class="article-detail bg-white rounded-4 shadow-sm overflow-hidden" data-aos="fade-up">
                <div class="article-header p-5 text-white bg-gradient-primary">
                    <div class="mb-3">
                        <span class="badge bg-white text-primary mb-2">Makale</span>
                        <h1 class="display-5 fw-bold mb-3"><?= e($makale->makale_baslik) ?></h1>
                    </div>
                    
                    <div class="article-meta d-flex flex-wrap gap-4 align-items-center">
                        <?php if (!empty($makale->makale_yazar)): ?>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
                            <div>
                                <small class="d-block opacity-75">Yazar</small>
                                <strong><?= e($makale->makale_yazar) ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex align-items-center">
                            <i class="bi bi-journal-text fs-4 me-2"></i>
                            <div>
                                <small class="d-block opacity-75">Yayın</small>
                                <strong><?= e($makale->dergi_tanim_text ?? 'Edebiyat Bilimleri') ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="article-body p-5">
                    <?php if (!empty($makale->makale_ozet)): ?>
                    <section class="mb-5">
                        <h4 class="fw-bold border-start border-primary border-4 ps-3 mb-4">Özet / Abstract</h4>
                        <div class="lead text-secondary lh-lg">
                            <?= nl2br(e($makale->makale_ozet)) ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php if (!empty($makale->anahtar_kelime)): ?>
                    <section class="mb-5">
                        <h5 class="fw-bold mb-3">Anahtar Kelimeler</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            $tags = explode(',', $makale->anahtar_kelime);
                            foreach ($tags as $tag): 
                            ?>
                                <span class="badge bg-light text-secondary border px-3 py-2">
                                    #<?= trim(e($tag)) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <div class="article-actions p-4 bg-light rounded-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="text-secondary small">
                            <i class="bi bi-info-circle me-1"></i> Tam metne PDF butonu üzerinden ulaşabilirsiniz.
                        </div>
                        <div class="d-flex gap-2">
                            <?php if (!empty($makale->dosya)): ?>
                            <a href="<?= get_image_url($makale->dosya) ?>" class="btn btn-danger px-4" target="_blank">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Tam Metin Gör (PDF)
                            </a>
                            <?php endif; ?>
                            <button class="btn btn-outline-primary px-4" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i> Yazdır
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Info -->
            <div class="mt-5" data-aos="fade-up">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Atıf Göster (APA)</h5>
                        <div class="p-3 bg-white border rounded border-dashed text-secondary">
                            <?= e($makale->makale_yazar) ?>. (<?= date('Y') ?>). <?= e($makale->makale_baslik) ?>. <i>Edebiyat Bilimleri Dergisi</i>.
                        </div>
                        <button class="btn btn-link btn-sm mt-2 p-0 text-decoration-none" onclick="copyCitation()">
                            <i class="bi bi-clipboard"></i> Atıfı Kopyala
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
}
.border-dashed {
    border-style: dashed !important;
}
.article-detail {
    transition: transform 0.3s ease;
}
@media print {
    .btn, .breadcrumb, footer, nav { display: none !important; }
    .container { width: 100% !important; max-width: none !important; margin: 0 !important; }
    .bg-gradient-primary { background: none !important; color: black !important; border-bottom: 2px solid #eee; }
    .text-white { color: black !important; }
}
</style>

<script>
function copyCitation() {
    const text = document.querySelector('.border-dashed').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Atıf kopyalandı!');
    });
}
</script>
