<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dergi Künyesi', 'url' => '/admin/kunye'],
    ['text' => 'Düzenle', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-pencil text-primary me-2"></i> Künye Kaydını Düzenle
                    </h3>

                    <form action="/admin/kunye/update/<?= $entry->id ?>" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                            <select name="baslik_id" class="form-select bg-light border-0 py-3 rounded-3" required>
                                <option value="">Kategori Seçiniz</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= $entry->baslik_id == $cat->id ? 'selected' : '' ?>>
                                        <?= e($cat->baslik) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">İçerik <span class="text-danger">*</span></label>
                            <textarea name="ad_soyad" class="form-control bg-light border-0 py-3 rounded-3" rows="3" required><?= e($entry->ad_soyad) ?></textarea>
                            <small class="text-muted">Ad soyad, adres, telefon, e-posta, ISSN vb. bilgileri giriniz.</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" 
                                       <?= $entry->is_approved ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3">
                                <i class="bi bi-check-circle me-1"></i> Güncelle
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
