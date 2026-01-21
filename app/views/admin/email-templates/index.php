<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'E-posta Şablonları', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-envelope-paper text-primary me-2"></i> E-posta Şablonları
            </h2>
            <p class="text-secondary mb-0">Sistem tarafından otomatik gönderilen e-postaların içeriklerini buradan düzenleyebilirsiniz.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Şablon Adı</th>
                            <th>Konu Başlığı</th>
                            <th>Son Güncelleme</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($templates as $item): ?>
                        <tr>
                            <td class="ps-4 fw-bold">
                                <?= e($item->name) ?>
                                <div class="small text-muted fw-normal"><?= e($item->code) ?></div>
                            </td>
                            <td><?= e($item->subject) ?></td>
                            <td><?= formatDate($item->updated_at) ?></td>
                            <td class="text-end pe-4">
                                <a href="/admin/email-templates/edit/<?= $item->id ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil-square me-1"></i> Düzenle
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
