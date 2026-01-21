<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'Sistem Logları', 'url' => '#']
];
?>

<div class="row py-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom p-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-folder me-2"></i> Log Dosyaları</h6>
            </div>
            <div class="list-group list-group-flush rounded-bottom-4">
                <?php if (empty($files)): ?>
                    <div class="list-group-item text-muted small p-3">Henüz log dosyası yok.</div>
                <?php else: ?>
                    <?php foreach ($files as $file): ?>
                        <a href="?file=<?= $file['name'] ?>" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($selectedFile == $file['name']) ? 'active' : '' ?>">
                            <div>
                                <i class="bi bi-file-text me-2"></i> <?= $file['name'] ?>
                            </div>
                            <span class="badge bg-secondary rounded-pill"><?= $file['size'] ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-terminal me-2"></i> 
                    <?= $selectedFile ? $selectedFile : 'Dosya Seçilmedi' ?>
                </h6>
                <?php if ($selectedFile): ?>
                    <span class="badge bg-info text-dark">
                        <i class="bi bi-clock me-1"></i> Son Güncelleme: 
                        <?= date("d.m.Y H:i:s", filemtime(storagePath('logs/' . $selectedFile))) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="card-body p-0">
                <?php if ($content): ?>
                    <pre class="m-0 p-3 bg-dark text-light" style="max-height: 600px; overflow-y: auto; font-family: 'Consolas', monospace; font-size: 0.85rem; border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;"><?= htmlspecialchars($content) ?></pre>
                <?php else: ?>
                    <div class="p-5 text-center text-muted">
                        <i class="bi bi-file-earmark-x display-4 mb-3 d-block"></i>
                        Görüntülenecek içerik yok.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
