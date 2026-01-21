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

    <form action="/admin/submissions/bulk-action" method="POST" id="bulkForm">
        <?= csrf_field() ?>
        
        <!-- Bulk Actions Toolbar -->
        <div class="card border-0 shadow-sm rounded-4 mb-3 bg-light">
            <div class="card-body py-2 d-flex align-items-center">
                <div class="form-check me-3">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label user-select-none" for="selectAll">Tümünü Seç</label>
                </div>
                <div class="d-flex gap-2">
                    <select name="action" class="form-select form-select-sm" style="width: 200px;" required>
                        <option value="">Toplu İşlem Seç...</option>
                        <option value="delete">Seçilileri Sil</option>
                        <option value="approve">Seçilileri Onayla (Yayına Hazır)</option>
                        <option value="pending">Seçilileri Beklemeye Al</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Seçili işlemleri uygulamak istediğinize emin misiniz?')">Uygula</button>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4" style="width: 40px;">#</th>
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
                                    <td class="ps-4">
                                        <input class="form-check-input item-checkbox" type="checkbox" name="ids[]" value="<?= $item->id ?>">
                                    </td>
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
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            <?php if ($item->status == 2 || $item->status == 0): ?>
                                                <a href="/admin/submissions/assign/<?= $item->id ?>" class="btn btn-sm btn-info border shadow-sm ms-2 text-white" title="Hakem Ata">
                                                    <i class="bi bi-person-check-fill"></i>
                                                </a>
                                                <a href="/admin/submissions/approve/<?= $item->id ?>" class="btn btn-sm btn-outline-success border shadow-sm ms-2" onclick="return confirm('Bu makaleyi yayınlanmaya hazır olarak işaretlemek istiyor musunuz?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($item->status == 3): ?>
                                                <a href="/admin/online-makale/publish/<?= $item->id ?>" class="btn btn-sm btn-success border shadow-sm ms-2" title="Yayına Al">
                                                    <i class="bi bi-journal-arrow-up me-1"></i>
                                                </a>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-sm btn-light-danger border shadow-sm ms-2" onclick="deleteItem(<?= $item->id ?>)" title="Sil">
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
    </form>
</div>

<script>
// Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

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
