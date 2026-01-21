<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Makale Esasları', 'url' => '/admin/makale-esas'],
    ['text' => 'Yeni Esas', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle text-primary me-2"></i> Yeni Makale Esası Ekle
                    </h3>

                    <form action="/admin/makale-esas/store" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Esas Türü / Başlık <span class="text-danger">*</span></label>
                            <input type="text" name="esas_turu" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="örn: Genel Yazım Kuralları, Kaynak Gösterme" required>
                            <small class="text-muted">Makale esasının başlığı</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Açıklama / İçerik <span class="text-danger">*</span></label>
                            <textarea name="aciklama" class="form-control bg-light border-0 rounded-3" 
                                      rows="15" required></textarea>
                            <small class="text-muted">
                                Makale esasının detaylı açıklaması. HTML etiketleri kullanabilirsiniz.
                                <br><strong>Özel ID'ler:</strong> ID=11 → yzkural.php dosyasını include eder | ID=12 → Her satırı liste elemanı yapar
                            </small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" checked>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3">
                                <i class="bi bi-check-circle me-1"></i> Kaydet
                            </button>
                            <a href="/admin/makale-esas" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                <i class="bi bi-x-circle me-1"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
