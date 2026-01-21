<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Yayımlanmış Makaleler', 'url' => '/admin/online-makale']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-8">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-text text-primary me-2"></i> Yayımlanmış Makale Yönetimi
            </h2>
            <p class="text-secondary mb-0">Dergi sayılarında yayımlanmış makaleleri yönetin.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="/admin/online-makale/create<?= $selectedDergi ? '?dergi='.$selectedDergi : '' ?>" class="btn btn-primary rounded-pill px-4 shadow-sm me-2">
                <i class="bi bi-plus-circle me-1"></i> Yeni Makale
            </a>
            <div class="dropdown d-inline-block">
                <button class="btn btn-light border dropdown-toggle rounded-pill px-4 shadow-sm" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-filter me-1"></i> <?= $selectedDergi ? 'Filtreli' : 'Tüm Sayılar' ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                    <li><a class="dropdown-item" href="/admin/online-makale">Tüm Sayılar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <?php foreach($dergiler as $dergi): ?>
                        <li><a class="dropdown-item <?= $selectedDergi == $dergi->id ? 'active' : '' ?>" href="/admin/online-makale?dergi=<?= $dergi->id ?>"><?= e($dergi->dergi_tanim) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tür</th>
                            <th>Makale Başlığı / Yazar</th>
                            <th>Dergi Sayısı</th>
                            <th>Dosya</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz yayımlanmış makale bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-light text-secondary border"><?= e($item->tur_adi) ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-truncate" style="max-width: 400px;"><?= e($item->makale_baslik) ?></div>
                                    <small class="text-muted"><?= e($item->makale_yazar) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info"><?= e($item->dergi_adi) ?></span>
                                </td>
                                <td>
                                    <?php if ($item->dosya): ?>
                                        <a href="<?= get_image_url($item->dosya) ?>" target="_blank" class="btn btn-sm btn-outline-danger border-0">
                                            <i class="bi bi-file-earmark-pdf fs-5"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/online-makale/edit/<?= $item->id ?>" class="btn btn-sm btn-light border shadow-sm" title="Düzenle">
                                            <i class="bi bi-pencil"></i>
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
    if (confirm('Bu makale kaydını silmek istediğinize emin misiniz? (Dosya silinmeyecektir)')) {
        fetch('/admin/online-makale/delete/' + id, {
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
