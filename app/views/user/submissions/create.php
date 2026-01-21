<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Makale Gönderilerim', 'url' => '/submissions'],
    ['text' => 'Yeni Gönderi', 'url' => '']
];
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
            <div class="card-body p-5">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h3 class="fw-bold">
                            <i class="bi bi-cloud-arrow-up text-primary me-2"></i> Yeni Makale Gönder
                        </h3>
                        <p class="text-muted">Makale kabul şartlarını okuduğunuzdan emin olun.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="/submissions" class="btn btn-outline-secondary btn-sm rounded-pill">
                            <i class="bi bi-arrow-left"></i> Vazgeç
                        </a>
                    </div>
                </div>

                <form action="/submissions/store" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label class="form-label fw-bold">Makale Başlığı <span class="text-danger">*</span></label>
                            <input type="text" name="makale_adi" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="Çalışmanın tam başlığı" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Makale Türü <span class="text-danger">*</span></label>
                            <select name="makale_tur" class="form-select bg-light border-0 py-3 rounded-3" required>
                                <option value="">Seçin...</option>
                                <?php foreach($turler as $tur): ?>
                                    <option value="<?= $tur->id ?>"><?= e($tur->makale_turu) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Yazar(lar) Adı Soyadı <span class="text-danger">*</span></label>
                            <input type="text" name="yazar_adi" class="form-control bg-light border-0 py-3 rounded-3" 
                                   value="<?= $_SESSION['user_name'] ?>" required>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold">Yazar Sayısı</label>
                            <input type="number" name="yazar_sayisi" class="form-control bg-light border-0 py-3 rounded-3" 
                                   value="1" min="1">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label fw-bold">Çeviren Adı (Varsa)</label>
                            <input type="text" name="ceviren_adi" class="form-control bg-light border-0 py-3 rounded-3">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kapsam / Konu</label>
                        <input type="text" name="makale_konu" class="form-control bg-light border-0 py-3 rounded-3" 
                               placeholder="Genel alan (örn: Yeni Türk Edebiyatı)">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Anahtar Kelimeler</label>
                        <input type="text" name="anahtar_kelimeler" class="form-control bg-light border-0 py-3 rounded-3" 
                               placeholder="virgül ile ayırın">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Makale Dosyası (.docx, .doc, .pdf) <span class="text-danger">*</span></label>
                        <div class="bg-light p-4 rounded-3 border-2 border-dashed text-center">
                            <i class="bi bi-file-earmark-arrow-up display-5 text-muted mb-3 d-block"></i>
                            <input type="file" name="dosya" class="form-control border-0 bg-transparent" accept=".doc,.docx,.pdf" required>
                            <small class="text-muted mt-2 d-block">Maksimum dosya boyutu: 10MB</small>
                        </div>
                    </div>

                    <!-- E-Signature / Agreement Section -->
                    <div class="card bg-light border-0 rounded-4 mb-4">
                        <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                            <h6 class="fw-bold mb-0 text-warning-emphasis"><i class="bi bi-vector-pen me-2"></i> Telif Devir Sözleşmesi ve E-İmza</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="bg-white p-3 rounded-3 border mb-3" style="max-height: 200px; overflow-y: auto;">
                                <?php if (!empty($sozlesmeMaddeleri)): ?>
                                    <?php foreach($sozlesmeMaddeleri as $baslik => $items): ?>
                                        <h6 class="fw-bold text-primary"><?= e($baslik) ?></h6>
                                        <ul class="list-unstyled small text-muted mb-3">
                                            <?php foreach($items as $item): ?>
                                                <li class="mb-1"><i class="bi bi-check2 me-1"></i> <?= $item->metin ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted my-3">Sözleşme maddeleri yüklenemedi.</p>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($user->e_imza)): ?>
                                <div class="form-check p-3 bg-white border rounded-3 d-flex align-items-center">
                                    <input class="form-check-input fs-4 me-3 mt-0" type="checkbox" name="sozlesme_onay" id="sozlesme_onay" required>
                                    <div>
                                        <label class="form-check-label fw-bold text-dark" for="sozlesme_onay">
                                            Yukarıdaki sözleşme metnini okudum, kabul ediyorum.
                                        </label>
                                        <div class="text-secondary small mt-1">
                                            <i class="bi bi-patch-check-fill text-success"></i> Bu işlem <strong><?= e($user->ad_soyad) ?></strong> adına kayıtlı benzersiz e-imza ile imzalanacaktır.
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger border-0 mb-0 d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill fs-2 me-3"></i>
                                    <div>
                                        <strong>E-İmza Gereklidir!</strong><br>
                                        Makale gönderebilmek için önce profilinizden benzersiz e-imza oluşturmanız gerekmektedir.
                                        <div class="mt-2">
                                            <a href="/profil" class="btn btn-sm btn-danger rounded-pill px-3">
                                                <i class="bi bi-person-gear me-1"></i> İmzayı Şimdi Oluştur
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    // Disable form submission if no signature
                                    document.addEventListener('DOMContentLoaded', function() {
                                        document.querySelector('form').onsubmit = function(e) { e.preventDefault(); alert('Lütfen önce e-imza oluşturunuz.'); };
                                        document.querySelector('button[type="submit"]').disabled = true;
                                    });
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 rounded-4">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Bilgilendirme</h6>
                                <p class="mb-0 small text-secondary">
                                    Gönderdiğiniz makale editör onayı ve hakem süreci sonrasında yayımlanacaktır. 
                                    Süreci panelden takip edebilir, düzeltme taleplerini bu sayfadan yanıtlayabilirsiniz.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 rounded- pill fw-bold">
                            <i class="bi bi-send me-1"></i> Makaleyi Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-dashed { border-style: dashed !important; }
</style>
