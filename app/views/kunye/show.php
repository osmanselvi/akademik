<!-- Custom Styles for Masthead Page -->
<style>
    .kunye-hero {
        position: relative;
        padding: 80px 0;
        margin: -30px -15px 50px -15px;
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        color: white;
        border-radius: 0 0 50px 50px;
        overflow: hidden;
    }

    .kunye-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=2000');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
        filter: grayscale(100%) brightness(0.5);
    }

    .kunye-header-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .section-divider {
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin: 20px auto;
        border-radius: 2px;
    }

    .kunye-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-top: -80px;
        position: relative;
        z-index: 2;
        padding: 0 15px;
    }

    .kunye-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #edf2f7;
        transition: all 0.3s ease;
        height: 100%;
    }

    .kunye-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.12);
        border-color: #667eea;
    }

    .kunye-category-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f7fafc;
    }

    .kunye-category-title i {
        width: 35px;
        height: 35px;
        background: #f7fafc;
        color: #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 1.1rem;
    }

    .kunye-entry {
        font-size: 1rem;
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 8px;
        display: flex;
        flex-direction: column;
    }

    .kunye-entry span.name {
        font-weight: 600;
        color: #2d3748;
    }

    .kunye-entry a {
        color: #667eea;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .kunye-entry a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .kunye-grid {
            grid-template-columns: 1fr;
            margin-top: -30px;
        }
        .kunye-hero {
            padding: 60px 0 100px;
        }
    }
</style>

<!-- Hero Section -->
<div class="kunye-hero">
    <div class="container kunye-header-content" data-aos="fade-down">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/" class="text-white-50 text-decoration-none">Ana Sayfa</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Künye</li>
            </ol>
        </nav>
        <h1 class="display-3 fw-bold mb-2">Dergi Künyesi</h1>
        <p class="lead text-white-50 mb-4">Edebiyat Bilimleri Dergisi Yönetim ve Yayın Bilgileri</p>
        <div class="section-divider"></div>
    </div>
</div>

<div class="container pb-5">
    <?php if (empty($kunye)): ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-info-circle display-1 text-muted opacity-25 mb-3"></i>
            <p class="text-muted fs-5">Künye bilgisi henüz tanımlanmamıştır.</p>
        </div>
    <?php else: ?>
        <div class="kunye-grid">
            <?php 
            $icons = [
                'kurucusu' => 'bi-award',
                'imtiyaz sahibi' => 'bi-person-badge',
                'yazı işleri müdürü' => 'bi-pen',
                'editör' => 'bi-person-workspace',
                'alan editörleri' => 'bi-people',
                'yayın sekreteri' => 'bi-clipboard-check',
                'web editörü' => 'bi-laptop',
                'issn' => 'bi-journal-code',
                'e-issn' => 'bi-envelope-paper',
                'adres' => 'bi-geo-alt',
                'telefon' => 'bi-telephone',
                'e-posta adresi' => 'bi-envelope',
                'yayın periyodu' => 'bi-calendar-range',
                'taranan dizinler' => 'bi-search'
            ];
            ?>

            <?php foreach($kunye as $kategori => $entries): ?>
                <?php 
                $lowerCat = mb_strtolower($kategori, 'UTF-8');
                $icon = 'bi-info-circle';
                foreach($icons as $key => $val) {
                    if (strpos($lowerCat, $key) !== false) {
                        $icon = $val;
                        break;
                    }
                }
                ?>
                <div class="kunye-card" data-aos="fade-up">
                    <h5 class="kunye-category-title">
                        <i class="bi <?= $icon ?>"></i>
                        <?= e($kategori) ?>
                    </h5>
                    <div class="kunye-content">
                        <?php foreach($entries as $entry): ?>
                            <div class="kunye-entry">
                                <?php if (in_array(mb_strtolower($kategori), ['web adresi', 'e-posta adresi'])): ?>
                                    <?php if (filter_var($entry->ad_soyad, FILTER_VALIDATE_EMAIL)): ?>
                                        <a href="mailto:<?= e($entry->ad_soyad) ?>">
                                            <i class="bi bi-envelope-at me-1"></i> <?= e($entry->ad_soyad) ?>
                                        </a>
                                    <?php elseif (filter_var($entry->ad_soyad, FILTER_VALIDATE_URL) || strpos($entry->ad_soyad, 'http') === 0): ?>
                                        <a href="<?= e($entry->ad_soyad) ?>" target="_blank">
                                            <i class="bi bi-link-45deg me-1"></i> <?= e($entry->ad_soyad) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="name"><?= e($entry->ad_soyad) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="name"><?= e($entry->ad_soyad) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Bottom Action -->
    <div class="mt-5 text-center">
        <a href="/dergiler" class="btn btn-outline-primary px-5 py-2 rounded-pill">
            <i class="bi bi-journals me-2"></i> Tüm Sayıları İncele
        </a>
    </div>
</div>

