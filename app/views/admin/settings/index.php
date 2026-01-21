<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Genel Ayarlar', 'url' => '#']
];
?>

<div class="row justify-content-center py-5">
    <div class="col-md-10 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 p-4">
                <h4 class="fw-bold mb-0">
                    <i class="bi bi-gear-fill text-primary me-2"></i> Site Ayarları
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="/admin/settings/update" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Site Kimliği -->
                    <h6 class="text-secondary fw-bold border-bottom pb-2 mb-4">Site Kimliği</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Site Adı (Domain)</label>
                            <input type="text" name="SiteAdi" class="form-control" value="<?= e($settings->SiteAdi) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site Başlığı (Title)</label>
                            <input type="text" name="SiteTitle" class="form-control" value="<?= e($settings->SiteTitle) ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Site Açıklaması (Description)</label>
                        <textarea name="SiteDescription" class="form-control" rows="2"><?= e($settings->SiteDescription) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Anahtar Kelimeler (Keywords)</label>
                        <textarea name="SiteKeywords" class="form-control" rows="2"><?= e($settings->SiteKeywords) ?></textarea>
                        <div class="form-text">Virgülle ayırarak yazınız.</div>
                    </div>

                    <!-- Görseller -->
                    <h6 class="text-secondary fw-bold border-bottom pb-2 mb-4 mt-5">Görseller ve Footer</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Site Logosu</label>
                            <div class="d-flex align-items-center gap-3">
                                <?php if($settings->siteLogosu): ?>
                                    <img src="<?= get_image_url($settings->siteLogosu) ?>" class="bg-light rounded p-2" height="50">
                                <?php endif; ?>
                                <input type="file" name="siteLogosu" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Copyright Metni</label>
                            <input type="text" name="siteCopyRightMetni" class="form-control" value="<?= e($settings->siteCopyRightMetni) ?>">
                        </div>
                    </div>

                    <!-- İletişim & SMTP -->
                    <h6 class="text-secondary fw-bold border-bottom pb-2 mb-4 mt-5">İletişim ve E-posta (SMTP)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">İletişim E-posta</label>
                            <input type="email" name="SiteEmailAdresi" class="form-control" value="<?= e($settings->SiteEmailAdresi) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" name="SiteEmailHost" class="form-control" value="<?= e($settings->SiteEmailHost) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">SMTP Şifre</label>
                            <input type="password" name="SiteEmailSifresi" class="form-control" value="<?= e($settings->SiteEmailSifresi) ?>">
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save me-2"></i> Ayarları Kaydet
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
