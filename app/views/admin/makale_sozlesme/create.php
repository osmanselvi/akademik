<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Telif Devir Sözleşmesi', 'url' => '/admin/makale-sozlesme'],
    ['text' => 'Yeni Madde', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle text-primary me-2"></i> Yeni Sözleşme Maddesi Ekle
                    </h3>

                    <form action="/admin/makale-sozlesme/store" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Başlık / Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="baslik" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="örn: TARAFLAR, YÜKÜMLÜLÜKLER" required>
                            <small class="text-muted">Aynı başlığa sahip maddeler gruplanarak gösterilir.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Madde Metni <span class="text-danger">*</span></label>
                            <textarea name="metin" class="form-control bg-light border-0 rounded-3" 
                                      rows="8" required placeholder="Sözleşme maddesi içeriği..."></textarea>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved" checked>
                                <label class="form-check-label fw-bold" for="isApproved">Yayında (Onaylı)</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 fw-bold">
                                <i class="bi bi-check-circle me-1"></i> Kaydet
                            </button>
                            <a href="/admin/makale-sozlesme" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
