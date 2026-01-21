<!-- Hero Section - Enhanced -->
<div class="hero-section mb-5">
    <div class="container h-100 d-flex align-items-center">
        <div class="hero-content text-start">
            <div class="hero-logo mb-4">
                <img src="/images/ebilimlogo1.png" alt="Edebiyat Bilimleri Logo" class="img-fluid">
            </div>
            <h1 class="hero-title mb-3" data-aos="fade-up">
                Edebiyat Bilimleri Dergisi
            </h1>
            <p class="hero-subtitle mb-4" data-aos="fade-up" data-aos-delay="100">
                Journal of Literary Sciences
            </p>
            <p class="hero-badge" data-aos="fade-up" data-aos-delay="200">
                <span class="badge bg-light text-dark px-4 py-2">
                    <i class="bi bi-award-fill"></i> Hakemli Akademik Dergi
                </span>
            </p>
        </div>
    </div>
</div>

<style>
.hero-section {
    background-image: url('/banner/banner.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 20px;
    padding: 100px 0;
    text-align: left;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    min-height: 400px;
    display: flex;
    align-items: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

.hero-content {
    position: relative;
    z-index: 2;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.hero-logo {
    animation: fadeInDown 1s ease-out;
}

.hero-logo img {
    max-height: 120px;
    width: auto;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}

.hero-icon {
    font-size: 4rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.hero-title {
    font-size: 3rem;
    font-weight: 700;
    text-shadow: 0 2px 20px rgba(0,0,0,0.2);
}

.hero-subtitle {
    font-size: 1.5rem;
    opacity: 0.95;
    font-weight: 300;
}

.hero-badge .badge {
    font-size: 1rem;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

@media (max-width: 991px) {
    .hero-title { font-size: 2.2rem; }
    .hero-subtitle { font-size: 1.2rem; }
}

@media (max-width: 768px) {
    .hero-section { padding: 80px 15px; }
    .hero-title { font-size: 2.2rem; }
    .hero-subtitle { font-size: 1.2rem; }
    .hero-logo img { max-height: 80px; }
}

@media (max-width: 576px) {
    .hero-section { padding: 60px 15px; }
    .hero-title { font-size: 1.8rem; }
    .hero-subtitle { font-size: 1.1rem; }
    .hero-logo img { max-height: 60px; }
}
</style>

<!-- Güncel Sayı -->
<?php if ($guncelDergi): ?>
<div class="row mb-5" data-aos="fade-up">
    <div class="col-12">
        <div class="section-header mb-4">
            <h2 class="section-title">
                <i class="bi bi-star-fill text-warning"></i> Güncel Sayı
            </h2>
            <div class="section-line"></div>
        </div>
        
        <div class="feature-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <div class="feature-image">
                        <?php if (!empty($guncelDergi->dergi_kapak)): ?>
                            <img src="<?= get_image_url($guncelDergi->dergi_kapak) ?>" 
                                 class="img-fluid" 
                                 alt="Dergi Kapağı">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="bi bi-journal-text"></i>
                            </div>
                        <?php endif; ?>
                        <div class="current-badge">
                            <i class="bi bi-lightning-fill"></i> GÜNCEL
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="feature-content">
                        <h3 class="feature-title"><?= e($guncelDergi->dergi_tanim) ?></h3>
                        <?php if (!empty($guncelDergi->ing_baslik)): ?>
                            <h5 class="feature-subtitle"><?= e($guncelDergi->ing_baslik) ?></h5>
                        <?php endif; ?>
                        
                        <div class="feature-meta">
                            <span class="meta-item">
                                <i class="bi bi-calendar-event"></i> 
                                <?= formatDate($guncelDergi->yayin_created_at) ?>
                            </span>
                        </div>
                        
                        <div class="feature-actions">
                            <a href="/dergi/<?= $guncelDergi->id ?>" class="btn btn-gradient btn-lg">
                                <i class="bi bi-eye"></i> Sayıyı İncele
                            </a>
                            <?php if (!empty($guncelDergi->jenerikdosyasi)): ?>
                                <a href="<?= get_image_url($guncelDergi->jenerikdosyasi) ?>" 
                                   class="btn btn-outline-danger btn-lg">
                                    <i class="bi bi-file-pdf"></i> Jenerik İndir
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.section-header {
    text-align: center;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.section-line {
    width: 80px;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    margin: 0 auto;
    border-radius: 2px;
}

.feature-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}

.feature-image {
    position: relative;
    height: 100%;
    min-height: 400px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.feature-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    font-size: 6rem;
    color: white;
    opacity: 0.3;
}

.current-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #fbbf24;
    color: #000;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(251, 191, 36, 0.4);
    animation: pulse-badge 2s infinite;
}

@keyframes pulse-badge {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.feature-content {
    padding: 40px;
}

.feature-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 15px;
}

.feature-subtitle {
    color: #718096;
    font-size: 1.2rem;
    margin-bottom: 20px;
}

.feature-meta {
    margin: 25px 0;
}

.meta-item {
    display: inline-block;
    padding: 10px 20px;
    background: #f7fafc;
    border-radius: 50px;
    color: #4a5568;
    font-weight: 500;
}

.feature-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

@media (max-width: 991px) {
    .feature-image { min-height: 300px; }
    .feature-content { padding: 30px; }
}

@media (max-width: 768px) {
    .feature-image { min-height: 250px; }
    .feature-title { font-size: 1.5rem; }
    .feature-subtitle { font-size: 1.1rem; }
    .feature-actions .btn { width: 100%; }
}

.btn-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 600;
    padding: 12px 30px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline-danger {
    border: 2px solid #dc2626;
    color: #dc2626;
    font-weight: 600;
    padding: 10px 30px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-outline-danger:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
}
</style>
<?php endif; ?>

<!-- Son Sayılar -->
<?php if (!empty($recentDergiler)): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="section-header mb-4" data-aos="fade-up">
            <h2 class="section-title">
                <i class="bi bi-clock-history"></i> Son Sayılar
            </h2>
            <div class="section-line"></div>
        </div>
    </div>
    
    <?php foreach ($recentDergiler as $index => $dergi): ?>
        <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
            <div class="modern-card">
                <div class="card-image">
                    <?php if (!empty($dergi->dergi_kapak)): ?>
                        <img src="<?= get_image_url($dergi->dergi_kapak) ?>" alt="Dergi Kapağı">
                    <?php else: ?>
                        <div class="card-placeholder">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-overlay">
                        <a href="/dergi/<?= $dergi->id ?>" class="overlay-btn">
                            <i class="bi bi-eye"></i> Görüntüle
                        </a>
                    </div>
                </div>
                <div class="card-content">
                    <h5 class="card-title"><?= htmlspecialchars($dergi->dergi_tanim) ?></h5>
                    <?php if (!empty($dergi->yayin_created_at)): ?>
                        <p class="card-date">
                            <i class="bi bi-calendar3"></i> 
                            <?= formatDate($dergi->yayin_created_at) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Pagination -->
    <div class="col-12 mt-4 d-flex justify-content-center" data-aos="fade-up">
        <?= $paginator->getLinks() ?>
    </div>
</div>



<div class="row">
    <div class="col-12 text-center" data-aos="fade-up">
        <a href="/dergiler" class="btn btn-gradient btn-lg px-5">
            <i class="bi bi-journals"></i> Tüm Sayıları Görüntüle
            <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Henüz yayınlanmış dergi sayısı bulunmamaktadır.
</div>
<?php endif; ?>

<!-- Features Section -->
<div class="row mt-5 g-4">
    <div class="col-md-4" data-aos="flip-left">
        <a href="/submissions/create" class="text-decoration-none h-100 d-block">
            <div class="feature-box">
                <div class="feature-icon bg-gradient-1">
                    <i class="bi bi-cloud-arrow-up"></i>
                </div>
                <h5>Makale Gönder</h5>
                <p>Makalenizi sisteme yükleyin ve hakem sürecini şeffaf bir şekilde takip edin.</p>
                <div class="feature-link mt-3 text-primary fw-bold small">
                    Hemen Başla <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4" data-aos="flip-left" data-aos-delay="100">
        <a href="/dergiler" class="text-decoration-none h-100 d-block">
            <div class="feature-box">
                <div class="feature-icon bg-gradient-2">
                    <i class="bi bi-archive"></i>
                </div>
                <h5>Arşiv</h5>
                <p>Geçmiş sayılarımıza, yayınlanmış tüm cilt ve makalelere kolayca erişin.</p>
                <div class="feature-link mt-3 text-danger fw-bold small">
                    Sayıları İncele <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4" data-aos="flip-left" data-aos-delay="200">
        <a href="/kurul/2" class="text-decoration-none h-100 d-block">
            <div class="feature-box">
                <div class="feature-icon bg-gradient-3">
                    <i class="bi bi-people"></i>
                </div>
                <h5>Yayın Kurulu</h5>
                <p>Dergi yönetim kadrosu ve akademik danışma kurulumuz hakkında bilgi edinin.</p>
                <div class="feature-link mt-3 text-info fw-bold small">
                    Kurulu Görüntüle <i class="bi bi-arrow-right"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
.feature-box {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    height: 100%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid #edf2f7;
    position: relative;
    overflow: hidden;
}

.feature-box::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border-color: rgba(102, 126, 234, 0.2);
}

.feature-box:hover h5 {
    color: #667eea;
}

.feature-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 1.8rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    transition: transform 0.5s ease;
}

.feature-box:hover .feature-icon {
    transform: rotateY(360deg);
}

.featur.hero-logo {
    animation: fadeInDown 1s ease-out;
}

.hero-logo img {
    max-height: 120px;
    width: auto;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
}

.bg-gradient-1 {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-2 {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.bg-gradient-3 {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.feature-box h5 {
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 15px;
}

.feature-box p {
    color: #718096;
    margin: 0;
}
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({
    duration: 800,
    once: true,
    offset: 100
});
</script>
