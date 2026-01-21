<?php
namespace App\Controllers;

use App\Helpers\Mail;

class DebugController extends BaseController {
    public function testMail() {
        if (!\isAdmin()) die('Unauthorized');

        $to = $_GET['to'] ?? $_ENV['MAIL_USERNAME'] ?? 'bilgi@edebiyatbilim.com';
        
        echo "<h1>Mail Debugging Tool</h1>";
        echo "<p>Environment: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "</p>";
        echo "<p>Mail Host: " . ($_ENV['MAIL_HOST'] ?? 'NOT SET') . "</p>";
        echo "<p>Mail User: " . ($_ENV['MAIL_USERNAME'] ?? 'NOT SET') . "</p>";
        echo "<p>Attempting to send to: $to ...</p>";

        // Ensure log directory exists
        $logDir = \storagePath('logs');
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $result = Mail::send($to, "Web Test Mail " . time(), "Built-in web mail test success.");

        if ($result) {
            echo "<h2 style='color:green'>SUCCESS!</h2>";
        } else {
            echo "<h2 style='color:red'>FAILED!</h2>";
            echo "<p>Check <b>storage/logs/mail_debug.log</b> for details.</p>";
        }

        echo "<p><a href='/admin/support'>Back to Admin</a></p>";
    }
}
