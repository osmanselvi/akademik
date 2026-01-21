<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Kullanıcı Yönetimi', 'url' => '/admin/users'],
    ['text' => 'Düzenle', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Kullanıcı Düzenle</h5>
                    <span class="badge bg-secondary"><?= e($user->id) ?></span>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/users/update/<?= $user->id ?>" method="POST" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <!-- Identity Info -->
                        <h6 class="text-primary fw-bold mb-3">Kimlik Bilgileri</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Ad Soyad</label>
                                <input type="text" name="ad_soyad" class="form-control" value="<?= e($user->ad_soyad) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-posta</label>
                                <input type="email" name="email" class="form-control" value="<?= e($user->email) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="telefon" class="form-control" value="<?= e($user->telefon) ?>">
                            </div>
                        </div>

                        <!-- Role & Status -->
                        <h6 class="text-primary fw-bold mb-3">Yetki ve Durum</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-12">
                                <label class="form-label">Kullanıcı Grubu (Rol)</label>
                                <select name="grup_id" class="form-select">
                                    <?php foreach($groups as $group): ?>
                                    <option value="<?= $group->id ?>" <?= $user->grup_id == $group->id ? 'selected' : '' ?>>
                                        <?= e($group->grupadi) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded border">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" <?= $user->is_active ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="isActive">Hesap Aktif</label>
                                    <small class="d-block text-muted">Kapatılırsa kullanıcı giriş yapamaz.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch p-3 bg-light rounded border">
                                    <input class="form-check-input" type="checkbox" name="is_verified" id="isVerified" <?= $user->is_verified ? 'checked' : '' ?>>
                                    <label class="form-check-label fw-bold" for="isVerified">E-posta Doğrulanmış</label>
                                    <small class="d-block text-muted">Rozet görünümü için kullanılır.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Security -->
                        <h6 class="text-danger fw-bold mb-3">Güvenlik</h6>
                        <div class="mb-4 p-3 border border-danger border-opacity-25 bg-danger bg-opacity-10 rounded">
                            <label class="form-label">Yeni Şifre Belirle</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Değiştirmek istemiyorsanız boş bırakın">
                            <small class="text-muted">Kullanıcının şifresini sıfırlamak için burayı doldurun.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger" onclick="deleteUser(<?= $user->id ?>)">
                                <i class="bi bi-trash me-2"></i> Hesabı Sil
                            </button>
                            <div class="d-flex gap-2">
                                <a href="/admin/users" class="btn btn-outline-secondary">İptal</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i> Kaydet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(id) {
    if (confirm('DİKKAT! Bu kullanıcıyı kalıcı olarak silmek istediğinize emin misiniz?')) {
        fetch('/admin/users/delete/' + id, { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            if(data.success) window.location.href = '/admin/users';
            else alert(data.message);
        });
    }
}
</script>
