<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Makale Gönderilerim', 'url' => '']
];
?>

<div class="row mb-4" data-aos="fade-down">
    <div class="col-md-8">
        <h2 class="fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i> Makale Gönderilerim</h2>
        <p class="text-muted">Gönderdiğiniz makalelerin süreçlerini buradan takip edebilirsiniz.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <a href="/submissions/create" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Yeni Makale Gönder
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12" data-aos="fade-up">
        <?php if (empty($submissions)): ?>
            <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                <div class="card-body">
                    <div class="display-1 text-light mb-4"><i class="bi bi-journal-x"></i></div>
                    <h4>Henüz bir makale göndermediniz.</h4>
                    <p class="text-muted mb-4">Yeni bir akademik çalışma göndererek süreci başlatabilirsiniz.</p>
                    <a href="/submissions/create" class="btn btn-primary px-5 rounded-pill">İlk Makalemi Gönder</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Makale Bilgileri</th>
                                <th>Durum</th>
                                <th>Tarih</th>
                                <th class="text-end pe-4">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($submissions as $sub): 
                                $status = (new \App\Models\GonderilenMakale($this->pdo))->getStatusLabel($sub->status);
                            ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark"><?= e($sub->makale_adi) ?></div>
                                        <div class="small text-muted"><?= e($sub->yazar_adi) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge <?= $status['class'] ?> rounded-pill px-3">
                                            <?= $status['text'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small"><?= formatDate($sub->created_at) ?></div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="/submissions/<?= $sub->id ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="bi bi-eye"></i> Detay
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
