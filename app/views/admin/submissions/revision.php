<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Hakem Süreci', 'url' => '/admin/submissions'],
    ['text' => 'Düzeltme İste', 'url' => '']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-pencil-square text-warning me-2"></i> Makale Düzeltme Talebi
                    </h3>
                    <div class="alert alert-light border rounded-3 mb-4">
                        <div class="fw-bold"><?= e($item->makale_adi) ?></div>
                        <div class="small text-muted">Yazar: <?= e($item->researcher_name) ?> (<?= e($item->researcher_email) ?>)</div>
                    </div>

                    <form action="/admin/submissions/revision/<?= $item->id ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="email" value="<?= e($item->researcher_email) ?>">

                        <div class="mb-4">
                            <label class="form-label fw-bold">Düzeltme Notları <span class="text-danger">*</span></label>
                            <textarea name="metin" class="form-control bg-light border-0 rounded-3" 
                                      rows="10" placeholder="Yazara gönderilecek düzeltme talimatlarını buraya yazın..." required></textarea>
                            <div class="form-text mt-2 text-muted">
                                <i class="bi bi-info-circle me-1"></i> Bu metin hem araştırmacı panelinde görünecek hem de araştırmacının e-posta adresine gönderilecektir.
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning px-5 py-3 rounded-3 fw-bold">
                                <i class="bi bi-send me-1"></i> Talebi ve E-postayı Gönder
                            </button>
                            <a href="/admin/submissions" class="btn btn-outline-secondary px-5 py-3 rounded-3">
                                İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
