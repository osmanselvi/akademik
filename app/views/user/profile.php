<div class="container py-5">
    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4">Profil Ayarları</h2>
            
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="alert alert-info border-0 rounded-4 mb-4 shadow-sm">
                    <i class="bi bi-info-circle me-2"></i> <?= e($_SESSION['flash_message']) ?>
                </div>
                <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>

            <!-- Basic Info -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Genel Bilgiler</h5>
                    <form action="/profil/guncelle" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <div class="row g-3">
                            <div class="col-md-6 text-center mb-4">
                                <div class="avatar-lg rounded-circle bg-soft-primary text-primary mx-auto mb-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person fs-1"></i>
                                </div>
                                <span class="badge bg-light text-primary rounded-pill">
                                    <?= e($group->grupadi ?? 'Kullanıcı') ?>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">AD SOYAD</label>
                                    <input type="text" name="ad_soyad" class="form-control rounded-3 <?= has_error('ad_soyad') ? 'is-invalid' : '' ?>" value="<?= e(old('ad_soyad', $user->ad_soyad)) ?>" required minlength="3">
                                    <?php if (has_error('ad_soyad')): ?>
                                        <div class="invalid-feedback"><?= error('ad_soyad') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">E-POSTA ADRESİ</label>
                                    <input type="email" name="email" class="form-control rounded-3 <?= has_error('email') ? 'is-invalid' : '' ?>" value="<?= e(old('email', $user->email)) ?>" required>
                                    <?php if (has_error('email')): ?>
                                        <div class="invalid-feedback"><?= error('email') ?></div>
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 mt-2">
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>

            <!-- E-Signature Management -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">E-İmza Yönetimi</h5>
                    
                    <?php if (!empty($user->e_imza)): ?>
                        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success d-flex align-items-center mb-3">
                            <i class="bi bi-patch-check-fill fs-4 me-3"></i>
                            <div>
                                <strong>E-İmzanız Oluşturulmuş</strong><br>
                                <span class="small">Telif devir sözleşmelerini bu imza ile onaylayabilirsiniz.</span>
                            </div>
                        </div>
                        <div class="accordion" id="accordionSignature">
                            <div class="accordion-item border-0">
                                <h2 class="accordion-header" id="headingSig">
                                    <button class="accordion-button collapsed bg-light rounded-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSig">
                                        <i class="bi bi-key me-2"></i> İmza Kodunu Görüntüle
                                    </button>
                                </h2>
                                <div id="collapseSig" class="accordion-collapse collapse" data-bs-parent="#accordionSignature">
                                    <div class="accordion-body bg-light mt-2 rounded-3">
                                        <pre class="small mb-0 text-muted" style="max-height: 150px; overflow-y: auto;"><?= e($user->e_imza) ?></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning mb-3">
                             <i class="bi bi-exclamation-circle me-2"></i> Henüz bir e-imzanız bulunmamaktadır. Makale göndermek için e-imza oluşturmalısınız.
                        </div>
                        <div class="text-center py-3">
                            <form action="/profil/imza-olustur" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-warning text-white px-4 py-2 rounded-3 shadow-sm">
                                    <i class="bi bi-magic me-2"></i> Benzersiz E-İmza Oluştur
                                </button>
                            </form>
                            <p class="small text-muted mt-2">İmzanız, adınız-soyadınız ve e-posta adresiniz kullanılarak kriptografik olarak üretilecektir.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Şifre Değiştir</h5>
                    <form action="/profil/sifre" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">MEVCUT ŞİFRE</label>
                            <input type="password" name="current_password" class="form-control rounded-3 <?= has_error('current_password') ? 'is-invalid' : '' ?>" required>
                            <?php if (has_error('current_password')): ?>
                                <div class="invalid-feedback"><?= error('current_password') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">YENİ ŞİFRE</label>
                                    <input type="password" name="new_password" class="form-control rounded-3 <?= has_error('new_password') ? 'is-invalid' : '' ?>" required minlength="6">
                                    <?php if (has_error('new_password')): ?>
                                        <div class="invalid-feedback"><?= error('new_password') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">YENİ ŞİFRE (TEKRAR)</label>
                                    <input type="password" name="confirm_password" class="form-control rounded-3 <?= has_error('confirm_password') ? 'is-invalid' : '' ?>" required minlength="6">
                                    <?php if (has_error('confirm_password')): ?>
                                        <div class="invalid-feedback"><?= error('confirm_password') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary px-4 py-2 rounded-3">
                            Şifremi Güncelle
                        </button>
                    </form>
                    <?php clear_validation(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 100px;
    height: 100px;
    background-color: rgba(52, 152, 219, 0.1);
}
.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.1);
}
</style>
