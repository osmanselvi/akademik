<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Hakem Süreci', 'url' => '/admin/submissions']
];
?>

<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-md-12">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-medical text-primary me-2"></i> Hakem Sürecindeki Makaleler
            </h2>
            <p class="text-secondary mb-0">İncelenen veya yayımlanmaya hazır bekleyen makaleleri yönetin.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Makale Başlığı</th>
                            <th>Yazar</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Henüz gönderilen makale bulunmamaktadır.</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($items as $item): 
                                $status = (new \App\Models\GonderilenMakale($this->pdo))->getStatusLabel($item->status);
                            ?>
                            <tr>
                                <td class="ps-4"><?= $item->id ?></td>
                                <td>
                                    <div class="fw-bold text-truncate" style="max-width: 400px;"><?= e($item->makale_adi) ?></div>
                                    <small class="text-muted d-block"><?= e($item->makale_konu) ?></small>
                                    <small class="text-primary"><?= e($item->researcher_email) ?></small>
                                </td>
                                <td><?= e($item->yazar_adi) ?></td>
                                <td>
                                    <span class="badge <?= $status['class'] ?> rounded-pill px-3">
                                        <?= $status['text'] ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="/admin/submissions/revision/<?= $item->id ?>" class="btn btn-sm btn-warning border shadow-sm" title="Düzeltme İste">
                                            <i class="bi bi-pencil-square"></i> Düzeltme İste
                                        </a>
                                        
                                        <?php if ($item->status == 2 || $item->status == 0): ?>
                                            <a href="/admin/submissions/approve/<?= $item->id ?>" class="btn btn-sm btn-outline-success border shadow-sm ms-2" onclick="return confirm('Bu makaleyi yayınlanmaya hazır olarak işaretlemek istiyor musunuz?')">
                                                <i class="bi bi-check-circle"></i> Onayla
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($item->status == 3): ?>
                                            <a href="/admin/online-makale/publish/<?= $item->id ?>" class="btn btn-sm btn-success border shadow-sm ms-2" title="Yayına Al">
                                                <i class="bi bi-journal-arrow-up me-1"></i> Yayınla
                                            </a>
                                        <?php endif; ?>

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
    if (confirm('Bu gönderiyi silmek istediğinize emin misiniz?')) {
        fetch('/admin/submissions/delete/' + id, {
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
