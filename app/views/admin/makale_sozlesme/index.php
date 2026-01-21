<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Telif Devir Sözleşmesi', 'url' => '/admin/makale-sozlesme']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-check text-primary me-2"></i> Telif Devir Sözleşmesi Yönetimi
            </h2>
            <p class="text-secondary mb-0">Sözleşme maddelerini başlık bazlı yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/makale-sozlesme/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Yeni Madde Ekle
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Başlık</th>
                            <th>Metin</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Henüz madde bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-primary border"><?= e($item->baslik) ?></span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 400px;">
                                        <?= e(strip_tags($item->metin)) ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($item->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Beklemede</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/makale-sozlesme/edit/<?= $item->id ?>" class="btn btn-sm btn-light border shadow-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger border shadow-sm ms-2" onclick="deleteItem(<?= $item->id ?>)">
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

<script>
function deleteItem(id) {
    if (confirm('Bu maddeyi silmek istediğinize emin misiniz?')) {
        fetch('/admin/makale-sozlesme/delete/' + id, {
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

<style>
.btn-light-danger { 
    background-color: rgba(231, 76, 60, 0.05); 
    color: #e74c3c;
}
.btn-light-danger:hover {
    background-color: #e74c3c;
    color: white;
}
</style>
