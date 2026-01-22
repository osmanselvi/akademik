<div class="container py-5">
    <div class="row" data-aos="fade-up">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                    <p class="text-secondary small mb-3">
                           ;

                        <?php

                        function kullaniciRolu($role) {
                            if ($role == 1) {
                                return 'Yönetici';
                            } elseif ($role == 2) {
                                return 'Editör';
                            } elseif ($role == 3) {
                                return 'Hakem';
                            } elseif ($role == 4) {
                                return 'Araştırmacı/Yazar';
                            } elseif ($role == 5) {
                                return 'Üye';
                            } else {
                                return 'Web Site Ziyaretçisi';
                            }
                        }
                        echo kullaniciRolu($user['role']);
?>
                    </p>
                    <hr>
                    <nav class="nav flex-column text-start gap-2">
                        <a class="nav-link active rounded-3 bg-light" href="/dashboard">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link text-dark" href="/profil">
                            <i class="bi bi-person me-2"></i> Profilim
                        </a>
                        <a class="nav-link text-dark" href="/submissions">
                            <i class="bi bi-cloud-arrow-up me-2"></i> Makale Gönderilerim
                        </a>
                        <a class="nav-link text-dark" href="/support">
                            <i class="bi bi-headset me-2"></i> Destek Taleplerim
                        </a>
                        <?php if (isAdmin() || isEditor()): ?>
                            <hr class="my-2">
                            <h6 class="small text-secondary px-3 mb-2">YÖNETİM</h6>
                            <?php if (isAdmin()): ?>
                            <a class="nav-link text-dark" href="/admin/users">
                                <i class="bi bi-people me-2"></i> Kullanıcı Yönetimi
                            </a>
                            <?php endif; ?>
                            <a class="nav-link text-dark" href="/admin/kurul">
                                <i class="bi bi-person-lines-fill me-2"></i> Dergi Kurulları
                            </a>
                            <a class="nav-link text-dark" href="/admin/kunye">
                                <i class="bi bi-file-text me-2"></i> Dergi Künyesi
                            </a>
                            <a class="nav-link text-dark" href="/admin/dizin">
                                <i class="bi bi-database me-2"></i> Dizinler
                            </a>
                            <a class="nav-link text-dark" href="/admin/makale-esas">
                                <i class="bi bi-bookmark-check me-2"></i> Makale Esasları
                            </a>
                            <a class="nav-link text-dark" href="/admin/makale-sozlesme">
                                <i class="bi bi-file-earmark-check me-2"></i> Telif Sözleşmesi
                            </a>
                            <a class="nav-link text-dark" href="/admin/dergi-tanim">
                                <i class="bi bi-journal-album me-2"></i> Dergi Sayıları
                            </a>
                            <a class="nav-link text-dark" href="/admin/online-makale">
                                <i class="bi bi-file-earmark-text me-2"></i> Yayımlanmış Makaleler
                            </a>
                            <a class="nav-link text-dark" href="/admin/submissions">
                                <i class="bi bi-file-earmark-medical me-2"></i> Hakem Süreci
                            </a>
                            <a class="nav-link text-dark font-weight-bold text-primary" href="/admin/support">
                                <i class="bi bi-headset me-2"></i> Destek Yönetimi
                            </a>
                        <?php endif; ?>
                        <a class="nav-link text-danger" href="/logout">
                            <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="welcome-banner p-5 mb-4 rounded-4 text-white bg-gradient-primary">
                <h2 class="fw-bold mb-2">Hoş Geldiniz, <?= explode(' ', $user['name'])[0] ?>!</h2>
                <p class="opacity-75 mb-0">EBP Dergi yönetim sistemine başarıyla giriş yaptınız. Buradan makalelerinizi yönetebilir ve profil bilgilerinizi güncelleyebilirsiniz.</p>
            </div>

            <?php
            $db = getDatabase();
            $counts = [
                'total' => $db->query("SELECT COUNT(*) FROM gonderilen_makale WHERE created_by = {$_SESSION['user_id']}")->fetchColumn(),
                'pending' => $db->query("SELECT COUNT(*) FROM gonderilen_makale WHERE created_by = {$_SESSION['user_id']} AND status IN (0, 1, 2)")->fetchColumn(),
                'approved' => $db->query("SELECT COUNT(*) FROM gonderilen_makale WHERE created_by = {$_SESSION['user_id']} AND status = 3")->fetchColumn(),
            ];
            ?>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 card-stat">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon mb-3 text-primary bg-light-primary rounded-circle mx-auto">
                                <i class="bi bi-file-earmark-plus fs-3"></i>
                            </div>
                            <h3 class="fw-bold mb-1"><?= $counts['total'] ?></h3>
                            <p class="text-secondary mb-0">Gönderilen Makale</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 card-stat">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon mb-3 text-warning bg-light-warning rounded-circle mx-auto">
                                <i class="bi bi-hourglass-split fs-3"></i>
                            </div>
                            <h3 class="fw-bold mb-1"><?= $counts['pending'] ?></h3>
                            <p class="text-secondary mb-0">Hakem Sürecinde</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 rounded-4 card-stat">
                        <div class="card-body p-4 text-center">
                            <div class="stat-icon mb-3 text-success bg-light-success rounded-circle mx-auto">
                                <i class="bi bi-check-circle fs-3"></i>
                            </div>
                            <h3 class="fw-bold mb-1"><?= $counts['approved'] ?></h3>
                            <p class="text-secondary mb-0">Onaylanmış</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-5">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="fw-bold mb-0">Son Aktiviteler</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item p-4 border-0 text-center text-muted">
                            <p class="mb-0">Henüz bir aktivite kaydı bulunmuyor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
}
.bg-light-primary { background-color: rgba(52, 152, 219, 0.1); }
.bg-light-warning { background-color: rgba(241, 196, 15, 0.1); }
.bg-light-success { background-color: rgba(46, 204, 113, 0.1); }
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-stat {
    transition: transform 0.3s ease;
}
.card-stat:hover {
    transform: translateY(-5px);
}
</style>
