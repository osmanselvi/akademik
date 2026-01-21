<?php
// Breadcrumb data
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Kurul Üyeleri', 'url' => '/admin/kurul']
];
?>

<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-lines-fill text-primary me-2"></i> Kurul Üyeleri
            </h2>
            <p class="text-secondary mb-0">Editörler, Danışma ve Yayın Kurulu üyelerini yönetin.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                    <i class="bi bi-gear me-1"></i> Tanımlamalar
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="/admin/lookup/unvan"><i class="bi bi-mortarboard me-2"></i> Unvanlar</a></li>
                    <li><a class="dropdown-item" href="/admin/lookup/kurul"><i class="bi bi-diagram-3 me-2"></i> Kurullar</a></li>
                    <li><a class="dropdown-item" href="/admin/lookup/gorev"><i class="bi bi-briefcase me-2"></i> Görevler</a></li>
                </ul>
            </div>
            <a href="/admin/kurul/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-person-plus me-1"></i> Yeni Üye Ekle
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-4">
            <form action="/admin/kurul" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">KURUL FİLTRESİ</label>
                    <select name="kurul_id" class="form-select bg-light border-0 py-2 rounded-3">
                        <option value="">Tüm Kurullar</option>
                        <?php foreach($boards as $board): ?>
                            <option value="<?= $board->id ?>" <?= $filters['kurul_id'] == $board->id ? 'selected' : '' ?>>
                                <?= e($board->kurul) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">DURUM</label>
                    <select name="is_approved" class="form-select bg-light border-0 py-2 rounded-3">
                        <option value="">Tümü</option>
                        <option value="1" <?= $filters['is_approved'] === '1' ? 'selected' : '' ?>>Yayında</option>
                        <option value="0" <?= $filters['is_approved'] === '0' ? 'selected' : '' ?>>Beklemede</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 py-2 rounded-3">
                        <i class="bi bi-filter me-1"></i> Filtrele
                    </button>
                </div>
                <?php if (!empty($filters['kurul_id']) || $filters['is_approved'] !== null): ?>
                <div class="col-md-2">
                    <a href="/admin/kurul" class="btn btn-outline-secondary w-100 py-2 rounded-3">
                        Sıfırla
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Members Table -->
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Ad Soyad</th>
                            <th>Kurul / Görev</th>
                            <th>E-posta</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-people text-light" style="font-size: 3rem;"></i>
                                <p class="mt-2 text-secondary">Henüz kayıtlı kurul üyesi bulunamadı.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($members as $m): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded-circle bg-soft-primary text-primary me-3 d-flex align-items-center justify-content-center">
                                            <?= strtoupper(substr($m->ad_soyad, 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold d-block"><?= e($m->unvan_text) ?> <?= e($m->ad_soyad) ?></span>
                                            <small class="text-muted"><?= e($m->bolum_ad) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-soft-info text-info mb-1"><?= e($m->kurul_text) ?></span>
                                    <div class="small fw-semibold text-secondary"><?= e($m->gorev_text) ?></div>
                                </td>
                                <td>
                                    <?php if ($m->email): ?>
                                        <a href="mailto:<?= e($m->email) ?>" class="text-decoration-none text-dark small">
                                            <i class="bi bi-envelope me-1"></i> <?= e($m->email) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($m->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Beklemede</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/kurul/edit/<?= $m->id ?>" class="btn btn-sm btn-light shadow-sm" title="Düzenle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger shadow-sm ms-2" onclick="deleteMember(<?= $m->id ?>)" title="Sil">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.bg-soft-primary { background-color: rgba(52, 152, 219, 0.1); }
.bg-soft-info { background-color: rgba(52, 219, 213, 0.1); }
.btn-light-danger { 
    background-color: rgba(231, 76, 60, 0.1); 
    color: #e74c3c;
    border: none;
}
.btn-light-danger:hover {
    background-color: #e74c3c;
    color: white;
}
.avatar-sm {
    width: 40px;
    height: 40px;
}
</style>

<script>
function deleteMember(id) {
    if (confirm('Bu üyeyi kuruldan silmek istediğinize emin misiniz?')) {
        fetch('/admin/kurul/delete/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_token() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Bir hata oluştu.');
            }
        });
    }
}
</script>
