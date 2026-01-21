<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Makale Gönderilerim', 'url' => '/submissions'],
    ['text' => 'Gönderi Detayı', 'url' => '']
];
$statusInfo = (new \App\Models\GonderilenMakale($this->pdo))->getStatusLabel($item->status);
?>

<div class="row">
    <div class="col-lg-8" data-aos="fade-right">
        <!-- Makale Bilgileri -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Makale Bilgileri</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-2"><?= e($item->makale_adi) ?></h3>
                    <span class="badge <?= $statusInfo['class'] ?> rounded-pill px-3 py-2">
                        <?= $statusInfo['text'] ?>
                    </span>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Yazar(lar)</label>
                        <div class="fw-bold"><?= e($item->yazar_adi) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Gönderim Tarihi</label>
                        <div class="fw-bold"><?= formatDate($item->created_at) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Anahtar Kelimeler</label>
                        <div><?= e($item->anahtar_kelimeler) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small d-block mb-1">Dosya</label>
                        <a href="/uploads/<?= $item->dosya ?>" target="_blank" class="text-decoration-none">
                            <i class="bi bi-file-earmark-text"></i> Mevcut Dosyayı Görüntüle
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revizyon Geçmişi / Mesajlar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Süreç Notları & Düzeltmeler</h5>
            </div>
            <div class="card-body p-4">
                <?php if (empty($revisions)): ?>
                    <p class="text-muted text-center py-4">Henüz bir süreç notu bulunmamaktadır.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($revisions as $rev): ?>
                            <div class="timeline-item mb-4 pb-4 border-bottom last-child-no-border">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold text-primary"><?= e($rev->sender_name) ?></span>
                                    <small class="text-muted"><?= formatDate($rev->created_at, true) ?></small>
                                </div>
                                <div class="p-3 bg-light rounded-3 text-secondary">
                                    <?= nl2br(e($rev->metin)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($supportRequests)): ?>
                    <hr class="my-4">
                    <h6 class="fw-bold text-primary mb-3"><i class="bi bi-headset me-2"></i> İlgili Destek Talepleri</h6>
                    <?php foreach ($supportRequests as $sreq): 
                        $sstatus = (new \App\Models\DestekTalep($this->pdo))->getStatusLabel($sreq->status);
                    ?>
                        <div class="p-3 bg-white border rounded-3 mb-3 shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-bold small"><?= e($sreq->konu) ?></div>
                                <span class="badge <?= $sstatus['class'] ?> small"><?= $sstatus['text'] ?></span>
                            </div>
                            <div class="small text-secondary mb-2"><?= nl2br(e($sreq->mesaj)) ?></div>
                            <?php if ($sreq->editor_notu): ?>
                                <div class="mt-2 p-2 bg-light border-start border-primary border-3 small italic">
                                    <strong>Editör Yanıtı:</strong><br>
                                    <?= nl2br(e($sreq->editor_notu)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-2 text-end">
                                <small class="text-muted" style="font-size: 0.7rem;"><?= formatDate($sreq->created_at) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- İşlem Paneli -->
    <div class="col-lg-4" data-aos="fade-left">
        
        <!-- Signature Status Check -->
        <?php if ($signatureRecord): ?>
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10 mb-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <i class="bi bi-patch-check-fill text-success fs-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold text-success mb-1">Sözleşme İmzalı</h6>
                        <small class="text-secondary">E-İmza ile onaylandı.<br><?= formatDate($signatureRecord->created_at, true) ?></small>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-opacity-10 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-danger mb-3">
                        <i class="bi bi-pen-fill me-2"></i> İmza Bekleniyor
                    </h5>
                    <p class="small text-dark mb-4">
                        Bu makale için Telif Devir Sözleşmesi henüz imzalanmamıştır. Lütfen e-imzanız ile onaylayın.
                    </p>
                    <button type="button" class="btn btn-danger w-100 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#signModal">
                        <i class="bi bi-vector-pen me-2"></i> Sözleşmeyi İmzala
                    </button>
                </div>
            </div>

            <!-- Signing Modal -->
            <div class="modal fade" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header border-0 bg-warning bg-opacity-10">
                            <h1 class="modal-title fs-5 fw-bold text-warning-emphasis" id="signModalLabel">
                                <i class="bi bi-file-text-fill me-2"></i> Telif Devir Sözleşmesi
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body p-4">
                            <?php if (!empty($sozlesmeMaddeleri)): ?>
                                <?php foreach($sozlesmeMaddeleri as $baslik => $items): ?>
                                    <h6 class="fw-bold text-primary mb-2"><?= e($baslik) ?></h6>
                                    <ul class="list-unstyled small text-muted mb-4">
                                        <?php foreach($items as $m): ?>
                                            <li class="mb-2 d-flex">
                                                <i class="bi bi-caret-right-fill text-primary me-2 flex-shrink-0 mt-1"></i>
                                                <span><?= nl2br(e($m->metin)) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center text-muted">Sözleşme maddeleri görüntülenemedi.</p>
                            <?php endif; ?>
                            
                            <hr class="my-4">

                            <div class="form-check p-3 bg-light border rounded-3 d-flex align-items-center">
                                <input class="form-check-input fs-4 me-3 mt-0" type="checkbox" id="modalAgreeCheck">
                                <div>
                                    <label class="form-check-label fw-bold text-dark" for="modalAgreeCheck">
                                        Yukarıdaki sözleşme metnini okudum, anladım ve onaylıyorum.
                                    </label>
                                    <div class="text-secondary small mt-1">
                                        Bu işlem kayıtlı e-imzanız ile gerçekleştirilecektir.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 pb-4 pe-4">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Vazgeç</button>
                            <form action="/submissions/sign/<?= $item->id ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" id="modalSignBtn" class="btn btn-danger rounded-pill px-4" disabled>
                                    <i class="bi bi-vector-pen me-2"></i> E-İmzayla Onayla
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const check = document.getElementById('modalAgreeCheck');
                    const btn = document.getElementById('modalSignBtn');
                    if(check && btn) {
                        check.addEventListener('change', function() {
                            btn.disabled = !this.checked;
                        });
                    }
                });
            </script>
        <?php endif; ?>

        <?php if ($item->status == 1): // Revision Requested ?>
            <div class="card border-0 shadow-sm rounded-4 bg-warning bg-opacity-10 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-warning mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Düzeltme Gerekli
                    </h5>
                    <p class="small text-dark mb-4">
                        Editör tarafından düzeltme talep edilmiştir. Lütfen notları inceleyip güncel dosyayı yükleyin.
                    </p>
                    
                    <form action="/submissions/resubmit/<?= $item->id ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Düzeltilmiş Dosya (.docx)</label>
                            <input type="file" name="dosya" class="form-control form-control-sm" accept=".doc,.docx,.pdf" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold">
                            Yeni Versiyonu Gönder
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-muted mb-3 text-uppercase small">Destek</h6>
                <p class="small text-muted mb-4">Bir sorun yaşıyorsanız lütfen aşağıdaki formu kullanarak editör ekibine iletin.</p>
                
                <form action="/support/store" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="submission_id" value="<?= $item->id ?>">
                    <input type="hidden" name="konu" value="Makale #<?= $item->id ?> Hakkında Destek">
                    <div class="mb-3">
                        <textarea name="mesaj" class="form-control form-control-sm bg-light" rows="4" placeholder="Mesajınızı buraya yazın..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">Talebi Gönder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.last-child-no-border:last-child { border-bottom: 0 !important; margin-bottom: 0 !important; padding-bottom: 0 !important; }
</style>
