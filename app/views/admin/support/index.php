<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Destek Yönetimi', 'url' => '']
];
?>

<div class="row mb-4" data-aos="fade-up">
    <div class="col-md-12">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-headset text-primary me-2"></i> Destek Talepleri Yönetimi
        </h2>
        <p class="text-secondary mb-0">Kullanıcılardan gelen soruları yanıtlayın ve çözüm sunun.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Araştırmacı</th>
                        <th class="py-3">Konu</th>
                        <th class="py-3">Durum</th>
                        <th class="py-3">Tarih</th>
                        <th class="text-end pe-4 py-3">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Henüz destek talebi yok.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($items as $item): 
                            $status = (new \App\Models\DestekTalep($this->pdo))->getStatusLabel($item->status);
                        ?>
                        <tr>
                            <td class="ps-4">#<?= $item->id ?></td>
                            <td>
                                <div class="fw-bold"><?= e($item->researcher_name) ?></div>
                                <div class="small text-muted"><?= e($item->researcher_email) ?></div>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 250px;"><?= e($item->konu) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= $status['class'] ?> rounded-pill px-3">
                                    <?= $status['text'] ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= formatDate($item->created_at) ?></td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="/admin/support/reply/<?= $item->id ?>" class="btn btn-sm btn-primary border shadow-sm" title="Yanıtla">
                                        <i class="bi bi-reply-fill"></i> Yanıtla
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger border shadow-sm ms-1" onclick="deleteRequest(<?= $item->id ?>)" title="Sil">
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

<script>
function deleteRequest(id) {
    if (confirm('Bu destek talebini silmek istediğinize emin misiniz?')) {
        fetch('/admin/support/delete/' + id, {
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
