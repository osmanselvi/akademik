<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Dergi Sayıları', 'url' => '/admin/dergi-tanim']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-journal-album text-primary me-2"></i> Dergi Sayı Yönetimi
            </h2>
            <p class="text-secondary mb-0">Yayımlanmış ve taslak dergi sayılarını yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/dergi-tanim/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Yeni Sayı Oluştur
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kapak</th>
                            <th>Dergi Tanımı</th>
                            <th>Yayın Tarihi</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz dergi sayısı bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <?php if ($item->dergi_kapak): ?>
                                        <img src="<?= get_image_url($item->dergi_kapak) ?>" alt="Kapak" class="rounded shadow-sm" style="height: 60px; width: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="height: 60px; width: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-bold"><?= e($item->dergi_tanim) ?></div>
                                    <small class="text-muted"><?= e($item->ing_baslik) ?></small>
                                </td>
                                <td><?= e($item->yayin_created_at) ?></td>
                                <td>
                                    <?php if ($item->is_approved): ?>
                                        <span class="badge bg-success">Yayında</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Taslak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/dergi-tanim/edit/<?= $item->id ?>" class="btn btn-sm btn-light border shadow-sm" title="Düzenle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/admin/online-makale?dergi=<?= $item->id ?>" class="btn btn-sm btn-light border shadow-sm ms-2" title="Makaleleri Yönet">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </a>
                                        <button class="btn btn-sm btn-light-danger border shadow-sm ms-2" onclick="deleteItem(<?= $item->id ?>)" title="Sil">
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
    if (confirm('Bu dergi sayısını ve buna bağlı makale kayıtlarını silmek istediğinize emin misiniz? (Dosyalar silinmeyecektir)')) {
        fetch('/admin/dergi-tanim/delete/' + id, {
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
.btn-light-danger { background-color: rgba(231, 76, 60, 0.05); color: #e74c3c; }
.btn-light-danger:hover { background-color: #e74c3c; color: white; }
</style>
