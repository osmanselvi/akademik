<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dergi Sayıları', 'url' => '/admin/dergi-tanim'],
    ['text' => 'Yeni Sayı', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle text-primary me-2"></i> Yeni Dergi Sayısı Oluştur
                    </h3>

                    <form action="/admin/dergi-tanim/store" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Dergi Tanımı (Türkçe) <span class="text-danger">*</span></label>
                                <input type="text" name="dergi_tanim" class="form-control bg-light border-0 py-3 rounded-3" 
                                       placeholder="örn: Cilt 1 Sayı 1, 2024" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Yayın Tarihi / İbare <span class="text-danger">*</span></label>
                                <input type="text" name="yayin_created_at" class="form-control bg-light border-0 py-3 rounded-3" 
                                       placeholder="örn: Ocak 2024" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">İngilizce Başlık <span class="text-danger">*</span></label>
                            <input type="text" name="ing_baslik" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="örn: Volume 1 Issue 1, 2024" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Dergi Kapağı (Görsel)</label>
                                <input type="file" name="dergi_kapak" class="form-control bg-light border-0 py-3 rounded-3" accept="image/*">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Jenerik Dosyası (PDF)</label>
                                <input type="file" name="jenerikdosyasi" class="form-control bg-light border-0 py-3 rounded-3" accept=".pdf">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" checked>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında (Aktif)</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Sayıyı Oluştur
                            </button>
                            <a href="/admin/dergi-tanim" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
