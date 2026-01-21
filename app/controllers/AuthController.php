<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\Validator;

class AuthController extends BaseController {
    protected $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->userModel = new User($pdo);
    }

    /**
     * Show login form
     */
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            redirect('/admin');
        }
        
        $this->view('auth.login', ['title' => 'Giriş Yap']);
    }

    /**
     * Process login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        \App\Helpers\CSRF::verify();

        $validator = Validator::make($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email' => 'E-posta',
            'password' => 'Şifre'
        ]);

        if ($validator->fails()) {
            return $this->view('auth.login', [
                'errors' => $validator->errors(),
                'title' => 'Giriş Yap'
            ]);
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($user, $password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->ad_soyad;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['grup_id'] = $user->grup_id;
            
            redirect('/dashboard');
        } else {
            $this->view('auth.login', [
                'error' => 'Geçersiz e-posta veya şifre.',
                'title' => 'Giriş Yap'
            ]);
        }
    }

    /**
     * Show register form
     */
    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            redirect('/dashboard');
        }
        
        $this->view('auth.register', ['title' => 'Kayıt Ol']);
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword() {
        return $this->view('auth.forgot-password', [
            'pageTitle' => 'Şifremi Unuttum | ' . ($_ENV['APP_NAME'] ?? getenv('APP_NAME') ?: 'EBP Dergi')
        ]);
    }

    /**
     * Send password reset link
     */
    public function sendResetLink() {
        \App\Helpers\CSRF::verify();
        
        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            $this->redirect('/sifremi-unuttum', 'Lütfen e-posta adresinizi girin.');
        }

        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            $this->redirect('/sifremi-unuttum', 'Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.');
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        
        // Save to password_resets
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->execute([$email, $token]);

        // Send Email
        $appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: 'http://localhost:8080';
        $resetUrl = $appUrl . "/sifre-sifirla/" . $token;
        $message = "
            <p>Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın:</p>
            <p><a href='{$resetUrl}' class='btn'>Şifremi Sıfırla</a></p>
            <p>Eğer bu talebi siz yapmadıysanız bu e-postayı dikkate almayın.</p>
        ";

        if (\App\Helpers\Mail::send($email, "Şifre Sıfırlama Talebi", $message)) {
            $this->redirect('/login', 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.');
        } else {
            $this->redirect('/sifremi-unuttum', 'E-posta gönderimi sırasında bir hata oluştu.');
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPassword($token) {
        return $this->view('auth.reset-password', [
            'token' => $token,
            'pageTitle' => 'Şifre Sıfırla | ' . ($_ENV['APP_NAME'] ?? getenv('APP_NAME') ?: 'EBP Dergi')
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword() {
        \App\Helpers\CSRF::verify();

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (strlen($password) < 6) {
            $this->redirect("/sifre-sifirla/{$token}", 'Şifre en az 6 karakter olmalıdır.');
        }

        if ($password !== $confirmPassword) {
            $this->redirect("/sifre-sifirla/{$token}", 'Şifreler eşleşmiyor.');
        }

        // Check token
        $stmt = $this->pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$token]);
        $reset = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$reset) {
            $this->redirect('/sifremi-unuttum', 'Geçersiz veya süresi dolmuş sıfırlama bağlantısı.');
        }

        // Update user password
        $user = $this->userModel->findByEmail($reset->email);
        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $this->userModel->update($user->id, ['sifre' => $hashedPassword]);
            
            // Delete token
            $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$reset->email]);

            $this->redirect('/login', 'Şifreniz başarıyla güncellendi. Yeni şifrenizle giriş yapabilirsiniz.');
        } else {
            $this->redirect('/sifremi-unuttum', 'Kullanıcı bulunamadı.');
        }
    }

    /**
     * Process registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/kayit');
        }

        \App\Helpers\CSRF::verify();

        // Basic IP-based rate limiting (Session based)
        $now = time();
        if (isset($_SESSION['last_registration_attempt']) && ($now - $_SESSION['last_registration_attempt'] < 60)) {
            $_SESSION['flash_message'] = "Çok fazla kayıt denemesi yaptınız. Lütfen 1 dakika bekleyin.";
            redirect('/kayit');
        }
        $_SESSION['last_registration_attempt'] = $now;

        $validator = Validator::make($_POST, [
            'ad_soyad' => 'required|min:3',
            'email' => 'required|email|unique:yonetim,email',
            'password' => 'required|min:6',
            'password_confirm' => 'required|same:password'
        ], [
            'ad_soyad' => 'Ad Soyad',
            'email' => 'E-posta',
            'password' => 'Şifre',
            'password_confirm' => 'Şifre Tekrar'
        ]);

        if ($validator->fails()) {
            $_SESSION['validation_errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            redirect('/kayit');
        }

        $email = $_POST['email'];

        // Yahoo.com check
        if (str_contains(strtolower($email), '@yahoo.')) {
            $_SESSION['flash_message'] = "Yahoo e-posta adresleri kabul edilmemektedir.";
            redirect('/kayit');
        }

        $userId = $this->userModel->createUser([
            'ad_soyad' => $_POST['ad_soyad'],
            'email' => $email,
            'password' => $_POST['password'],
            'grup_id' => 5 // Default user role
        ]);

        if ($userId) {
            $_SESSION['flash_message'] = "Kaydınız başarıyla tamamlandı. Giriş yapabilirsiniz.";
            redirect('/login');
        } else {
            $_SESSION['flash_message'] = "Kayıt sırasında bir hata oluştu.";
            redirect('/kayit');
        }
    }

    /**
     * Handle logout
     */
    public function logout() {
        session_destroy();
        redirect('/');
    }
}
