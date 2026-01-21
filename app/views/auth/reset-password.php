<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 mt-5">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-shield-lock fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Yeni Şifre Belirle</h3>
                    <p class="text-muted small">Lütfen yeni şifrenizi girin.</p>
                </div>

                <form action="/sifre-sifirla" method="POST" class="needs-validation" novalidate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Yeni Şifre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control bg-light border-0" placeholder="En az 6 karakter" required minlength="6">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label small fw-semibold">Şifre Tekrar</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control bg-light border-0" placeholder="Şifrenizi tekrar girin" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success py-2 rounded-3 fw-semibold">
                            Şifreyi Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
