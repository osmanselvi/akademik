# Edebiyat Bilimleri Dergisi (EBP) - Sistem Dokümantasyonu

## Genel Bakış

**Edebiyat Bilimleri Dergisi (Journal of Literary Sciences)** web sitesi, akademik bir dergi yönetim sistemidir. PHP ve MySQL teknolojileri kullanılarak geliştirilmiş bu platform, makale gönderimi, hakem süreci, dergi yayınlama ve kullanıcı yönetimi gibi akademik yayıncılık süreçlerini desteklemektedir.

## Proje Yapısı

### Ana Dizinler

#### `wwwroot/`
- **Amaç**: Ana web uygulaması dosyaları
- **İçerik**: 
  - `index.php` - Ana sayfa
  - `login.php` - Kullanıcı girişi
  - `kayitol.php` - Yeni üye kaydı
  - `dergigoster.php` - Dergi sayılarını görüntüleme
  - `makale_*.php` - Makale yönetimi sayfaları
  - `dergi_*.php` - Dergi yönetimi sayfaları

#### `include/`
Paylaşılan bileşenler ve yardımcı dosyalar:

- **`include/araclar/`**: Temel araçlar ve yardımcılar
  - `baglanti.php` - Veritabanı bağlantısı
  - `functions.php` - Yardımcı fonksiyonlar (667 satır)
  - `ayarlar.php` - Sistem ayarları
  - `navbar.php` - Navigasyon menüsü
  - `footer.php` - Sayfa altbilgisi
  - `head.php` - HTML head bölümü

- **`include/dergi/`**: Dergi görüntüleme bileşenleri
  - `dergi_goster.php` - Dergi sayı detayları

- **`include/makale/`**: Makale işleme bileşenleri

- **`include/kurul/`**: Kurul ve editör yönetimi

- **`include/menu/`**: Menü yönetimi

#### `ydergi/`
Yeni nesil dergi sistemi (Modern PHP mimarisi):
- **`app/`**: Uygulama mantığı
- **`config/`**: Yapılandırma dosyaları
- **`database/`**: SQL şemaları ve migration'lar
- **`public/`**: Genel erişime açık dosyalar
- **`routes/`**: URL yönlendirmeleri

#### `isler/`
İş akışları ve görev yönetimi

## Teknoloji Yığını

### Backend
- **PHP**: Ana programlama dili
- **PDO (PHP Data Objects)**: Veritabanı erişim katmanı
- **MySQL**: İlişkisel veritabanı yönetim sistemi
  - Ana veritabanı: `ebph_server`
  - Karakter seti: UTF-8 (Türkçe karakter desteği)

### Frontend
- **HTML5**: Sayfa yapısı
- **Bootstrap**: Responsive UI framework
- **Bootstrap Icons**: İkon kütüphanesi
- **JavaScript**: İstemci tarafı etkileşim
- **CSS/SCSS**: Stil yönetimi

### Güvenlik
- **Parameterized Queries**: SQL injection koruması
- **htmlspecialchars()**: XSS koruması
- **strip_tags()**: HTML tag filtreleme
- **Özel filtrele() fonksiyonu**: Input sanitization

## Veritabanı Yapısı

### Ana Tablolar

#### Kullanıcı Yönetimi
- `yonetim` - Yönetici/kullanıcı hesapları
- `uye` - Üye bilgileri
- `usergroup` - Kullanıcı grupları ve yetkileri

#### Dergi Yönetimi
- `dergi_tanim` - Dergi sayı tanımları
  - `k_no` (Primary Key)
  - `dergi_tanim` - Türkçe başlık
  - `ing_baslik` - İngilizce başlık
  - `dergi_kapak` - Kapak görseli
  - `jenerikdosyasi` - Jenerik PDF
  - `yayin_tarih` - Yayın tarihi
  - `is_approved` - Durum (0=Geçmiş, 1=Güncel)

- `dergi_kunye` - Dergi künye bilgileri
- `dergi_kunye_baslik` - Künye başlıkları

#### Makale Yönetimi
- `online_makale` - Yayınlanmış makaleler
  - `k_no` (Primary Key)
  - `dergi_tanim` (Foreign Key)
  - `makale_baslik` - Başlık
  - `makale_yazar` - Yazar(lar)
  - `makale_ozet` - Özet
  - `anahtar_kelime` - Anahtar kelimeler
  - `kaynakca` - Kaynaklar
  - `dosya` - PDF dosya adı
  - `makale_turu` - Makale tipi (1=Normal, 2=Detaylı)

- `gonderilen_makale` - Gönderilen/değerlendirmedeki makaleler
- `makale_tur` - Makale türleri
- `makale_esas` - Makale esasları
- `makale_sozlesme` - Makale sözleşmeleri

#### Organizasyonel
- `kurul` - Kurul tanımları
- `kurul_gorev` - Kurul görevleri ve üyeleri
- `yayin_kurul` - Yayın kurulu
- `editorler` - Editör bilgileri
- `hakem_kriter` - Hakem değerlendirme kriterleri

#### Referans Tabloları
- `ana_dal` - Ana bilim dalları
- `bilim_dali` - Bilim dalları
- `dil` - Dil seçenekleri
- `unvan` - Akademik unvanlar

#### Menü Sistemi
- `anamenu` - Ana menü öğeleri
- `altmenu1` - Alt menü öğeleri

## Temel Fonksiyonlar

### Güvenlik ve Veri Filtreleme

```php
filtrele($deger)           // Form verilerini temizle
fn_temizle($veri)          // SQL injection koruması
Guvenlik($deger)           // Genel güvenlik filtreleme
```

### Dergi İşlemleri

```php
fn_dergi_tarih($sayi)      // Dergi yayın tarihini al
fn_tr_baslik($sayi)        // Türkçe dergi başlığı
fn_en_baslik($sayi)        // İngilizce dergi başlığı
fn_dergi_jenerik($sayi)    // Jenerik dosya adı
fn_dergi_kapak($sayi)      // Kapak görsel dosyası
fn_dergi_durum($sayi)      // Güncel/geçmiş durumu
```

### Makale İşlemleri

```php
fn_makale_baslik($makale_id, $dergi_id)     // Makale başlığı
fn_makale_yazar($makale_id, $dergi_id)      // Yazar bilgisi
fn_makale_ozet($makale_id, $dergi_id)       // Özet metni
fn_anahtar_kelime($makale_id, $dergi_id)    // Anahtar kelimeler
fn_makale_kaynakca($makale_id, $dergi_id)   // Kaynakça
fn_makale_dosya($makale_id, $dergi_id)      // PDF dosya
fn_makale_kunye($makale_id)                 // Künye bilgisi
```

### Türkçe Karakter İşleme

```php
replace_tr($text)          // Bozuk Türkçe karakterleri düzelt
mb_strtoupper_tr($metin)   // Türkçe'ye uygun büyük harf
```

### Form Oluşturma

```php
fn_formgiris($alanad, $alantip)     // Veri giriş formu
fn_formduzenle($alanad, $alantip)   // Düzenleme formu
fn_formsil($alanad, $alantip)       // Silme onay formu
```

**Alan Tipleri:**
- 1: Text input
- 2: Number input
- 3: Checkbox
- 4: Radio button
- 5: Select dropdown
- 6: Hidden field
- 7: File upload
- 8: Textarea

## Kullanıcı Akışları

### Yeni Üye Kaydı
1. `kayitol.php` - Kayıt formu
2. `kayitol_islem.php` - Form işleme
3. Email ile aktivasyon
4. `aktivasyon.php` - Hesap aktivasyonu

### Makale Gönderimi
1. Kullanıcı girişi (`login.php`)
2. Makale gönder menüsüne erişim
3. Makale detaylarını doldurma
4. PDF yükleme
5. Gönderim ve hakem ataması

### Dergi Görüntüleme
1. `dergigoster.php?dergi_id={id}` - Dergi sayısı seçimi
2. Makale listesi görüntüleme
3. PDF indirme veya detay görüntüleme
4. `makale_goster.php` - Makale detayı (özet, anahtar kelime, kaynakça)

## Konfigürasyon

### Veritabanı Bağlantıları

**Aktif Bağlantı** (`include/araclar/baglanti.php`):
```php
Host: localhost
Database: ebph_server
User: root
Password: st63pc71x
```

**Alternatif Konfigürasyon** (`pdo_config.php`):
```php
Host: localhost
Database: ebp_server
User: ebp_sevrer
Password: 99882580
```

## Dosya Yapısı ve Organizasyon

### Statik Dosyalar
- **`wwwroot/images/`**: Kapak görselleri, logolar
- **`wwwroot/makaleler/`**: Makale PDF dosyaları
- **`wwwroot/belgeler/`**: Sistem belgeleri
- **`wwwroot/css/`**: Stil dosyaları
- **`wwwroot/js/`**: JavaScript dosyaları
- **`wwwroot/lib/`**: Kütüphaneler

### CRUD Dosya Şablonu
Her veri yönetimi için 2 dosya:
- `{modul}_edit.php` - Form arayüzü
- `{modul}_islem.php` - Veri işleme

## Önemli Notlar

### Güvenlik
- ✅ PDO prepared statements kullanılıyor
- ✅ Input filtreleme mevcut
- ⚠️ Veritabanı bilgileri kaynak kodda

### Karakter Kodlama
- Veritabanı: UTF-8
- Türkçe karakter sorunları için `replace_tr()` fonksiyonu
- Windows-1254 → UTF-8 dönüşümü

### Email Kısıtlamaları
> ⚠️ **Önemli**: Yahoo.com email adresleri kabul edilmemektedir (`mainpage.php` line 45)

## Başlangıç Kılavuzu

### 1. Sistem Gereksinimleri
- PHP 7.0+
- MySQL 5.7+
- Web sunucu (Apache/Nginx)
- PDO MySQL uzantısı

### 2. Kurulum
```bash
# Veritabanını içe aktarın
mysql -u root -p ebph_server < ydergi/database/ebp_server.sql

# Veritabanı bağlantısını yapılandırın
# include/araclar/baglanti.php dosyasını düzenleyin
```

### 3. İlk Giriş
- Admin paneline erişim için yönetim tablosunda kullanıcı oluşturun
- Varsayılan giriş sayfası: `/wwwroot/login.php`

## Destek ve Katkı

Bu dokümantasyon, mevcut sistemi anlamak ve geliştirmek için hazırlanmıştır. Detaylı bilgi için diğer dokümantasyon dosyalarına bakınız:

- [`tasks.md`](./tasks.md) - Görev listesi
- [`oneriler.md`](./oneriler.md) - Sistem iyileştirme önerileri
- [`roadmap.md`](./roadmap.md) - Geliştirme yol haritası
- [`walkthrough.md`](./walkthrough.md) - Sistem kullanım rehberi
- [`implementation_plan.md`](./implementation_plan.md) - Uygulama planı

---

**Son Güncelleme**: 2026-01-20  
**Versiyon**: 1.0
