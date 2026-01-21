<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 mt-5">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-key fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Şifremi Unuttum</h3>
                    <p class="text-muted small">E-posta adresinizi girin, size şifre sıfırlama bağlantısını gönderelim.</p>
                </div>

                <form action="/sifremi-unuttum" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label for="email" class="form-label small fw-semibold">E-posta Adresi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control bg-light border-0" placeholder="E-posta adresiniz" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 rounded-3 fw-semibold">
                            Sıfırlama Bağlantısı Gönder
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <a href="/login" class="text-decoration-none small">
                            <i class="bi bi-arrow-left"></i> Giriş Sayfasına Dön
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
