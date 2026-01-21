<div class="login-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" data-aos="zoom-in">
                    <div class="row g-0">
                        <div class="col-12 p-5">
                            <div class="text-center mb-5">
                                <div class="login-icon mb-3">
                                    <i class="bi bi-person-plus fs-1 text-primary"></i>
                                </div>
                                <h2 class="fw-bold">Yeni Kayıt</h2>
                                <p class="text-secondary">Edebiyat Bilimleri Dergisi sistemine katılın</p>
                                <div class="alert alert-warning border-0 rounded-3 small mb-0">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Yahoo mail adresleri kabul edilmemektedir.
                                </div>
                            </div>

                            <?php if (isset($_SESSION['flash_message'])): ?>
                            <div class="alert alert-info border-0 rounded-3 mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i> <?= e($_SESSION['flash_message']) ?>
                            </div>
                            <?php unset($_SESSION['flash_message']); ?>
                            <?php endif; ?>

                             <form action="/kayit" method="POST" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="form-floating mb-3">
                                    <input type="text" name="ad_soyad" class="form-control <?= has_error('ad_soyad') ? 'is-invalid' : '' ?>" id="floatingName" placeholder="Ad Soyad" value="<?= e(old('ad_soyad')) ?>" required minlength="3">
                                    <label for="floatingName">Ad Soyad</label>
                                    <?php if (has_error('ad_soyad')): ?>
                                        <div class="invalid-feedback"><?= error('ad_soyad') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control <?= has_error('email') ? 'is-invalid' : '' ?>" id="floatingInput" placeholder="name@example.com" value="<?= e(old('email')) ?>" required>
                                    <label for="floatingInput">E-posta adresi</label>
                                    <?php if (has_error('email')): ?>
                                        <div class="invalid-feedback"><?= error('email') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" class="form-control <?= has_error('password') ? 'is-invalid' : '' ?>" id="floatingPassword" placeholder="Şifre" required minlength="6">
                                    <label for="floatingPassword">Şifre</label>
                                    <?php if (has_error('password')): ?>
                                        <div class="invalid-feedback"><?= error('password') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" name="password_confirm" class="form-control <?= has_error('password_confirm') ? 'is-invalid' : '' ?>" id="floatingPasswordConfirm" placeholder="Şifre Onay" required>
                                    <label for="floatingPasswordConfirm">Şifre Tekrar</label>
                                    <?php if (has_error('password_confirm')): ?>
                                        <div class="invalid-feedback"><?= error('password_confirm') ?></div>
                                    <?php endif; ?>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                    Kayıt Ol <i class="bi bi-check2-circle ms-2"></i>
                                </button>
                            </form>
                            <?php clear_validation(); ?>

                            <div class="mt-5 text-center">
                                <p class="text-secondary small mb-0">Zaten hesabınız var mı?</p>
                                <a href="/login" class="btn btn-link text-decoration-none fw-bold">Giriş Yapın</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="/" class="text-secondary text-decoration-none small">
                        <i class="bi bi-house-door me-1"></i> Ana Sayfaya Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.login-page {
    background: radial-gradient(circle at top right, rgba(52, 152, 219, 0.05) 0%, transparent 20%),
                radial-gradient(circle at bottom left, rgba(46, 204, 113, 0.05) 0%, transparent 20%);
}
.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.1);
    border-color: #3498db;
}
</style>
