# EBP Dergi Sistemi - Uygulama Planı

## 📋 Genel Bakış

Bu dokümantasyon, EBP (Edebiyat Bilimleri Dergisi) web sisteminin mevcut durumunu tanımlar ve önerilen iyileştirmelerin teknik detaylarını içerir.

### Mevcut Durum
- **Platform**: PHP/MySQL tabanlı monolitik web uygulaması
- **Veritabanı**: MySQL (ebph_server)
- **Frontend**: HTML, Bootstrap, jQuery
- **Mimari**: Prosedürel PHP, karma include yapısı

### Hedef
Modern, güvenli, ölçeklenebilir ve sürdürülebilir akademik dergi yönetim sistemi

---

## 🎯 Faz 1: Güvenlik Kritik İyileştirmeler

### 1.1 Environment Configuration

#### Mevcut Kod
```php
// include/araclar/baglanti.php (MEVCUT - GÜVENLİ DEĞİL)
$baglanti=new PDO("mysql:host=localhost; dbname=ebph_server; charset:UTF8","root","st63pc71x");
```

#### Önerilen Implementasyon

**Adım 1**: `.env` dosyası oluştur
```bash
# .env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ebph_server
DB_USERNAME=ebp_user
DB_PASSWORD=SecureP@ssw0rd123!

APP_ENV=production
APP_DEBUG=false
APP_URL=https://ebp-dergi.com
APP_KEY=base64:generatedkey

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@ebp-dergi.com
MAIL_PASSWORD=emailpass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ebp-dergi.com
MAIL_FROM_NAME="EBP Dergi"
```

**Adım 2**: Composer ile vlucas/phpdotenv yükle
```bash
composer require vlucas/phpdotenv
```

**Adım 3**: Bootstrap dosyası oluştur
```php
// bootstrap.php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
function getDatabase() {
    static $pdo = null;
    
    if ($pdo === null) {
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        $charset = 'utf8mb4';
        
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ];
        
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            die('Veritabanı bağlantısı başarısız');
        }
    }
    
    return $pdo;
}

$baglanti = getDatabase();
```

**Adım 4**: Tüm dosyalarda güncelle
```php
// Her PHP dosyasının başına
require_once __DIR__ . '/../bootstrap.php';
```

**Adım 5**: `.gitignore` güncelle
```
.env
vendor/
composer.lock
```

---

### 1.2 Password Security

#### Mevcut Durum Analizi
```php
// Şifre hash'leme yöntemi belirsiz, muhtemelen MD5 veya plain text
```

#### Implementasyon

**Adım 1**: Yeni kayıtlar için güvenli hash
```php
// kayitol_islem.php
$plainPassword = $_POST['sifre'];

// Argon2id kullan (en güvenli)
$hashedPassword = password_hash($plainPassword, PASSWORD_ARGON2ID, [
    'memory_cost' => 65536, // 64MB
    'time_cost' => 4,
    'threads' => 2
]);

// Veritabanına kaydet
$sql = "INSERT INTO yonetim (ad_soyad, email, sifre, grup_id) VALUES (?, ?, ?, ?)";
$stmt = $baglanti->prepare($sql);
$stmt->execute([$adSoyad, $email, $hashedPassword, 9999]);
```

**Adım 2**: Giriş doğrulama
```php
// login.php işleme
$email = filtrele($_POST['email']);
$password = $_POST['sifre'];

$sql = "SELECT * FROM yonetim WHERE email = ? LIMIT 1";
$stmt = $baglanti->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if ($user && password_verify($password, $user->sifre)) {
    // Giriş başarılı
    $_SESSION['user_id'] = $user->k_no;
    $_SESSION['user_name'] = $user->ad_soyad;
    $_SESSION['grup_id'] = $user->grup_id;
    
    // Hash algoritması eski ise yeniden hash'le
    if (password_needs_rehash($user->sifre, PASSWORD_ARGON2ID)) {
        $newHash = password_hash($password, PASSWORD_ARGON2ID);
        $updateSql = "UPDATE yonetim SET sifre = ? WHERE k_no = ?";
        $updateStmt = $baglanti->prepare($updateSql);
        $updateStmt->execute([$newHash, $user->k_no]);
    }
    
    header('Location: dashboard.php');
    exit;
} else {
    $error = "Hatalı email veya şifre";
}
```

**Adım 3**: Mevcut kullanıcıları migrate et
```php
// migrate_passwords.php (bir kere çalıştır)
$users = $baglanti->query("SELECT k_no, email FROM yonetim")->fetchAll(PDO::FETCH_OBJ);

foreach ($users as $user) {
    // Geçici şifre oluştur
    $tempPassword = bin2hex(random_bytes(8));
    $hashedPassword = password_hash($tempPassword, PASSWORD_ARGON2ID);
    
    // Güncelle
    $sql = "UPDATE yonetim SET sifre = ? WHERE k_no = ?";
    $stmt = $baglanti->prepare($sql);
    $stmt->execute([$hashedPassword, $user->k_no]);
    
    // Kullanıcıya email gönder
    sendPasswordResetEmail($user->email, $tempPassword);
}
```

---

### 1.3 CSRF Protection

#### Implementasyon

**Adım 1**: CSRF Token Helper
```php
// include/araclar/csrf.php
class CSRF {
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function getTokenField() {
        $token = self::generateToken();
        return "<input type='hidden' name='csrf_token' value='{$token}'>";
    }
}
```

**Adım 2**: Tüm formlara token ekle
```php
// Örnek: kayitol.php
<form method="POST" action="kayitol_islem.php">
    <?php echo CSRF::getTokenField(); ?>
    <!-- Diğer form alanları -->
</form>
```

**Adım 3**: Form işlemlerinde doğrula
```php
// kayitol_islem.php
require_once '../include/araclar/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CSRF::validateToken($_POST['csrf_token'] ?? '')) {
        die('CSRF token doğrulaması başarısız');
    }
    
    // Form işleme devam et...
}
```

---

### 1.4 File Upload Security

#### Mevcut Sorunlar
- MIME type kontrolü yok
- Dosya boyut limiti belirsiz
- Dosya adı sanitization eksik

#### Implementasyon

**Adım 1**: Upload Helper sınıfı
```php
// include/araclar/FileUpload.php
class FileUpload {
    private $allowedMimes = ['application/pdf'];
    private $maxSize = 10485760; // 10MB
    private $uploadDir = __DIR__ . '/../../wwwroot/makaleler/';
    
    public function upload($file) {
        // Dosya var mı kontrol
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Dosya yükleme hatası');
        }
        
        // Boyut kontrolü
        if ($file['size'] > $this->maxSize) {
            throw new Exception('Dosya boyutu çok büyük (max 10MB)');
        }
        
        // MIME type kontrolü
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedMimes)) {
            throw new Exception('Sadece PDF dosyası yüklenebilir');
        }
        
        // Güvenli dosya adı oluştur
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeFilename = uniqid('makale_', true) . '.' . $extension;
        $destination = $this->uploadDir . $safeFilename;
        
        // Taşı
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Dosya kaydedilemedi');
        }
        
        return $safeFilename;
    }
}
```

**Adım 2**: Kullanım
```php
// gonderilen_makale_islem.php
require_once '../include/araclar/FileUpload.php';

try {
    $uploader = new FileUpload();
    $filename = $uploader->upload($_FILES['makale_dosya']);
    
    // Veritabanına kaydet
    $sql = "INSERT INTO gonderilen_makale (baslik, dosya, ...) VALUES (?, ?, ...)";
    // ...
} catch (Exception $e) {
    $error = $e->getMessage();
}
```

---

## 🎯 Faz 2: Database Refactoring

### 2.1 Foreign Key Constraints

#### Migration Script
```sql
-- migrations/001_add_foreign_keys.sql

-- online_makale için
ALTER TABLE online_makale
ADD CONSTRAINT fk_online_makale_dergi
FOREIGN KEY (dergi_tanim) REFERENCES dergi_tanim(k_no)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE online_makale
ADD CONSTRAINT fk_online_makale_tur
FOREIGN KEY (makale_turu) REFERENCES makale_tur(k_no)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- kurul_gorev için
ALTER TABLE kurul_gorev
ADD CONSTRAINT fk_kurul_gorev_kurul
FOREIGN KEY (kurul) REFERENCES kurul(k_no)
ON DELETE CASCADE;

ALTER TABLE kurul_gorev
ADD CONSTRAINT fk_kurul_gorev_yonetim
FOREIGN KEY (yonetim) REFERENCES yonetim(k_no)
ON DELETE CASCADE;

-- altmenu1 için
ALTER TABLE altmenu1
ADD CONSTRAINT fk_altmenu1_anamenu
FOREIGN KEY (ust_id) REFERENCES anamenu(k_no)
ON DELETE CASCADE;
```

#### Index Optimization
```sql
-- migrations/002_add_indexes.sql

-- Sık sorgulanan kolonlara index
CREATE INDEX idx_dergi_is_approved ON dergi_tanim(is_approved);
CREATE INDEX idx_dergi_tarih ON dergi_tanim(yayin_tarih);
CREATE INDEX idx_makale_dergi ON online_makale(dergi_tanim);
CREATE INDEX idx_makale_tur ON online_makale(makale_turu);
CREATE INDEX idx_yonetim_email ON yonetim(email);
CREATE INDEX idx_yonetim_grup ON yonetim(grup_id);

-- Composite indexes
CREATE INDEX idx_makale_dergi_tur ON online_makale(dergi_tanim, makale_turu);
```

---

### 2.2 Soft Deletes

#### Migration
```sql
-- migrations/003_add_soft_deletes.sql

ALTER TABLE dergi_tanim ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE online_makale ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE yonetim ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE gonderilen_makale ADD COLUMN deleted_at TIMESTAMP NULL;
```

#### Helper Functions
```php
// include/araclar/SoftDelete.php
class SoftDelete {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function delete($table, $id) {
        $sql = "UPDATE {$table} SET deleted_at = NOW() WHERE k_no = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function restore($table, $id) {
        $sql = "UPDATE {$table} SET deleted_at = NULL WHERE k_no = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getActive($table) {
        $sql = "SELECT * FROM {$table} WHERE deleted_at IS NULL";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }
}
```

---

## 🎯 Faz 3: MVC Architecture

### 3.1 Directory Structure

```
ebp/
├── app/
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── DergiController.php
│   │   ├── MakaleController.php
│   │   ├── UserController.php
│   │   └── AdminController.php
│   ├── Models/
│   │   ├── BaseModel.php
│   │   ├── Dergi.php
│   │   ├── Makale.php
│   │   ├── User.php
│   │   └── Kurul.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   └── navbar.php
│   │   ├── dergi/
│   │   │   ├── index.php
│   │   │   └── show.php
│   │   ├── makale/
│   │   │   ├── index.php
│   │   │   ├── show.php
│   │   │   └── create.php
│   │   └── admin/
│   │       └── dashboard.php
│   └── Helpers/
│       ├── CSRF.php
│       ├── FileUpload.php
│       └── Validator.php
├── config/
│   ├── database.php
│   └── app.php
├── public/
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── routes/
│   └── web.php
├── storage/
│   ├── logs/
│   └── uploads/
├── vendor/
├── .env
├── .env.example
├── composer.json
└── bootstrap.php
```

### 3.2 Base Model Implementation

```php
// app/Models/BaseModel.php
<?php
namespace App\Models;

abstract class BaseModel {
    protected $pdo;
    protected $table;
    protected $primaryKey = 'k_no';
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function all() {
        $sql = "SELECT * FROM {$this->table} WHERE deleted_at IS NULL ORDER BY {$this->primaryKey} DESC";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
    
    public function create($data) {
        $fields = array_keys($data);
        $values = array_values($data);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $sets = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $sets[] = "{$field} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
```

### 3.3 Dergi Model Example

```php
// app/Models/Dergi.php
<?php
namespace App\Models;

class Dergi extends BaseModel {
    protected $table = 'dergi_tanim';
    
    public function getCurrent() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 1 AND deleted_at IS NULL LIMIT 1";
        return $this->pdo->query($sql)->fetch(\PDO::FETCH_OBJ);
    }
    
    public function getArchive() {
        $sql = "SELECT * FROM {$this->table} WHERE is_approved = 0 AND deleted_at IS NULL ORDER BY yayin_tarih DESC";
        return $this->pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }
    
    public function withMakaleler($dergiId) {
        $sql = "SELECT d.*, COUNT(m.k_no) as makale_sayisi
                FROM dergi_tanim d
                LEFT JOIN online_makale m ON d.k_no = m.dergi_tanim
                WHERE d.k_no = ? AND d.deleted_at IS NULL
                GROUP BY d.k_no";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$dergiId]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
```

### 3.4 Controller Example

```php
// app/Controllers/DergiController.php
<?php
namespace App\Controllers;

use App\Models\Dergi;
use App\Models\Makale;

class DergiController extends BaseController {
    private $dergiModel;
    private $makaleModel;
    
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->dergiModel = new Dergi($pdo);
        $this->makaleModel = new Makale($pdo);
    }
    
    public function index() {
        $dergiler = $this->dergiModel->all();
        $guncelDergi = $this->dergiModel->getCurrent();
        
        return $this->view('dergi/index', [
            'dergiler' => $dergiler,
            'guncel' => $guncelDergi
        ]);
    }
    
    public function show($id) {
        $dergi = $this->dergiModel->find($id);
        
        if (!$dergi) {
            $this->redirect('/dergiler', 'Dergi bulunamadı');
        }
        
        $makaleler = $this->makaleModel->getByDergi($id);
        
        return $this->view('dergi/show', [
            'dergi' => $dergi,
            'makaleler' => $makaleler
        ]);
    }
}
```

### 3.5 Routing System

```php
// routes/web.php
<?php

$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/dergiler', 'DergiController@index');
$router->get('/dergi/{id}', 'DergiController@show');
$router->get('/makale/{id}', 'MakaleController@show');

// Auth routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/kayit', 'AuthController@showRegister');
$router->post('/kayit', 'AuthController@register');

// User routes (protected)
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/dashboard', 'UserController@dashboard');
    $router->get('/makale/gonder', 'MakaleController@create');
    $router->post('/makale/gonder', 'MakaleController@store');
});

// Admin routes (protected, admin only)
$router->group(['middleware' => 'admin'], function($router) {
    $router->get('/admin', 'AdminController@dashboard');
    $router->get('/admin/dergiler', 'AdminController@dergiler');
    $router->post('/admin/dergi/olustur', 'AdminController@createDergi');
});

return $router;
```

---

## 🎯 Testing Strategy

### Unit Tests

```php
// tests/Models/DergiTest.php
<?php
use PHPUnit\Framework\TestCase;
use App\Models\Dergi;

class DergiTest extends TestCase {
    private $pdo;
    private $dergi;
    
    protected function setUp(): void {
        // Test database connection
        $this->pdo = new PDO('mysql:host=localhost;dbname=ebp_test', 'root', '');
        $this->dergi = new Dergi($this->pdo);
    }
    
    public function testGetAllDergiler() {
        $dergiler = $this->dergi->all();
        $this->assertIsArray($dergiler);
    }
    
    public function testFindDergi() {
        $dergi = $this->dergi->find(1);
        $this->assertIsObject($dergi);
        $this->assertEquals(1, $dergi->k_no);
    }
    
    public function testCreateDergi() {
        $data = [
            'dergi_tanim' => 'Test Dergi',
            'ing_baslik' => 'Test Journal',
            'yayin_tarih' => '2026-01-20'
        ];
        
        $id = $this->dergi->create($data);
        $this->assertGreaterThan(0, $id);
    }
}
```

---

## 📦 Deployment Plan

### Production Checklist

- [ ] `.env` dosyası production değerleri
- [ ] `APP_DEBUG=false`
- [ ] HTTPS zorunlu
- [ ] Database backups otomatik
- [ ] Error logging aktif
- [ ] Security headers
- [ ] Rate limiting
- [ ] CDN kurulumu
- [ ] Monitoring (Sentry, New Relic)
- [ ] Load balancer (opsiyonel)

### Deployment Script

```bash
#!/bin/bash
# deploy.sh

echo "Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx

echo "Deployment completed!"
```

---

**Son Güncelleme**: 2026-01-20  
**Versiyon**: 1.0
