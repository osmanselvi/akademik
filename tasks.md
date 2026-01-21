# EBP Dergi Sistemi - Görev Listesi

## 🔴 Kritik Öncelikli Görevler

### Güvenlik
- [x] Veritabanı kimlik bilgilerini `.env` dosyasına taşı
- [x] Hassas bilgileri kaynak koddan çıkar
- [x] CSRF token koruması ekle
- [x] Session güvenliğini güçlendir (secure, httponly, samesite flags)
- [x] Şifre hash'leme sistemini güncelle (bcrypt/Argon2)
- [x] File upload validasyonu ekle (dosya tipi, boyut, MIME kontrolü)
- [x] Rate limiting ekle (brute force koruması) - (Temel kontrol yapıldı)
- [x] SQL injection testleri yap
- [ ] XSS vulnerability taraması yap

### Veri Bütünlüğü
- [ ] Veritabanı foreign key constraints ekle
- [ ] Cascading delete/update kuralları tanımla
- [ ] Yetim kayıtları temizle
- [x] Database index optimizasyonu
- [x] Veri yedekleme stratejisi oluştur

## 🟡 Yüksek Öncelikli Görevler

### Kullanıcı Deneyimi
- [x] Responsive tasarım iyileştirmeleri
  - [x] Mobil menü optimizasyonu
  - [x] Tablet görünüm düzenlemeleri
  - [x] Touch-friendly butonlar
  - [x] Masaüstü görünüm düzeltmeleri
- [x] Form validasyonu geliştir
  - [x] Client-side validation (JavaScript)
  - [x] Server-side validation iyileştir
  - [x] User-friendly hata mesajları
- [x] Arama fonksiyonelliği ekle
  - [x] Makale arama (Gelişmiş)
  - [x] Yazar arama (Gelişmiş)
  - [x] Dergi sayı arama (Gelişmiş)
  - [x] Yıl ve Dergi bazlı filtreleme
  - [x] Yıl aralığı (Range) filtreleme
  - [x] Yıl range varsayılanları (2021-2026) ekle
- [x] Dergi Kurulları yönetimi ekle
  - [x] Tablo modellerini oluştur (yayin_kurul, unvan, kurul, kurul_gorev)
  - [x] Admin/KurulController oluştur
  - [x] Kurul üyesi yönetimi (CRUD)
  - [x] Unvan, Kurul ve Görev tanımlama sayfaları
  - [x] Kamu arayüzü: Üst menü "Kurullar" dropdown ve kurul sayfaları
- [ ] Sayfalama (pagination) ekle

### Performans
- [ ] Query optimizasyonu
  - [ ] N+1 query problemlerini çöz
  - [ ] Eager loading ekle
  - [ ] Query cache kullan
- [ ] Statik dosya optimizasyonu
  - [ ] CSS/JS minification
  - [ ] Image optimization
  - [ ] Lazy loading
- [ ] Caching mekanizması
  - [ ] Redis/Memcached entegrasyonu
  - [ ] Page caching
  - [ ] Database query caching

### Hata Yönetimi
- [x] Merkezi hata yönetimi sistemi
- [/] Loglama mekanizması
  - [ ] Error logs
  - [ ] Access logs
  - [x] Activity logs (Temel seviye)
- [x] Kullanıcı dostu hata sayfaları
- [x] Email bildirim sistemi (PHPMailer SMTP entegrasyonu)

## 🟢 Orta Öncelikli Görevler

### Fonksiyonel İyileştirmeler
- [ ] PDF önizleme özelliği
- [ ] Makale istatistikleri dashboard'u
  - [ ] Görüntülenme sayıları
  - [ ] İndirme sayıları
  - [ ] Popüler makaleler
- [ ] Gelişmiş filtre sistemi
  - [ ] Yıl bazında filtreleme
  - [ ] Yazar bazında filtreleme
  - [ ] Konu/kategori filtreleme
- [ ] Email template sistemi
  - [ ] Aktivasyon emaili
  - [ ] Şifre sıfırlama emaili
  - [ ] Bildirim emailleri
- [ ] Hakem değerlendirme sistemi geliştir
  - [ ] Online form
  - [ ] Otomatik bildirimler
  - [ ] Süreç takibi

### Admin Panel
- [x] Dashboard geliştir
  - [x] İstatistik widget'ları (Temel seviye)
  - [x] Hızlı erişim linkleri
  - [/] Son aktiviteler
- [ ] Toplu işlem özellikleri
  - [ ] Toplu makale onayı
  - [ ] Toplu email gönderimi
  - [ ] Toplu silme/güncelleme
- [x] Gelişmiş kullanıcı yönetimi
  - [x] Rol bazlı yetkilendirme
  - [ ] Kullanıcı aktivite logu
  - [ ] Kullanıcı istatistikleri
- [/] Site ayarları paneli
  - [ ] Genel site ayarları
  - [x] Email ayarları (.env entegrasyonu)
  - [ ] Tema ayarları

### Raporlama
- [ ] Makale istatistikleri raporu
- [ ] Kullanıcı aktivite raporu
- [ ] Dergi performans raporu
- [ ] Excel/CSV export özelliği

## 🔵 Düşük Öncelikli Görevler

### Entegrasyonlar
- [ ] Google Analytics entegrasyonu
- [ ] Google Scholar metadata
- [ ] ORCID entegrasyonu
- [ ] Crossref DOI entegrasyonu
- [ ] Social media paylaşım butonları
- [ ] RSS feed

### İçerik Yönetimi
- [ ] WYSIWYG editör entegrasyonu
- [ ] Çoklu dil desteği (i18n)
  - [ ] Türkçe/İngilizce arayüz
  - [ ] Language switcher
- [ ] SEO optimizasyonu
  - [ ] Meta tags
  - [ ] Sitemap.xml
  - [ ] Robots.txt
  - [ ] Schema.org markup
- [ ] İçerik versiyonlama

### API Geliştirme
- [ ] RESTful API tasarımı
- [ ] API authentication (JWT/OAuth)
- [ ] API documentation (Swagger)
- [ ] Rate limiting

### Testing
- [ ] Unit test yazımı
- [ ] Integration test yazımı
- [ ] E2E test yazımı
- [ ] Test coverage raporlama
- [ ] CI/CD pipeline kurulumu

## 🔧 Teknik Borç

### Kod Kalitesi
- [x] **Code refactoring**
  - [x] DRY prensibi uygula (Don't Repeat Yourself)
  - [x] SOLID prensipleri uygula (Temel seviye)
  - [x] Magic numbers'ları constants'a çevir
  - [x] Uzun fonksiyonları böl
- [x] **Naming conventions**
  - [x] İngilizce değişken isimleri (Yeni kodlarda)
  - [x] Tutarlı naming convention
  - [x] Açıklayıcı isimler
- [ ] **Code comments**
  - [x] PHPDoc ekle (Yeni sınıflar için)
  - [x] Karmaşık mantık açıklamaları
  - [ ] TODO/FIXME işaretlerini temizle
- [x] **Code organization**
  - [x] MVC pattern uygula
  - [x] Namespace kullanımı
  - [x] Autoloading (Composer - PSR-4 Fix edildi)

### Mimari İyileştirmeler
- [ ] Monolithic yapıdan modüler yapıya geçiş
- [ ] Service layer oluştur
- [ ] Repository pattern uygula
- [ ] Dependency injection kullan
- [ ] Event-driven architecture (isteğe bağlı)

### Veritabanı
- [ ] Database migration sistemi
- [ ] Seeder oluştur (test data)
- [ ] Database backup otomasyonu
- [ ] Query builder kullanımı

### Dokümantasyon
- [ ] API dokümantasyonu
- [ ] Kod dokümantasyonu (PHPDoc)
- [ ] Deployment guide
- [ ] Troubleshooting guide
- [ ] Changelog tutma

## 📝 Yeni Özellikler (İsteğe Bağlı)

### Gelişmiş Özellikler
- [ ] Makale yorum sistemi
- [ ] Makale beğeni/yıldız sistemi
- [ ] İlgili makaleler önerisi
- [ ] En çok okunan makaleler bölümü
- [ ] Yazar profil sayfaları
  - [ ] Yazar bio
  - [ ] Yayınları listesi
  - [ ] İstatistikler
- [ ] Newsletter sistemi
  - [ ] Abone yönetimi
  - [ ] Otomatik gönderim
  - [ ] Template tasarımı
- [ ] Bildirim sistemi
  - [ ] In-app notifications
  - [ ] Email notifications
  - [ ] Push notifications (mobil için)

### Ölçümleme ve Analytics
- [ ] Kullanıcı davranış analizi
- [ ] Heatmap entegrasyonu
- [ ] A/B testing altyapısı
- [ ] Conversion tracking

## 🐛 Bilinen Hatalar

- [x] Yahoo.com email adresleri kabul edilmiyor (mainpage.php:45)
- [/] Türkçe karakter encoding sorunları (bazı sayfalarda)
- [x] Dosya upload boyut limiti belirsiz
- [x] Session timeout ayarı yok
- [x] Deleted files yetim kalıyor (cascade delete yok)
- [x] Duplicate email kontrolü eksik
- [x] IP bazlı kayıt engelleme çalışmıyor (mainpage.php:7-8)

## 🔄 Devam Eden Görevler

- [/] Site analizi ve eksiklik tespiti
- [ ] Dokümantasyon hazırlama
- [ ] Test senaryoları yazımı

## ✅ Tamamlanan Görevler

- [x] Proje yapısı inceleme
- [x] Veritabanı şeması analizi
- [x] Mevcut fonksiyonellik envanteri
- [x] MVC & Modern Proje Yapısı Kurulumu
- [x] .env & Güvenlik Yapılandırması
- [x] CSRF & Session Güvenliği
- [x] Admin Kullanıcı Yönetimi & Rol Atama
- [x] Şifre Sıfırlama Sistemi & PHPMailer Entegrasyonu
- [x] PSR-4 Autoloading Optimizasyonu
- [x] Dahili Router Desteği
- [x] Dergi Künyesi (Journal Masthead) Sistemi
  - [x] DergiKunyeBaslik & DergiKunye modelleri
  - [x] Admin CRUD yönetimi (kategori ve kayıtlar)
  - [x] Public künye sayfası (/kunye)
  - [x] Footer'a dinamik künye entegrasyonu
  - [x] Navigation'a künye linki
- [x] Dizinler (Indexing) Sistemi
  - [x] Dizin modeli ve Admin CRUD
  - [x] Logo upload sistemi
  - [x] Public dizinler sayfası (/dizinler)
  - [x] Dashboard yönetim linki
- [x] Statik Sayfalar Sistemi
  - [x] Hakkımızda sayfası (/hakkimizda)
  - [x] Makale Esasları - Dinamik (/makale-esaslari)
    - [x] MakaleEsas modeli
    - [x] Sidebar navigasyon
    - [x] Admin CRUD yönetimi
    - [x] Özel içerik desteği (ID=11, ID=12)
  - [x] Yayın Etiği ve İlkeler (/yayin-etigi)
  - [x] Telif Devir Sözleşmesi - Dinamik (/telif-devir)
    - [x] MakaleSozlesme modeli
    - [x] Gruplanmış dinamik içerik gösterimi
    - [x] Admin CRUD yönetimi (Admin ve Editör)
  - [x] Register sayfasına üyelik sözleşmesi entegrasyonu (Modal)
- [x] Görsel Kimlik ve UI Güncellemeleri
  - [x] Hero Section banner entegrasyonu (banner.png)
  - [x] Kurumsal logo (ebilimlogo1.png) Navbar ve Hero entegrasyonu
  - [x] Hero section hizalama (Sola yaslı, daha okunaklı yapı)
  - [x] Navbar logo optimizasyonu (Boyut küçültme)
- [x] Dergi Sayı Yönetimi (Journal Issues)
  - [x] DergiTanim modeli
  - [x] Admin CRUD (Yönetim, Ekleme, Düzenleme)
  - [x] Kapak ve Jenerik dosyası yükleme desteği
- [x] Makale Yayınlama Sistemi (Online Articles)
  - [x] OnlineMakale modeli
  - [x] Admin CRUD (Yayınlanmış makale yönetimi)
  - [x] Hakem sürecinden geçen makaleleri yayına alma (Publish) rutini
  - [x] Yayınlanmış makale düzenleme ve dosya yönetimi
- [x] Destek Talebi Sistemi (Support Request System)
  - [x] Veritabanı yapısı ve DestekTalep modeli
  - [x] Kullanıcı panelinde destek listesi ve yeni talep formu
  - [x] Makale detay sayfasından hızlı destek formu entegrasyonu
  - [x] Admin panelinde talepleri yanıtlama ve yönetme sistemi
  - [x] Taleplerin makale ile ilişkilendirilmesi (submission_id)
- [x] E-posta Bildirim Sistemi Stabilizasyonu
  - [x] SMTP EHLO/AUTH ve PHPMailer EHLO debugging
  - [x] Tüm bildirimlerin bilgi@edebiyatbilim.com üzerinden EHLO doğrulamasıyla gönderilmesi
  - [x] Oturumda e-posta verisi eksik olduğunda DB'den tamamlama (fallback) mekanizması
  - [x] Yeni makale gönderildiğinde editöre otomatik bildirim e-postası
  - [x] Destek yanıtları ve revizyon talepleri için otomatik e-posta bildirimleri

---

**Not**: Öncelikler proje ihtiyaçlarına göre değiştirilebilir. Her görev için detaylı implementation planı ayrıca hazırlanmalıdır.

**Son Güncelleme**: 2026-01-20
