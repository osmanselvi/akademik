<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Kullanıcı Yönetimi', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-people text-primary me-2"></i> Kullanıcı Yönetimi
                    </h4>
                    <span class="badge bg-light text-secondary rounded-pill px-3 py-2">
                        Toplam <?= count($users) ?> Kullanıcı
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Ad Soyad</th>
                                    <th>E-posta</th>
                                    <th>Role</th>
                                    <th>Durum</th>
                                    <th class="text-end pe-4">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="ps-4 text-secondary">#<?= $u->id ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-soft-primary text-primary me-3 d-flex align-items-center justify-content-center">
                                                <?= strtoupper(substr($u->ad_soyad, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= e($u->ad_soyad) ?></div>
                                                <small class="text-muted"><?= e($u->email) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(isset($u->is_verified) && $u->is_verified): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle me-1"></i> Doğrulanmış</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-clock me-1"></i> Bekliyor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3">
                                            <?= e($u->grupadi ?? 'Kullanıcı') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if(isset($u->is_active) && $u->is_active): ?>
                                            <span class="badge bg-success rounded-pill">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill">Pasif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="/admin/users/edit/<?= $u->id ?>" class="btn btn-sm btn-outline-primary border shadow-sm">
                                                <i class="bi bi-pencil-square me-1"></i> Düzenle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-secondary small">
                    <?= $paginator->getInfo() ?>
                </div>
                <div>
                    <?= $paginator->getLinks() ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(52, 152, 219, 0.1); }
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    font-weight: bold;
}
</style>
