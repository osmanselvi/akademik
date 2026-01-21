<!-- Custom Styles for this page -->
<style>
    .journal-hero {
        position: relative;
        padding: 60px 0;
        margin: -30px -15px 40px -15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        overflow: hidden;
        border-radius: 0 0 50px 50px;
        color: white;
    }

    .journal-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?= get_image_url($dergi->dergi_kapak) ?>') no-repeat center center;
        background-size: cover;
        filter: blur(20px) brightness(0.7);
        opacity: 0.4;
        transform: scale(1.1);
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .journal-cover-main {
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        transition: transform 0.3s ease;
        max-width: 100%;
        height: auto;
    }

    .journal-cover-main:hover {
        transform: scale(1.02);
    }

    .info-badge {
        background: rgba(255,255,255,0.15);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .article-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .article-card:hover {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
        border-color: #667eea;
        transform: translateX(5px);
    }

    .article-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .article-card:hover::before {
        opacity: 1;
    }

    .article-number {
        font-family: 'Outfit', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: #edf2f7;
        position: absolute;
        right: 20px;
        top: 10px;
        z-index: 0;
        transition: color 0.3s ease;
    }

    .article-card:hover .article-number {
        color: rgba(102, 126, 234, 0.05);
    }

    .article-content {
        position: relative;
        z-index: 1;
    }

    .pdf-btn {
        background: #fff;
        color: #dc3545;
        border: 2px solid #dc3545;
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-btn:hover {
        background: #dc3545;
        color: white;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    .meta-item {
        color: #718096;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .jenerik-btn {
        background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 65, 108, 0.3);
    }

    .jenerik-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 65, 108, 0.4);
        color: white;
    }

    .breadcrumb-custom .breadcrumb-item, 
    .breadcrumb-custom .breadcrumb-item a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .breadcrumb-custom .breadcrumb-item.active {
        color: white;
    }
</style>

<!-- Hero Section -->
<div class="journal-hero">
    <div class="container hero-content">
        <nav aria-label="breadcrumb" class="mb-4 breadcrumb-custom">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="/dergiler">Dergiler</a></li>
                <li class="breadcrumb-item active"><?= e($dergi->dergi_tanim) ?></li>
            </ol>
        </nav>

        <div class="row align-items-center g-5">
            <div class="col-lg-4 text-center text-lg-start">
                <?php if (!empty($dergi->dergi_kapak)): ?>
                    <img src="<?= get_image_url($dergi->dergi_kapak) ?>" 
                         class="journal-cover-main" 
                         alt="Dergi Kapağı">
                <?php else: ?>
                    <div class="glass-card d-flex align-items-center justify-content-center" style="height: 450px;">
                        <i class="bi bi-journal-text" style="font-size: 5rem; opacity: 0.5;"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php if ($dergi->is_approved == 1): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-patch-check-fill me-1"></i> Güncel Sayı
                            </span>
                        <?php endif; ?>
                        
                        <div class="info-badge">
                            <i class="bi bi-calendar3"></i> <?= formatDate($dergi->yayin_created_at) ?>
                        </div>
                    </div>

                    <h1 class="display-5 fw-bold mb-2"><?= e($dergi->dergi_tanim) ?></h1>
                    <?php if (!empty($dergi->ing_baslik)): ?>
                        <p class="lead opacity-75 mb-4"><?= e($dergi->ing_baslik) ?></p>
                    <?php endif; ?>

                    <div class="row g-4 mt-2">
                        <div class="col-auto">
                            <?php if (!empty($dergi->jenerikdosyasi)): ?>
                                <a href="<?= get_image_url($dergi->jenerikdosyasi) ?>" 
                                   class="jenerik-btn d-inline-block text-decoration-none" 
                                   target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Jenerik / Künye Dosyasını İndir
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <!-- Main Content (Articles) -->
        <div class="col-lg-12">
            <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                <h3 class="fw-bold m-0 d-flex align-items-center gap-3">
                    <span class="p-2 bg-primary rounded-3 text-white">
                        <i class="bi bi-list-task"></i>
                    </span>
                    İçindekiler
                </h3>
                <div class="text-muted small">
                    Toplam <strong><?= !empty($makaleler) ? count($makaleler) : 0 ?></strong> akademik çalışma
                </div>
            </div>

            <?php if (!empty($makaleler)): ?>
                <?php foreach ($makaleler as $index => $makale): ?>
                    <div class="article-card">
                        <span class="article-number"><?= sprintf('%02d', $index + 1) ?></span>
                        <div class="row align-items-center g-4 article-content">
                            <div class="col-md-9">
                                <h5 class="fw-bold mb-3 text-uppercase-custom">
                                    <?php if ($makale->makale_turu == 2): ?>
                                        <a href="/makale/<?= $makale->id ?>" class="text-decoration-none text-dark hover-primary transition-all">
                                            <?= e($makale->makale_baslik) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= e($makale->makale_baslik) ?>
                                    <?php endif; ?>
                                </h5>

                                <div class="d-flex flex-wrap gap-4 mb-3">
                                    <?php if (!empty($makale->makale_yazar)): ?>
                                        <div class="meta-item">
                                            <i class="bi bi-person-circle text-primary"></i>
                                            <strong><?= e($makale->makale_yazar) ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="meta-item">
                                        <i class="bi bi-tag"></i>
                                        <span>Makale</span>
                                    </div>
                                </div>

                                <?php if (!empty($makale->makale_ozet)): ?>
                                    <p class="text-secondary small mb-0 line-clamp-2">
                                        <?= str_limit(strip_tags($makale->makale_ozet), 250) ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3 text-md-end">
                                <?php if (!empty($makale->dosya)): ?>
                                    <a href="<?= get_image_url($makale->dosya) ?>" 
                                       class="pdf-btn text-decoration-none w-100 justify-content-center" 
                                       target="_blank">
                                        <i class="bi bi-file-pdf"></i> Tam Metin (PDF)
                                    </a>
                                    <button class="btn btn-outline-danger w-100 mt-2 d-flex align-items-center justify-content-center" 
                                            style="border-radius: 50px; padding: 8px 25px; font-weight: 600;"
                                            onclick="openPdfPreview('<?= get_image_url($makale->dosya) ?>')">
                                        <i class="bi bi-eye me-2"></i> Önizle
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($makale->makale_turu == 2): ?>
                                    <a href="/makale/<?= $makale->id ?>" class="btn btn-link btn-sm text-secondary mt-2 text-decoration-none">
                                        Detayları Görüntüle <i class="bi bi-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-light border text-center py-5 rounded-4 shadow-sm">
                    <i class="bi bi-inbox display-4 d-block mb-3 text-muted"></i>
                    <p class="lead text-muted mb-0">Bu sayıda henüz makale yayınlanmamıştır.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-5 text-center">
        <a href="/dergiler" class="btn btn-outline-secondary px-5 py-2 rounded-pill">
            <i class="bi bi-arrow-left me-2"></i> Tüm Arşiv ve Sayılar
        </a>
    </div>
</div>

<!-- PDF Preview Modal -->
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title"><i class="bi bi-file-earmark-pdf me-2"></i> Makale Önizleme</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light">
                <div id="pdfContainer" class="h-100 w-100 d-flex align-items-center justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openPdfPreview(url) {
    const modalElement = document.getElementById('pdfPreviewModal');
    const modal = new bootstrap.Modal(modalElement);
    const container = document.getElementById('pdfContainer');
    
    // Clear previous content
    container.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Yükleniyor...</span></div>';
    
    modal.show();

    // Load PDF
    setTimeout(() => {
        container.innerHTML = `
            <object data="${url}" type="application/pdf" width="100%" height="100%">
                <div class="text-center p-5">
                    <p class="mb-3">Tarayıcınız PDF önizlemeyi desteklemiyor.</p>
                    <a href="${url}" class="btn btn-primary" target="_blank">Dosyayı İndir</a>
                </div>
            </object>
        `;
    }, 500);
    
    // Cleanup
    modalElement.addEventListener('hidden.bs.modal', function () {
        container.innerHTML = '';
    });
}
</script>

