<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'E-posta Şablonları', 'url' => '/admin/email-templates'],
    ['text' => 'Düzenle', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Şablon Düzenle: <?= e($template->name) ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php if (!empty($template->variables)): ?>
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle me-2"></i> Kullanılabilir Değişkenler</h6>
                        <p class="mb-0 small">Bu şablonda aşağıdaki kodları kullanabilirsiniz. E-posta gönderilirken bu kodlar gerçek verilerle değiştirilecektir.</p>
                        <hr>
                        <div class="d-flex flex-wrap gap-2">
                            <?php 
                            $vars = json_decode($template->variables, true);
                            foreach ($vars as $key => $desc): 
                            ?>
                                <span class="badge bg-white text-info border" title="<?= $desc ?>">
                                    {{<?= $key ?>}}
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form action="/admin/email-templates/update/<?= $template->id ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">E-posta Konusu</label>
                            <input type="text" name="subject" class="form-control" value="<?= e($template->subject) ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">İçerik</label>
                            <textarea name="body" id="editor" class="form-control" rows="15"><?= $template->body ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/admin/email-templates" class="btn btn-outline-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-lg me-2"></i> Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple WYSIWYG Editor using Trumbowyg (Lightweight) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
<script>
    $('#editor').trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['link'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        autogrow: true
    });
</script>
