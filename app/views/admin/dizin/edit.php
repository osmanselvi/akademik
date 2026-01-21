<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dizinler', 'url' => '/admin/dizin'],
    ['text' => 'Düzenle', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-pencil text-primary me-2"></i> Dizin Düzenle
                    </h3>

                    <form action="/admin/dizin/update/<?= $dizin->id ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Dizin Adı <span class="text-danger">*</span></label>
                            <input type="text" name="dizin_adi" class="form-control bg-light border-0 py-3 rounded-3" 
                                   value="<?= e($dizin->dizin_adi) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Link (Opsiyonel)</label>
                            <input type="url" name="dizin_link" class="form-control bg-light border-0 py-3 rounded-3" 
                                   value="<?= e($dizin->dizin_link ?? '') ?>">
                            <small class="text-muted">Dizin web sitesi adresi</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Logo (Opsiyonel)</label>
                            <?php if (isset($dizin->dizin_logo) && $dizin->dizin_logo): ?>
                                <div class="mb-2">
                                    <img src="/images/<?= e($dizin->dizin_logo) ?>" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="dizin_logo" class="form-control bg-light border-0 py-3 rounded-3" 
                                   accept="image/*">
                            <small class="text-muted">Logo resmi (.jpg, .png formatında) - Mevcut logoyu değiştirmek için yeni dosya seçin</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" 
                                       <?= $dizin->is_approved ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3">
                                <i class="bi bi-check-circle me-1"></i> Güncelle
                            </button>
                            <a href="/admin/dizin" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                <i class="bi bi-x-circle me-1"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
