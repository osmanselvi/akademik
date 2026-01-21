<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Destek Taleplerim', 'url' => '']
];
?>

<div class="row mb-4" data-aos="fade-up">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-headset text-primary me-2"></i> Destek Taleplerim
        </h2>
        <p class="text-secondary mb-0">Editör ekibiyle olan iletişimlerinizi takip edin.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="/support/create" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Yeni Talep Oluştur
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Konu</th>
                        <th class="py-3">Durum</th>
                        <th class="py-3">Oluşturma Tarihi</th>
                        <th class="text-end pe-4 py-3">İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Henüz bir destek talebiniz bulunmuyor.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($requests as $req): 
                            $status = (new \App\Models\DestekTalep($this->pdo))->getStatusLabel($req->status);
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold">#<?= $req->id ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= e($req->konu) ?></div>
                                <div class="small text-muted text-truncate" style="max-width: 300px;"><?= e($req->mesaj) ?></div>
                            </td>
                            <td>
                                <span class="badge <?= $status['class'] ?> rounded-pill px-3">
                                    <?= $status['text'] ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= formatDate($req->created_at) ?></td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#req-<?= $req->id ?>">
                                    <i class="bi bi-eye"></i> Detay
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse bg-light" id="req-<?= $req->id ?>">
                            <td colspan="5" class="p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold small text-uppercase text-muted mb-2">Sizin Mesajınız:</h6>
                                        <div class="p-3 bg-white rounded-3 shadow-sm mb-3">
                                            <?= nl2br(e($req->mesaj)) ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold small text-uppercase text-muted mb-2">Editör Yanıtı:</h6>
                                        <div class="p-3 bg-primary bg-opacity-10 rounded-3 shadow-sm border-start border-primary border-4">
                                            <?php if ($req->editor_notu): ?>
                                                <?= nl2br(e($req->editor_notu)) ?>
                                            <?php else: ?>
                                                <em class="text-muted">Bu talep henüz yanıtlanmamış. En kısa sürede geri dönüş yapılacaktır.</em>
                                            <?php endif; ?>
                                        </div>
                                    </div>
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
