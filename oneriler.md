# EBP Dergi Sistemi - İyileştirme Önerileri

## 🔒 1. Güvenlik İyileştirmeleri

### 1.1 Kimlik Bilgileri Yönetimi
**Mevcut Durum**: Veritabanı kimlik bilgileri kaynak kodda hardcoded şekilde mevcut.

**Öneri**:
```php
// .env dosyası kullan
DB_HOST=localhost
DB_NAME=ebph_server
DB_USER=ebp_user
DB_PASS=secure_password_here
DB_CHARSET=utf8mb4
```

**Fayda**: Güvenlik açığı riski azalır, farklı ortamlarda kolay konfigürasyon.

### 1.2 Şifre Güvenliği
**Mevcut Durum**: Şifre hash'leme yöntemi belirsiz.

**Öneri**:
```php
// Şifre saklama
$hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

// Şifre doğrulama
if (password_verify($inputPassword, $hashedPassword)) {
    // Giriş başarılı
}
```

**Fayda**: Modern ve güvenli şifre saklama, brute force saldırılarına karşı koruma.

### 1.3 CSRF Koruması
**Öneri**:
```php
// Form token oluştur
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Form'da kullan
<input type="hidden" name="csrf_token" value="<?=$_SESSION['csrf_token']?>">

// Doğrulama
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token mismatch');
}
```

**Fayda**: Cross-site request forgery saldırılarından koruma.

### 1.4 Dosya Yükleme Güvenliği
**Öneri**:
```php
$allowedTypes = ['application/pdf'];
$maxSize = 10 * 1024 * 1024; // 10MB

// MIME type kontrolü
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['dosya']['tmp_name']);

if (!in_array($mimeType, $allowedTypes)) {
    die('Geçersiz dosya tipi');
}

// Dosya boyutu kontrolü
if ($_FILES['dosya']['size'] > $maxSize) {
    die('Dosya çok büyük');
}

// Güvenli dosya adı
$safeFilename = uniqid() . '_' . basename($_FILES['dosya']['name']);
```

**Fayda**: Zararlı dosya yüklemesi engellenme.

## ⚡ 2. Performans İyileştirmeleri

### 2.1 Database Query Optimizasyonu
**Mevcut Durum**: `dergi_goster.php` içinde her dergi için ayrı sorgu yapılıyor.

**Öneri**:
```php
// Yerine tek sorguda tüm dergileri çek
$listesorgu = $baglanti->prepare("
    SELECT dt.*, COUNT(om.k_no) as makale_sayisi
    FROM dergi_tanim dt
    LEFT JOIN online_makale om ON dt.k_no = om.dergi_tanim
    GROUP BY dt.k_no
    ORDER BY dt.k_no DESC
");
```

**Fayda**: Database yükü azalır, sayfa hızı artar.

### 2.2 Caching Mekanizması
**Öneri**:
```php
// Redis ile dergi listesini cache'le
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$cacheKey = 'dergi_listesi';
$cached = $redis->get($cacheKey);

if ($cached) {
    $listekayit = unserialize($cached);
} else {
    // Database'den çek
    $listekayit = $listesorgu->fetchAll(PDO::FETCH_OBJ);
    $redis->setex($cacheKey, 3600, serialize($listekayit)); // 1 saat
}
```

**Fayda**: Sık erişilen verilerde hız artışı.

### 2.3 Lazy Loading
**Öneri**:
```html
<img src="placeholder.jpg" 
     data-src="images/<?=fn_dergi_kapak($fg_dergi_id);?>" 
     class="lazy" 
     loading="lazy">
```

**Fayda**: İlk sayfa yükleme hızı artar.

## 📐 3. Kod Organizasyonu

### 3.1 MVC Mimarisi
**Mevcut Durum**: Business logic, presentation ve data access karışık.

**Öneri**:
```
app/
├── Controllers/
│   ├── DergiController.php
│   ├── MakaleController.php
│   └── UserController.php
├── Models/
│   ├── Dergi.php
│   ├── Makale.php
│   └── User.php
└── Views/
    ├── dergi/
    │   ├── index.php
    │   └── show.php
    └── layouts/
        ├── header.php
        └── footer.php
```

**Örnek Model**:
```php
class Dergi {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM dergi_tanim ORDER BY k_no DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM dergi_tanim WHERE k_no = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
```

**Fayda**: Kod organize, test edilebilir, yeniden kullanılabilir.

### 3.2 Composer ve Autoloading
**Öneri**:
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    },
    "require": {
        "php": "^7.4 || ^8.0",
        "vlucas/phpdotenv": "^5.0",
        "predis/predis": "^2.0"
    }
}
```

```php
// Kullanım
require 'vendor/autoload.php';
use App\Models\Dergi;
```

**Fayda**: Modern dependency management, autoloading.

### 3.3 Config Management
**Öneri**:
```php
// config/database.php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]
    ]
];

// config/app.php
return [
    'name' => 'EBP Dergi Sistemi',
    'url' => getenv('APP_URL'),
    'timezone' => 'Europe/Istanbul',
    'locale' => 'tr',
    'debug' => getenv('APP_DEBUG', false)
];
```

**Fayda**: Merkezi konfigürasyon yönetimi.

## 🎨 4. Kullanıcı Deneyimi (UX/UI)

### 4.1 Responsive Design
**Öneri**:
```css
/* Mobile-first approach */
.dergi-card {
    width: 100%;
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    .dergi-card {
        width: 48%;
    }
}

@media (min-width: 1024px) {
    .dergi-card {
        width: 31%;
    }
}
```

**Fayda**: Mobil cihazlarda daha iyi görünüm.

### 4.2 Form Validation
**Öneri**:
```javascript
// Client-side validation
document.getElementById('kayitForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        e.preventDefault();
        showError('Geçerli bir email adresi giriniz');
        return false;
    }
    
    if (email.endsWith('@yahoo.com')) {
        e.preventDefault();
        showError('Yahoo email adresleri kabul edilmemektedir');
        return false;
    }
});
```

**Fayda**: Anlık geri bildirim, server yükü azalır.

### 4.3 Loading States
**Öneri**:
```html
<button type="submit" id="submitBtn">
    <span class="button-text">Gönder</span>
    <span class="spinner" style="display:none;">⏳</span>
</button>

<script>
document.getElementById('form').addEventListener('submit', function() {
    document.querySelector('.button-text').style.display = 'none';
    document.querySelector('.spinner').style.display = 'inline-block';
    document.getElementById('submitBtn').disabled = true;
});
</script>
```

**Fayda**: Kullanıcı işlemin devam ettiğini anlar.

### 4.4 Arama Fonksiyonelliği
**Öneri**:
```php
// Ajax ile canlı arama
<input type="text" id="makaleAra" placeholder="Makale ara...">
<div id="sonuclar"></div>

<script>
$('#makaleAra').on('input', debounce(function() {
    const query = $(this).val();
    if (query.length >= 3) {
        $.ajax({
            url: 'ajax/makale_ara.php',
            data: { q: query },
            success: function(data) {
                $('#sonuclar').html(data);
            }
        });
    }
}, 300));
</script>
```

**Fayda**: Hızlı ve kullanışlı arama deneyimi.

## 📊 5. Veri Yönetimi

### 5.1 Foreign Key Constraints
**Öneri**:
```sql
ALTER TABLE online_makale
ADD CONSTRAINT fk_dergi
FOREIGN KEY (dergi_tanim) 
REFERENCES dergi_tanim(k_no)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE online_makale
ADD INDEX idx_dergi_tanim (dergi_tanim),
ADD INDEX idx_makale_turu (makale_turu),
ADD INDEX idx_tarih (created_at);
```

**Fayda**: Veri bütünlüğü, orphan record önleme.

### 5.2 Migration System
**Öneri**:
```php
// database/migrations/2026_01_20_create_dergi_table.php
class CreateDergiTable {
    public function up() {
        $sql = "CREATE TABLE IF NOT EXISTS dergi_tanim (
            k_no INT AUTO_INCREMENT PRIMARY KEY,
            dergi_tanim VARCHAR(255) NOT NULL,
            ing_baslik VARCHAR(255),
            dergi_kapak VARCHAR(255),
            jenerikdosyasi VARCHAR(255),
            yayin_tarih DATE,
            is_approved TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_is_approved (is_approved),
            INDEX idx_yayin_tarih (yayin_tarih)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $sql;
    }
    
    public function down() {
        return "DROP TABLE IF EXISTS dergi_tanim";
    }
}
```

**Fayda**: Veritabanı değişikliklerini version control altında tut.

### 5.3 Soft Deletes
**Öneri**:
```sql
ALTER TABLE dergi_tanim ADD COLUMN deleted_at TIMESTAMP NULL;

-- Silme işlemi
UPDATE dergi_tanim SET deleted_at = NOW() WHERE k_no = ?;

-- Listeleme (silinmemişleri getir)
SELECT * FROM dergi_tanim WHERE deleted_at IS NULL;
```

**Fayda**: Kazara silme durumlarında geri alma imkanı.

## 🔔 6. Bildirim ve İletişim

### 6.1 Email Template System
**Öneri**:
```php
// EmailService.php
class EmailService {
    public function sendActivationEmail($user) {
        $template = file_get_contents('templates/activation_email.html');
        $template = str_replace(
            ['{name}', '{activation_link}'],
            [$user->name, $this->generateActivationLink($user)],
            $template
        );
        
        return $this->send($user->email, 'Hesap Aktivasyonu', $template);
    }
}
```

**Fayda**: Profesyonel görünümlü, tutarlı email iletişimi.

### 6.2 Activity Log
**Öneri**:
```sql
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100),
    model VARCHAR(100),
    model_id INT,
    changes TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
);
```

```php
function logActivity($action, $model, $modelId, $changes = null) {
    global $baglanti;
    $sql = "INSERT INTO activity_logs 
            (user_id, action, model, model_id, changes, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $baglanti->prepare($sql);
    $stmt->execute([
        $_SESSION['user_id'] ?? null,
        $action,
        $model,
        $modelId,
        json_encode($changes),
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
}

// Kullanım
logActivity('CREATE', 'makale', $makaleId, ['baslik' => 'Yeni Makale']);
```

**Fayda**: Kullanıcı aktivitelerini izleme, güvenlik denetimi.

## 📱 7. API ve Entegrasyonlar

### 7.1 RESTful API
**Öneri**:
```php
// api/v1/dergi.php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch ($method) {
    case 'GET':
        // Liste veya detay
        if (isset($_GET['id'])) {
            $dergi = getDergiById($_GET['id']);
            echo json_encode($dergi);
        } else {
            $dergiler = getAllDergiler();
            echo json_encode($dergiler);
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = createDergi($data);
        http_response_code(201);
        echo json_encode($result);
        break;
}
```

**Fayda**: Mobil uygulama, üçüncü parti entegrasyonlar.

### 7.2 Google Scholar Metadata
**Öneri**:
```html
<meta name="citation_title" content="<?=$makale->baslik?>">
<meta name="citation_author" content="<?=$makale->yazar?>">
<meta name="citation_publication_date" content="<?=$makale->tarih?>">
<meta name="citation_journal_title" content="Edebiyat Bilimleri Dergisi">
<meta name="citation_pdf_url" content="<?=$makale->pdf_url?>">
```

**Fayda**: Akademik görünürlük artar.

## 🧪 8. Test ve Kalite

### 8.1 Unit Testing
**Öneri**:
```php
// tests/DergiTest.php
use PHPUnit\Framework\TestCase;

class DergiTest extends TestCase {
    public function testGetAllDergiler() {
        $dergiModel = new Dergi($this->db);
        $dergiler = $dergiModel->getAll();
        
        $this->assertIsArray($dergiler);
        $this->assertGreaterThan(0, count($dergiler));
    }
    
    public function testGetDergiById() {
        $dergiModel = new Dergi($this->db);
        $dergi = $dergiModel->getById(1);
        
        $this->assertIsObject($dergi);
        $this->assertEquals(1, $dergi->k_no);
    }
}
```

**Fayda**: Kod kalitesi artar, regresyon önlenir.

### 8.2 Error Tracking
**Öneri**:
```php
// Sentry entegrasyonu
\Sentry\init(['dsn' => getenv('SENTRY_DSN')]);

set_exception_handler(function ($exception) {
    \Sentry\captureException($exception);
    // Kullanıcıya dostane hata mesajı göster
    include 'views/errors/500.php';
});
```

**Fayda**: Production hatalarını anlık izleme.

## 📈 9. Analytics ve Raporlama

### 9.1 Dashboard
**Öneri**:
- Toplam makale sayısı
- Bu ay gönderilen makale sayısı
- Aktif kullanıcı sayısı
- En çok okunan makaleler
- Son aktiviteler

### 9.2 İstatistik Takibi
**Öneri**:
```sql
CREATE TABLE makale_istatistik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    makale_id INT,
    goruntuleme INT DEFAULT 0,
    indirme INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (makale_id) REFERENCES online_makale(k_no)
);
```

**Fayda**: Makale performansını ölçümleme.

## 🚀 10. Deployment ve DevOps

### 10.1 CI/CD Pipeline
**Öneri**:
```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Run tests
        run: vendor/bin/phpunit
      - name: Deploy to server
        run: |
          rsync -avz --exclude '.git' . user@server:/var/www/ebp
```

**Fayda**: Otomatik deployment, test kontrolü.

### 10.2 Backup Strategy
**Öneri**:
```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p ebph_server > /backups/db_$DATE.sql
tar -czf /backups/files_$DATE.tar.gz /var/www/ebp/wwwroot/makaleler
# Eski backupları temizle (30 günden eski)
find /backups -name "*.sql" -mtime +30 -delete
```

**Fayda**: Veri kaybı riskini azaltır.

---

## Özet Öncelik Sıralaması

1. **Kritik**: Güvenlik (şifre, CSRF, dosya upload)
2. **Yüksek**: Performans (query optimizasyonu, caching)
3. **Orta**: Kod organizasyonu (MVC, namespaces)
4. **Düşük**: İleri düzey özellikler (API, analytics)

**Son Güncelleme**: 2026-01-20
