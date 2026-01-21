# EBP Dergi Sistemi - Ydergi Modülü

Modern PHP 7.4+ ile geliştirilmiş, MVC mimarisine sahip akademik dergi yönetim sistemi.

## 🚀 Kurulum

### 1. Composer Bağımlılıklarını Yükle

```powershell
composer install
```

### 2. Environment Ayarları

`.env` dosyasını oluştur:

```powershell
copy .env.example .env
```

`.env` dosyasını düzenleyerek veritabanı bilgilerini güncelle:

```env
DB_HOST=localhost
DB_DATABASE=ebph_server
DB_USERNAME=root
DB_PASSWORD=st63pc71x
```

### 3. Development Server Başlat

```powershell
# PHP built-in server ile
php -S localhost:8000 -t public

# Veya XAMPP/WAMP kullanıyorsanız
# public/ klasörünü document root olarak ayarlayın
```

Tarayıcıda http://localhost:8000 adresini aç.

## 📁 Dizin Yapısı

```
ydergi/
├── app/
│   ├── controllers/      # Controller sınıfları
│   │   ├── BaseController.php
│   │   └── DergiController.php
│   ├── models/           # Model sınıfları
│   │   ├── BaseModel.php
│   │   ├── Dergi.php
│   │   └── Makale.php
│   ├── helpers/          # Yardımcı sınıflar
│   │   ├── functions.php
│   │   ├── CSRF.php
│   │   ├── FileUpload.php
│   │   └── Validator.php
│   └── views/            # View dosyaları
├── config/               # Konfigürasyon dosyaları
│   ├── app.php
│   └── database.php
├── public/               # Public dosyalar (document root)
│   ├── index.php
│   ├── .htaccess
│   └── assets/
├── storage/              # Yüklenen dosyalar, loglar
│   ├── logs/
│   ├── cache/
│   └── uploads/
├── database/             # SQL dosyaları
├── routes/               # Route tanımlamaları
├── vendor/               # Composer bağımlılıkları
├── .env.example          # Environment template
├── .gitignore
├── bootstrap.php         # App başlatma
├── composer.json
└── README_SETUP.md       # Bu dosya
```

## 🔧 Temel Kullanım

### Yeni Model Oluşturma

```php
<?php
namespace App\Models;

class YeniModel extends BaseModel {
    protected $table = 'tablo_adi';
    protected $softDeletes = true; // Soft delete kullan
    
    // Özel metodlar
    public function customMethod() {
        // ...
    }
}
```

### Yeni Controller Oluşturma

```php
<?php
namespace App\Controllers;

class YeniController extends BaseController {
    public function index() {
        $this->view('klasor.dosya', [
            'data' => 'value'
        ]);
    }
}
```

### View Oluşturma

```php
<!-- app/views/klasor/dosya.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?? 'EBP Dergi' ?></title>
</head>
<body>
    <h1><?= $data ?></h1>
</body>
</html>
```

## 🔒 Güvenlik Özellikleri

### CSRF Koruması

```php
use App\Helpers\CSRF;

// Form'da
echo CSRF::field();

// Doğrulama
CSRF::verify(); // Otomatik die() yapar
```

### File Upload

```php
use App\Helpers\FileUpload;

$uploader = new FileUpload();
$filename = $uploader->upload($_FILES['file']);
```

### Validation

```php
use App\Helpers\Validator;

$validator = Validator::make($_POST, [
    'email' => 'required|email',
    'name' => 'required|min:3|max:100',
    'age' => 'numeric'
]);

if ($validator->fails()) {
    $errors = $validator->errors();
}
```

## 📦 Helper Fonksiyonlar

```php
// Environment
$value = env('APP_DEBUG', false);

// Config
$name = config('app.name');

// URL
$link = url('path/to/page');

// Auth
if (auth()) {
    $userId = userId();
}

// Redirect
redirect('/login');

// JSON Response
jsonResponse(['success' => true]);
```

## 🗄️ Database İşlemleri

```php
$dergiModel = new Dergi($pdo);

// Tüm kayıtları getir
$dergiler = $dergiModel->all();

// ID ile bul
$dergi = $dergiModel->find(1);

// Yeni kayıt
$id = $dergiModel->create([
    'dergi_tanim' => 'Cilt 5 Sayı 1',
    'yayin_tarih' => '2026-01-20'
]);

// Güncelle
$dergiModel->update(1, [
    'dergi_tanim' => 'Güncellenmiş Başlık'
]);

// Sil (soft delete)
$dergiModel->delete(1);

// Pagination
$result = $dergiModel->paginate($page = 1, $perPage = 20);
// $result['data'], $result['total'], $result['current_page']
```

## 🧪 Testing

```powershell
# PHPUnit testleri çalıştır
composer test

# Code style check
composer cs-check

# Code style fix
composer cs-fix

# Static analysis
composer stan
```

## 📝 Yapılacaklar

- [ ] Route sistemi implement et
- [ ] Migration sistemi ekle
- [ ] Email service ekle
- [ ] Middleware sistemi
- [ ] CLI komutları
- [ ] API endpoints
- [ ] Unit/Integration tests yaz

## 🤝 Katkıda Bulunma

1. Feature branch oluştur
2. Değişikliklerini commit et
3. Tests yaz
4. Pull request aç

## 📄 Lisans

MIT

## 📞 Destek

Sorun bildirmek için issue aç veya ekiple iletişime geç.

---

**Not**: Bu modern PHP yapısı, eski `wwwroot/` sisteminin yanında paralel çalışabilir. Aşamalı migration önerilir.
