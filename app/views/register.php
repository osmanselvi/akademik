<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-2">Kayıt Ol</h2>
                        <p class="text-muted">Edebiyat Bilimleri Dergisi'ne hoş geldiniz</p>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/register" id="registerForm">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="Adınız ve soyadınız" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">E-posta <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="ornek@email.com" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Şifre <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="En az 6 karakter" required minlength="6">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Şifre Tekrar <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirm" class="form-control bg-light border-0 py-3 rounded-3" 
                                   placeholder="Şifrenizi tekrar girin" required>
                        </div>

                        <div class="mb-4">
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="accept_terms" id="acceptTerms" required>
                                            <label class="form-check-label small" for="acceptTerms">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#agreementModal" class="text-decoration-none">
                                                    Üyelik Sözleşmesi
                                                </a>ni okudum ve kabul ediyorum
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold">
                            <i class="bi bi-person-plus me-2"></i> Kayıt Ol
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Zaten hesabınız var mı? 
                                <a href="/login" class="text-decoration-none fw-bold">Giriş Yapın</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Üyelik Sözleşmesi Modal -->
<div class="modal fade" id="agreementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-file-text me-2"></i> Üyelik Sözleşmesi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <?php include __DIR__ . '/../../include/dergi/uyelik_sozlesme.php'; ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" onclick="acceptAgreement()">
                    <i class="bi bi-check-circle me-2"></i> Kabul Ediyorum
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function acceptAgreement() {
    document.getElementById('acceptTerms').checked = true;
    bootstrap.Modal.getInstance(document.getElementById('agreementModal')).hide();
}

// Şifre eşleşme kontrolü
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.querySelector('input[name="password"]').value;
    const confirm = document.querySelector('input[name="password_confirm"]').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Şifreler eşleşmiyor!');
        return false;
    }
});
</script>
