<div class="login-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" data-aos="zoom-in">
                    <div class="row g-0">
                        <div class="col-12 p-5">
                            <div class="text-center mb-5">
                                <div class="login-icon mb-3">
                                    <i class="bi bi-person-lock fs-1 text-primary"></i>
                                </div>
                                <h2 class="fw-bold">Yönetim Paneli</h2>
                                <p class="text-secondary">Hesabınıza giriş yapın</p>
                            </div>
                                <?php if (isset($_GET['ref']) && $_GET['ref'] == '/submissions/create'): ?>
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Dikkat:</strong> Eğer makale göndermek istiyorsanız giriş yapmalısınız veya araştırmacı olarak kayıt olmalısınız. Lütfen giriş yaptıktan sonra yeniden makale göndermeyi deneyiniz.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>
                            <?php if (isset($error)): ?>
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                            </div>
                            <?php endif; ?>

                            <form action="/login" method="POST" class="needs-validation" novalidate>
                                <?= csrf_field() ?>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" class="form-control <?= (isset($errors['email'])) ? 'is-invalid' : '' ?>" id="floatingInput" placeholder="name@example.com" required>
                                    <label for="floatingInput">E-posta adresi</label>
                                    <?php if (isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?= $errors['email'][0] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" name="password" class="form-control <?= (isset($errors['password'])) ? 'is-invalid' : '' ?>" id="floatingPassword" placeholder="Şifre" required>
                                    <label for="floatingPassword">Şifre</label>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?= $errors['password'][0] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label text-secondary small" for="rememberMe">
                                            Beni Hatırla
                                        </label>
                                    </div>
                                    <a href="/sifremi-unuttum" class="small text-decoration-none">Şifremi Unuttum</a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">
                                    Giriş Yap <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </form>

                            <div class="mt-5 text-center">
                                <p class="text-secondary small mb-0">Hesabınız yok mu?</p>
                                <a href="/kayit" class="btn btn-link text-decoration-none fw-bold">Kayıt Olun</a>
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
.login-icon i {
    filter: drop-shadow(0 4px 6px rgba(52, 152, 219, 0.2));
}
</style>
