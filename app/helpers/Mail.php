<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    /**
     * Send email using configuration from .env via PHPMailer (SMTP)
     */
    public static function send($to, $subject, $message, $from = null) {
        $mail = new PHPMailer(true);
        
        // Log start of send
        file_put_contents(\storagePath('logs/mail_debug.log'), date('Y-m-d H:i:s')." [INFO] Attempting send to: $to\n", FILE_APPEND);

        try {
            // Server settings
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
            $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'] ?? getenv('MAIL_ENCRYPTION') ?: 'tls';
            $mail->Port       = $_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?: 587;

            // SMTP Options for SSL
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Recipients
            $from = $from ?? $_ENV['MAIL_FROM_ADDRESS'] ?? getenv('MAIL_FROM_ADDRESS') ?: 'noreply@edebiyatbilim.com';
            $fromName = $_ENV['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?: ($_ENV['APP_NAME'] ?? getenv('APP_NAME') ?: 'EBP Dergi');
            
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "
            <html>
            <head>
                <style>
                    body { font-family: sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
                    .header { border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-bottom: 20px; }
                    .footer { margin-top: 30px; font-size: 0.8rem; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
                    .btn { display: inline-block; padding: 10px 20px; background: #667eea; color: #fff; text-decoration: none; border-radius: 5px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>{$fromName}</h2>
                    </div>
                    {$message}
                    <div class='footer'>
                        Bu e-posta {$fromName} sistemi tarafından otomatik olarak gönderilmiştir.
                    </div>
                </div>
            </body>
            </html>
            ";

            // SMTP DebugGING
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->Debugoutput = function($str, $level) {
                file_put_contents(\storagePath('logs/mail_debug.log'), date('Y-m-d H:i:s')." [Level $level] $str\n", FILE_APPEND);
            };

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            file_put_contents(\storagePath('logs/mail_error.log'), date('Y-m-d H:i:s')." Mail Error: " . $mail->ErrorInfo . "\n", FILE_APPEND);
            return false;
        }
    }
}
