<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Makale Esasları', 'url' => '/admin/makale-esas']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-bookmark-check text-primary me-2"></i> Makale Esasları Yönetimi
            </h2>
            <p class="text-secondary mb-0">Makale yazım kurallarını ve esaslarını yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/makale-esas/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Yeni Esas Ekle
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <form action="/admin/makale-esas" method="GET" class="row g-3 align-items-end">
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
                <?php if ($filters['is_approved'] !== null): ?>
                <div class="col-md-2">
                    <a href="/admin/makale-esas" class="btn btn-outline-secondary w-100 py-2 rounded-3">Sıfırla</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sıra</th>
                            <th>Esas Türü</th>
                            <th>Açıklama</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($esasList)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz makale esası bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($esasList as $esas): ?>
                            <tr>
                                <td class="ps-4"><?= $esas->id ?></td>
                                <td class="fw-semibold"><?= e($esas->esas_turu) ?></td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;">
                                        <?= e(substr(strip_tags($esas->aciklama), 0, 100)) ?>...
                                    </span>
                                </td>
                                <td>
                                    <?php if ($esas->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Beklemede</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/makale-esas/edit/<?= $esas->id ?>" class="btn btn-sm btn-light shadow-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger shadow-sm ms-2" onclick="deleteEsas(<?= $esas->id ?>)">
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
function deleteEsas(id) {
    if (confirm('Bu makale esasını silmek istediğinize emin misiniz?')) {
        fetch('/admin/makale-esas/delete/' + id, {
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
