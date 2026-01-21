<?php
$breadcrumbs = [
    ['text' => 'Panel', 'url' => '/dashboard'],
    ['text' => 'İstatistikler', 'url' => '#']
];
?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i> Makale İstatistikleri
            </h2>
            <p class="text-secondary mb-0">Makale okunma, indirilme ve yayınlanma verilerini analiz edin.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="h1 mb-0 fw-bold"><?= number_format($totalViews) ?></div>
                        <i class="bi bi-eye display-4 opacity-50"></i>
                    </div>
                    <div>Toplam Okunma</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="h1 mb-0 fw-bold"><?= number_format($totalDownloads) ?></div>
                        <i class="bi bi-download display-4 opacity-50"></i>
                    </div>
                    <div>Toplam İndirilme</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-info text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="h1 mb-0 fw-bold"><?= number_format($totalArticles) ?></div>
                        <i class="bi bi-file-text display-4 opacity-50"></i>
                    </div>
                    <div>Toplam Makale</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <!-- Most Viewed -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">En Çok Okunan 5 Makale</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Makale</th>
                                    <th class="text-end pe-4">Okunma</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mostViewed as $m): ?>
                                <tr>
                                    <td class="ps-4 text-truncate" style="max-width: 300px;">
                                        <?= e($m->makale_baslik) ?>
                                    </td>
                                    <td class="text-end pe-4 fw-bold"><?= number_format($m->view_count) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Most Downloaded -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">En Çok İndirilen 5 Makale</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Makale</th>
                                    <th class="text-end pe-4">İndirme</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($mostDownloaded as $m): ?>
                                <tr>
                                    <td class="ps-4 text-truncate" style="max-width: 300px;">
                                        <?= e($m->makale_baslik) ?>
                                    </td>
                                    <td class="text-end pe-4 fw-bold"><?= number_format($m->download_count) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Başvuru Durum Dağılımı</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Dergilere Göre Makale Sayıları</h5>
                </div>
                <div class="card-body">
                     <canvas id="journalChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data Preparation
const statusData = <?= json_encode($statusCounts) ?>;
const journalData = <?= json_encode($journalStats) ?>;

// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: statusData.map(d => {
            switch(parseInt(d.status)) {
                case 0: return 'Bekliyor';
                case 1: return 'İşlemde';
                case 2: return 'Düzeltme';
                case 3: return 'Onaylandı';
                default: return 'Bilinmiyor';
            }
        }),
        datasets: [{
            data: statusData.map(d => d.count),
            backgroundColor: ['#ffc107', '#0dcaf0', '#fd7e14', '#198754']
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Journal Chart
new Chart(document.getElementById('journalChart'), {
    type: 'bar',
    data: {
        labels: journalData.map(d => d.dergi_tanim),
        datasets: [{
            label: 'Makale Sayısı',
            data: journalData.map(d => d.article_count),
            backgroundColor: '#6610f2',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
