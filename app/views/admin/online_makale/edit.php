<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Yayımlanmış Makaleler', 'url' => '/admin/online-makale'],
    ['text' => 'Düzenle', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-pencil text-primary me-2"></i> Yayımlanmış Makaleyi Düzenle
                    </h3>

                    <form action="/admin/online-makale/update/<?= $item->id ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <label class="form-label fw-bold">Makale Başlığı <span class="text-danger">*</span></label>
                                <input type="text" name="makale_baslik" class="form-control bg-light border-0 py-3 rounded-3" 
                                       value="<?= e($item->makale_baslik) ?>" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Makale Türü <span class="text-danger">*</span></label>
                                <select name="makale_turu" class="form-select bg-light border-0 py-3 rounded-3" required>
                                    <?php foreach($turler as $tur): ?>
                                        <option value="<?= $tur->id ?>" <?= $item->makale_turu == $tur->id ? 'selected' : '' ?>>
                                            <?= e($tur->makale_turu) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Yazar(lar) <span class="text-danger">*</span></label>
                                <input type="text" name="makale_yazar" class="form-control bg-light border-0 py-3 rounded-3" 
                                       value="<?= e($item->makale_yazar) ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Dergi Sayısı <span class="text-danger">*</span></label>
                                <select name="dergi_tanim" class="form-select bg-light border-0 py-3 rounded-3" required>
                                    <?php foreach($dergiler as $dergi): ?>
                                        <option value="<?= $dergi->id ?>" <?= $item->dergi_tanim == $dergi->id ? 'selected' : '' ?>>
                                            <?= e($dergi->dergi_tanim) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Özet (Abstract)</label>
                            <textarea name="makale_ozet" class="form-control bg-light border-0 rounded-3" 
                                      rows="6"><?= e($item->makale_ozet) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Kaynakça (References)</label>
                            <textarea name="kaynakca" class="form-control bg-light border-0 rounded-3" 
                                      rows="6"><?= e($item->kaynakca) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Anahtar Kelimeler</label>
                                <input type="text" name="anahtar_kelime" class="form-control bg-light border-0 py-3 rounded-3" 
                                       value="<?= e($item->anahtar_kelime) ?>">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Atıf Künyesi (Kısa Ad)</label>
                                <input type="text" name="kisaad" class="form-control bg-light border-0 py-3 rounded-3" 
                                       value="<?= e($item->kisaad) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Yayın Tarihi</label>
                                <input type="date" name="yayin_created_at" class="form-control bg-light border-0 py-3 rounded-3" 
                                       value="<?= e($item->yayin_created_at) ?>">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">PDF Dosyası (Değiştirmek için dosya seçin)</label>
                                <input type="file" name="dosya" class="form-control bg-light border-0 py-3 rounded-3" accept=".pdf">
                                <?php if ($item->dosya): ?>
                                    <div class="mt-2 small text-success">
                                        <i class="bi bi-check-circle"></i> Mevcut: 
                                        <a href="<?= get_image_url($item->dosya) ?>" target="_blank" class="text-decoration-none">
                                            <?= e($item->dosya) ?> <i class="bi bi-box-arrow-up-right small"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" 
                                       <?= $item->is_approved ? 'checked' : '' ?>>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında (Aktif)</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Güncelle
                            </button>
                            <a href="/admin/online-makale" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
