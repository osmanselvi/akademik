<div class="container py-5">
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <h1 class="fw-bold display-5 mb-3">
                <i class="bi bi-bookmark-check-fill text-primary me-2"></i> Makale Esasları
            </h1>
            <div class="bg-primary rounded-pill mb-4" style="width: 80px; height: 5px;"></div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3" data-aos="fade-right">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">İçindekiler</h5>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($esasList)): ?>
                            <?php foreach($esasList as $esas): ?>
                                <a href="/makale-esaslari?esas_id=<?= $esas->id ?>" 
                                   class="list-group-item list-group-item-action border-0 rounded-3 mb-2 <?= $esas->id == $selectedId ? 'active' : '' ?>">
                                    <i class="bi bi-chevron-right me-2"></i>
                                    <?= e($esas->esas_turu) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="col-lg-9" data-aos="fade-left">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <?php if ($selectedEsas): ?>
                        <h3 class="fw-bold mb-4"><?= e($selectedEsas->esas_turu) ?></h3>
                        
                        <?php if ($selectedEsas->id == 11): ?>
                            <!-- Special case: Include yzkural.php -->
                            <div class="content">
                                <?php include __DIR__ . '/../dergi/yzkural.php'; ?>
                            </div>
                        <?php elseif ($selectedEsas->id == 12): ?>
                            <!-- Special case: Format with list items -->
                            <ul class="lh-lg">
                                <?php
                                $items = explode("\n", $selectedEsas->aciklama);
                                foreach($items as $item) {
                                    if (trim($item)) {
                                        echo '<li>' . htmlspecialchars_decode($item) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                        <?php else: ?>
                            <!-- Regular content -->
                            <div class="content lh-lg">
                                <?php 
                                $content = nl2br(htmlspecialchars_decode($selectedEsas->aciklama));
                                echo $content;
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Lütfen sol taraftan bir konu seçiniz.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}
.content table {
    width: 100%;
    margin: 20px 0;
}
.content table th,
.content table td {
    padding: 12px;
    border: 1px solid #dee2e6;
}
.content table thead {
    background-color: #f8f9fa;
}
</style>
