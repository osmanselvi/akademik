<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dizinler', 'url' => '/admin/dizin']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-database text-primary me-2"></i> Dizin Yönetimi
            </h2>
            <p class="text-secondary mb-0">Derginin tarandığı indeksleri yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/dizin/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Yeni Dizin Ekle
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <form action="/admin/dizin" method="GET" class="row g-3 align-items-end">
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
                    <a href="/admin/dizin" class="btn btn-outline-secondary w-100 py-2 rounded-3">Sıfırla</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Dizinler Table -->
    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Logo</th>
                            <th>Dizin Adı</th>
                            <th>Link</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dizinler)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz dizin bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($dizinler as $dizin): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if (isset($dizin->dizin_logo) && $dizin->dizin_logo): ?>
                                        <img src="/images/<?= e($dizin->dizin_logo) ?>" alt="Logo" class="img-thumbnail" style="max-height: 50px;">
                                    <?php else: ?>
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= e($dizin->dizin_adi) ?></td>
                                <td>
                                    <?php if (isset($dizin->dizin_link) && $dizin->dizin_link): ?>
                                        <a href="<?= e($dizin->dizin_link) ?>" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-link-45deg"></i> Link
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($dizin->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Beklemede</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/dizin/edit/<?= $dizin->id ?>" class="btn btn-sm btn-light shadow-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger shadow-sm ms-2" onclick="deleteDizin(<?= $dizin->id ?>)">
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
function deleteDizin(id) {
    if (confirm('Bu dizini silmek istediğinize emin misiniz?')) {
        fetch('/admin/dizin/delete/' + id, {
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
