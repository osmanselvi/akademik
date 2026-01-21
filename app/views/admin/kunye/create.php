<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dergi Künyesi', 'url' => '/admin/kunye'],
    ['text' => 'Yeni Kayıt', 'url' => '/admin/kunye/create']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle text-primary me-2"></i> Yeni Künye Kaydı
                    </h3>

                    <form action="/admin/kunye/store" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="baslik_id" class="form-select bg-light border-0 py-3 rounded-3" required>
                                <option value="">Kategori Seçiniz</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= old('baslik_id') == $cat->id ? 'selected' : '' ?>>
                                        <?= e($cat->baslik) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (has_error('baslik_id')): ?>
                                <div class="invalid-feedback d-block"><?= error('baslik_id') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">İçerik <span class="text-danger">*</span></label>
                            <textarea name="ad_soyad" class="form-control bg-light border-0 py-3 rounded-3" rows="3" required><?= old('ad_soyad') ?></textarea>
                            <small class="text-muted">Ad soyad, adres, telefon, e-posta, ISSN vb. bilgileri giriniz.</small>
                            <?php if (has_error('ad_soyad')): ?>
                                <div class="invalid-feedback d-block"><?= error('ad_soyad') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" 
                                       <?= old('is_approved', '1') ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3">
                                <i class="bi bi-check-circle me-1"></i> Kaydet
                            </button>
                            <a href="/admin/kunye" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                <i class="bi bi-x-circle me-1"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
