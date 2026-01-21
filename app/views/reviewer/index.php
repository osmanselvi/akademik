<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Hakem Paneli', 'url' => '/reviewer']
];
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-list-check text-primary me-2"></i> Hakem Değerlendirme Paneli
            </h2>
            <p class="text-secondary mb-0">Tarafınıza atanan makaleleri buradan görüntüleyip değerlendirebilirsiniz.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Makale Başlığı</th>
                            <th>Atama Tarihi</th>
                            <th>Son Tarih</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox d-block display-4 mb-3 opacity-25"></i>
                                Henüz tarafınıza atanmış bir makale bulunmamaktadır.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach($assignments as $item): ?>
                            <tr>
                                <td class="ps-4">#<?= $item->makale_id ?></td>
                                <td>
                                    <div class="fw-bold"><?= e($item->makale_adi) ?></div>
                                    <a href="<?= get_image_url($item->dosya) ?>" target="_blank" class="small text-decoration-none">
                                        <i class="bi bi-file-earmark-pdf"></i> Dosyayı İndir
                                    </a>
                                </td>
                                <td><?= formatDate($item->created_at) ?></td>
                                <td>
                                    <?php
                                        $deadline = new DateTime($item->deadline);
                                        $daysLeft = (new DateTime())->diff($deadline)->format('%r%a');
                                    ?>
                                    <span class="<?= $daysLeft < 0 ? 'text-danger fw-bold' : ($daysLeft < 3 ? 'text-warning fw-bold' : '') ?>">
                                        <?= formatDate($item->deadline) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item->status == 0): ?>
                                        <span class="badge bg-warning text-dark">Bekleniyor</span>
                                    <?php elseif ($item->status == 3): ?>
                                        <span class="badge bg-success">Tamamlandı</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <?php if ($item->status != 3): ?>
                                        <a href="/reviewer/show/<?= $item->id ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil-square me-1"></i> Değerlendir
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="bi bi-check-circle me-1"></i> Tamamlandı
                                        </button>
                                    <?php endif; ?>
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
