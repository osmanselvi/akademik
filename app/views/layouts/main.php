<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= setting('SiteTitle', 'Edebiyat Bilimleri Dergisi') ?></title>
    <meta name="description" content="<?= setting('SiteDescription', 'Akademik Dergi Platformu') ?>">
    <meta name="keywords" content="<?= setting('SiteKeywords', 'dergi, makale, akademik') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background: rgba(248, 249, 250, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 12px 0;
            position: relative;
            z-index: 1050;
        }
        .navbar-brand img {
            height: 45px !important;
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        .nav-link {
            color: #2d3748 !important;
            font-weight: 500;
            transition: color 0.3s ease;
            margin: 0 5px;
        }
        .nav-link:hover {
            color: #667eea !important;
        }
        .nav-link i {
            color: #667eea;
            margin-right: 5px;
        }
        .navbar-toggler {
            border: none;
            color: #2d3748;
        }
        .navbar-toggler-icon {
            filter: brightness(0);
        }
        .navbar .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white !important;
            padding: 8px 25px !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .navbar .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white !important;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .main-content {
            min-height: calc(100vh - 200px);
            padding: 30px 0;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        /* Modern Card System */
        .modern-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .modern-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .card-image {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            flex-shrink: 0;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .modern-card:hover .card-image img {
            transform: scale(1.1);
        }

        .card-placeholder {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: rgba(255,255,255,0.3);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modern-card:hover .card-overlay {
            opacity: 1;
        }

        .overlay-btn {
            background: white;
            color: #667eea;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .overlay-btn:hover {
            background: #f8f9fa;
            color: #764ba2;
        }

        .modern-card:hover .overlay-btn {
            transform: translateY(0);
        }

        .card-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 10px;
        }

        /* Turkish-aware Uppercase */
        .text-uppercase-custom {
            text-transform: uppercase;
        }
        
        /* Article Hover Effect */
        .hover-primary:hover {
            color: #667eea !important;
        }

        @media (max-width: 768px) {
            .card-image { height: 200px; }
            .card-content { padding: 20px; }
            .main-content { padding: 15px 0; }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <img src="/images/ebilimlogo1.png" alt="Edebiyat Bilimleri" height="34" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="bi bi-house"></i> Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dergiler"><i class="bi bi-journals"></i> Dergiler</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarKurul" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-people"></i> Kurullar
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarKurul">
                            <?php 
                            $navKurulModel = new \App\Models\Kurul($this->pdo);
                            $navKurullar = $navKurulModel->getApproved();
                            foreach($navKurullar as $nk): 
                            ?>
                                <li><a class="dropdown-item" href="/kurul/<?= $nk->id ?>"><?= e($nk->kurul) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarAbout" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-info-circle"></i> Dergi Hakkında
                        </a>
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="navbarAbout">
                            <li><a class="dropdown-item" href="/kunye"><i class="bi bi-file-text me-2"></i> Künye</a></li>
                        </ul>
                    </li>
                </ul>
                
                <div class="d-lg-flex align-items-center">
                    <form class="d-flex mb-3 mb-lg-0 me-lg-3 flex-grow-1 flex-lg-grow-0" action="/makale/ara" method="GET">
                        <div class="input-group">
                            <input class="form-control bg-light border-0" type="search" name="q" placeholder="Makale ara..." aria-label="Search">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    
                    <ul class="navbar-nav align-items-lg-center">
                    <?php if (auth()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5 me-2"></i>
                                <span><?= e($_SESSION['user_name'] ?? 'Hesabım') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item py-2" href="/dashboard">
                                        <i class="bi bi-speedometer2 me-2 text-primary"></i> Panel
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="/profil">
                                        <i class="bi bi-person-gear me-2 text-primary"></i> Profilim
                                    </a>
                                </li>
                                <?php if (isAdmin() || isEditor()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Yönetim</h6></li>
                                    <?php if (isAdmin()): ?>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/users">
                                            <i class="bi bi-people me-2 text-primary"></i> Kullanıcı Yönetimi
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/settings">
                                            <i class="bi bi-gear me-2 text-primary"></i> Genel Ayarlar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/logs">
                                            <i class="bi bi-terminal me-2 text-primary"></i> Sistem Logları
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/stats">
                                            <i class="bi bi-graph-up me-2 text-primary"></i> İstatistikler
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/kurul">
                                            <i class="bi bi-person-lines-fill me-2 text-primary"></i> Dergi Kurulları
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/email-templates">
                                            <i class="bi bi-envelope-paper me-2 text-primary"></i> E-posta Şablonları
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/admin/support">
                                            <i class="bi bi-headset me-2 text-primary"></i> Destek Yönetimi
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="/logout">
                                        <i class="bi bi-box-arrow-right me-2"></i> Çıkış Yap
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="bi bi-box-arrow-in-right"></i> Giriş
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white ms-2 px-3 rounded-pill shadow-sm" href="/kayit" style="color: white !important;">
                                Kayıt Ol
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['flash_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <?= $content ?? '' ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-modern">
        <div class="footer-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"></path>
            </svg>
        </div>
        
        <div class="footer-content">
            <div class="container">
                <?php
                // Load künye data for footer
                $footerKunyeModel = new \App\Models\DergiKunye($this->pdo);
                $footerKunye = $footerKunyeModel->getApprovedGrouped();
                ?>
                <div class="row g-4">
                    <!-- About -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">
                                <img src="images/logo.png" alt="EBP Logo" style="width:66px "> Edebiyat Bilimleri Dergisi
                            </h4>
                            <p class="footer-text">
                                Edebiyat Bilimleri Dergisi, akademik çalışmaları ve araştırmaları 
                                bilim dünyasıyla buluşturan hakemli bir dergidir.
                            </p>
                            <div class="footer-social">
                                <a href="#" class="social-link" title="Twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="social-link" title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="social-link" title="LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="#" class="social-link" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="social-link" title="Email">
                                    <i class="bi bi-envelope-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-subtitle">Hızlı Erişim</h5>
                            <ul class="footer-links">
                                <li><a href="/"><i class="bi bi-chevron-right"></i> Ana Sayfa</a></li>
                                <li><a href="/dergiler"><i class="bi bi-chevron-right"></i> Dergiler</a></li>
                                <li><a href="/hakkimizda"><i class="bi bi-chevron-right"></i> Hakkımızda</a></li>
                                <li><a href="/kurul/2"><i class="bi bi-chevron-right"></i> Yayın Kurulu</a></li>
                                <li><a href="/dizinler"><i class="bi bi-chevron-right"></i> Dizinler</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- For Authors -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-subtitle">Yazarlar İçin</h5>
                            <ul class="footer-links">
                                <li><a href="/makale-esaslari"><i class="bi bi-chevron-right"></i> Makale Esasları</a></li>
                                <li><a href="/yayin-etigi"><i class="bi bi-chevron-right"></i> Yayın Etiği ve İlkeler</a></li>
                                
                                    <?php if (isLoggedIn()): ?>
                                <li><a href="/submissions/create"><i class="bi bi-chevron-right"></i> Makale Gönder</a></li>
                                <?php else: ?>
                                <li><a href="/login?ref=/submissions/create"><i class="bi bi-chevron-right"></i> Makale Gönder</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Editorial Team -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-subtitle">Dergi Künyesi</h5>
                            <ul class="footer-links">
                                <?php if (isset($footerKunye['Kurucusu'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">Kurucusu</strong>
                                        <?php foreach($footerKunye['Kurucusu'] as $kurucu): ?>
                                            <span class="d-block"><?= e($kurucu->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['İmtiyaz Sahibi'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">İmtiyaz Sahibi</strong>
                                        <?php foreach($footerKunye['İmtiyaz Sahibi'] as $imtiyaz): ?>
                                            <span class="d-block"><?= e($imtiyaz->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['Yazı İşleri Müdürü'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">Yazı İşleri Müdürü</strong>
                                        <?php foreach($footerKunye['Yazı İşleri Müdürü'] as $yazi): ?>
                                            <span class="d-block"><?= e($yazi->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['Editör'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">Editör</strong>
                                        <?php foreach($footerKunye['Editör'] as $editor): ?>
                                            <span class="d-block"><?= e($editor->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['issn'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">ISSN</strong>
                                        <?php foreach($footerKunye['issn'] as $issn): ?>
                                            <span class="d-block"><?= e($issn->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['e-issn'])): ?>
                                    <li class="mb-2">
                                        <strong class="d-block text-white-50 small">e-ISSN</strong>
                                        <?php foreach($footerKunye['e-issn'] as $eissn): ?>
                                            <span class="d-block"><?= e($eissn->ad_soyad) ?></span>
                                        <?php endforeach; ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h5 class="footer-subtitle">İletişim</h5>
                            <ul class="footer-contact">
                                <?php if (isset($footerKunye['Adres'])): ?>
                                    <?php foreach($footerKunye['Adres'] as $adres): ?>
                                        <li>
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span><?= nl2br(e($adres->ad_soyad)) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['E-posta adresi'])): ?>
                                    <?php foreach($footerKunye['E-posta adresi'] as $email): ?>
                                        <li>
                                            <i class="bi bi-envelope-fill"></i>
                                            <span><?= e($email->ad_soyad) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if (isset($footerKunye['Telefon'])): ?>
                                    <?php foreach($footerKunye['Telefon'] as $tel): ?>
                                        <li>
                                            <i class="bi bi-telephone-fill"></i>
                                            <span><?= e($tel->ad_soyad) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start">
                            <p class="mb-0">
                                © <?= date('Y') ?> <strong>Edebiyat Bilimleri Dergisi</strong>. Tüm hakları saklıdır.
                            </p>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <p class="mb-0 tech-badge">
                                <i class="bi bi-code-slash"></i> Powered by PHP & MySQL
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
    .footer-modern {
        position: relative;
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        color: #e2e8f0;
        margin-top: 80px;
    }

    .footer-wave {
        position: absolute;
        top: -1px;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
    }

    .footer-wave svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 80px;
        fill: #1a202c;
    }

    .footer-content {
        padding: 80px 0 30px;
    }

    .footer-section {
        margin-bottom: 30px;
    }

    .footer-title {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .footer-title i {
        font-size: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-text {
        color: #cbd5e0;
        line-height: 1.8;
        margin-bottom: 25px;
    }

    .footer-subtitle {
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-subtitle::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .footer-social {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .social-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #cbd5e0;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a:hover {
        color: #667eea;
        padding-left: 5px;
    }

    .footer-links a i {
        font-size: 0.8rem;
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact li {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        color: #cbd5e0;
    }

    .footer-contact i {
        color: #667eea;
        font-size: 1.2rem;
        min-width: 20px;
    }

    .footer-bottom {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid rgba(255,255,255,0.1);
    }

    .footer-bottom p {
        color: #a0aec0;
        font-size: 0.95rem;
    }

    .tech-badge {
        font-size: 0.9rem;
    }

    .tech-badge i {
        color: #667eea;
        margin: 0 3px;
    }

    @media (max-width: 768px) {
        .footer-content {
            padding: 60px 0 20px;
        }
        
        .footer-wave svg {
            height: 50px;
        }
        
        .footer-bottom .col-md-6 {
            margin-bottom: 10px;
        }
    }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Validation Script -->
    <script>
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })()
    </script>
</body>
</html>
