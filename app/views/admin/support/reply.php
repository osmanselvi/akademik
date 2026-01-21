<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Destek Yönetimi', 'url' => '/admin/support'],
    ['text' => 'Talebi Yanıtla', 'url' => '']
];
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-4">
                    <i class="bi bi-reply-fill text-primary me-2"></i> Destek Talebini Yanıtla
                </h3>

                <div class="bg-light p-4 rounded-4 mb-4 border-start border-primary border-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-0"><?= e($item->researcher_name) ?></h6>
                            <small class="text-muted"><?= e($item->researcher_email) ?></small>
                        </div>
                        <span class="badge bg-white text-dark shadow-sm rounded-pill px-3"><?= formatDate($item->created_at) ?></span>
                    </div>
                    <div class="mb-2 fw-bold text-primary"><?= e($item->konu) ?></div>
                    <div class="text-secondary"><?= nl2br(e($item->mesaj)) ?></div>
                </div>

                <form action="/admin/support/reply/<?= $item->id ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="user_email" value="<?= e($item->researcher_email) ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Sizin Yanıtınız <span class="text-danger">*</span></label>
                        <textarea name="editor_notu" class="form-control bg-light border-0 py-3 rounded-3" 
                                  rows="8" placeholder="Kullanıcıya gönderilecek çözüm veya yanıt..." required><?= $item->editor_notu ?></textarea>
                        <div class="form-text mt-2 text-muted">
                            <i class="bi bi-info-circle me-1"></i> Bu yanıt kaydedilecek ve kullanıcıya anında e-posta ile gönderilecektir.
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold">
                            Yanıtı Kaydet ve Gönder
                        </button>
                        <a href="/admin/support" class="btn btn-outline-secondary px-5 py-3 rounded-pill">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
