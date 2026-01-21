<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dergi Künyesi', 'url' => '/admin/kunye']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-text text-primary me-2"></i> Dergi Künyesi
            </h2>
            <p class="text-secondary mb-0">Dergi künye bilgilerini yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/lookup/kunye-baslik" class="btn btn-outline-secondary rounded-pill px-3 me-2">
                <i class="bi bi-gear me-1"></i> Kategoriler
            </a>
            <a href="/admin/kunye/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Yeni Kayıt
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <form action="/admin/kunye" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">KATEGORİ</label>
                    <select name="baslik_id" class="form-select bg-light border-0 py-2 rounded-3">
                        <option value="">Tüm Kategoriler</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>" <?= $filters['baslik_id'] == $cat->id ? 'selected' : '' ?>>
                                <?= e($cat->baslik) ?>
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
                <?php if (!empty($filters['baslik_id']) || $filters['is_approved'] !== null): ?>
                <div class="col-md-2">
                    <a href="/admin/kunye" class="btn btn-outline-secondary w-100 py-2 rounded-3">Sıfırla</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Entries Table -->
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kategori</th>
                            <th>İçerik</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($entries)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Henüz künye kaydı bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($entries as $entry): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-soft-info text-dark"><?= e($entry->kategori) ?></span>
                                </td>
                                <td class="fw-semibold"><?= e($entry->ad_soyad) ?></td>
                                <td>
                                    <?php if ($entry->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Beklemede</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/kunye/edit/<?= $entry->id ?>" class="btn btn-sm btn-light shadow-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger shadow-sm ms-2" onclick="deleteEntry(<?= $entry->id ?>)">
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
</style>

<script>
function deleteEntry(id) {
    if (confirm('Bu künye kaydını silmek istediğinize emin misiniz?')) {
        fetch('/admin/kunye/delete/' + id, {
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
