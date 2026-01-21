<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Destek Taleplerim', 'url' => '/support'],
    ['text' => 'Yeni Talep', 'url' => '']
];
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                        <i class="bi bi-headset fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">Yeni Destek Talebi</h3>
                        <p class="text-muted mb-0">Sorularınızı veya feedbacklerinizi bize iletin.</p>
                    </div>
                </div>

                <form action="/support/store" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Konu <span class="text-danger">*</span></label>
                        <input type="text" name="konu" class="form-control bg-light border-0 py-3 rounded-3" 
                               placeholder="Talebinizin kısa özeti" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Mesajınız <span class="text-danger">*</span></label>
                        <textarea name="mesaj" class="form-control bg-light border-0 py-3 rounded-3" 
                                  rows="6" placeholder="Detaylı açıklama..." required></textarea>
                    </div>

                    <div class="alert alert-light border-0 rounded-4 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle text-primary fs-4 me-3"></i>
                            <div class="small">
                                Talebiniz editör ekibimize iletilecek ve yanıtlandığında kayıtlı e-posta adresinize (<?= e($_SESSION['user_email'] ?? '') ?>) bildirim gönderilecektir.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-3 rounded-pill fw-bold">
                            Talebi Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
