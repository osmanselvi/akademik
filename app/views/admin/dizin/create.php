<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dizinler', 'url' => '/admin/dizin'],
    ['text' => 'Yeni Dizin', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-plus-circle text-primary me-2"></i> Yeni Dizin Ekle
                    </h3>

                    <form action="/admin/dizin/store" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Dizin Adı <span class="text-danger">*</span></label>
                            <input type="text" name="dizin_adi" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="örn: Index Copernicus, Google Scholar" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Link (Opsiyonel)</label>
                            <input type="url" name="dizin_link" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="https://...">
                            <small class="text-muted">Dizin web sitesi adresi</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Logo (Opsiyonel)</label>
                            <input type="file" name="dizin_logo" class="form-control bg-light border-0 py-3 rounded-3" 
                                   accept="image/*">
                            <small class="text-muted">Logo resmi (.jpg, .png formatında)</small>
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
