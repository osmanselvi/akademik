<?php
/**
 * Email Test Diagnostic Script
 */

require_once __DIR__ . '/bootstrap.php';

use App\Helpers\Mail;

$testEmail = 'osselman@gmail.com'; // Testing to user's likely email or a known good one
// Actually, let's use the one from .env as a test or prompt for one.
// Let's just try to send it to the MAIL_USERNAME itself as a loopback test.
$to = $_ENV['MAIL_USERNAME'] ?? 'test@example.com';

echo "Testing email sending to: $to\n";
echo "Host: " . ($_ENV['MAIL_HOST'] ?? 'NOT SET') . "\n";
echo "User: " . ($_ENV['MAIL_USERNAME'] ?? 'NOT SET') . "\n";
echo "Port: " . ($_ENV['MAIL_PORT'] ?? 'NOT SET') . "\n";

$result = Mail::send($to, "EBP Sistem Test Mail", "Bu bir test e-postasıdır. Eğer ulaştıysa sistem çalışıyor demektir.");

if ($result) {
    echo "BAŞARILI! E-posta başarıyla gönderildi.\n";
} else {
    echo "HATA! E-posta gönderilemedi.\n";
    echo "Lütfen PHP error_log dosyasını kontrol edin veya Mail.php dosyasındaki error_log çıktısını manuel inceleyin.\n";
}
