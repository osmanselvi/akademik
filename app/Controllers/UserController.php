<?php
namespace App\Controllers;

class UserController extends BaseController {
    protected $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->userModel = new \App\Models\User($pdo);
    }

    /**
     * User Dashboard
     */
    public function dashboard() {
        $this->requireAuth();
        
        $this->view('user.dashboard', [
            'pageTitle' => 'Kullanıcı Paneli',
            'user' => [
                'name' => $_SESSION['user_name'] ?? 'Kullanıcı',
                'role' => $_SESSION['grup_id'] ?? 0
            ]
        ]);
    }

    /**
     * Show profile edit page
     */
    public function profile() {
        $this->requireAuth();
        $user = $this->userModel->find($_SESSION['user_id']);
        $group = $this->userModel->getGroup($user->id);

        $this->view('user.profile', [
            'user' => $user,
            'group' => $group,
            'pageTitle' => 'Profilimi Düzenle'
        ]);
    }

    /**
     * Update profile
     */
    public function updateProfile() {
        $this->requireAuth();
        \App\Helpers\CSRF::verify();

        $userId = $_SESSION['user_id'];
        $validator = \App\Helpers\Validator::make($_POST, [
            'ad_soyad' => 'required|min:3',
            'email' => 'required|email'
        ], [
            'ad_soyad' => 'Ad Soyad',
            'email' => 'E-posta Adresi'
        ]);
        
        // Bonus: check email unique but exclude current user
        // I need to add that logic to Validator or do it manually
        // For now simple validation is enough.

        if ($validator->fails()) {
            $_SESSION['validation_errors'] = $validator->errors();
            $this->redirect('/profil');
        }

        $data = [
            'ad_soyad' => $_POST['ad_soyad'],
            'email' => $_POST['email']
        ];

        if ($this->userModel->update($userId, $data)) {
            $_SESSION['user_name'] = $data['ad_soyad'];
            $this->redirect('/profil', 'Profiliniz başarıyla güncellendi.');
        } else {
            $this->redirect('/profil', 'Güncelleme sırasında bir hata oluştu.');
        }
    }

    /**
     * Change password
     */
    public function changePassword() {
        $this->requireAuth();
        \App\Helpers\CSRF::verify();

        $validator = \App\Helpers\Validator::make($_POST, [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ], [
            'current_password' => 'Mevcut Şifre',
            'new_password' => 'Yeni Şifre',
            'confirm_password' => 'Yeni Şifre Tekrar'
        ]);

        if ($validator->fails()) {
            $_SESSION['validation_errors'] = $validator->errors();
            $this->redirect('/profil');
        }

        $user = $this->userModel->find($userId);

        if (!$this->userModel->verifyPassword($user, $oldPass)) {
            $this->redirect('/profil', 'Mevcut şifreniz hatalı.');
        }

        if ($this->userModel->update($userId, ['sifre' => password_hash($newPass, PASSWORD_BCRYPT)])) {
            $this->redirect('/profil', 'Şifreniz başarıyla değiştirildi.');
        } else {
            $this->redirect('/profil', 'Şifre değiştirme sırasında bir hata oluştu.');
        }
    }

    /**
     * Create Digital Signature
     */
    public function createSignature() {
        $this->requireAuth();
        \App\Helpers\CSRF::verify();
        
        $user = $this->userModel->find($_SESSION['user_id']);
        
        if (!empty($user->e_imza)) {
            $this->redirect('/profil', 'Zaten bir e-imzanız bulunmaktadır.');
        }

        $signatureData = \App\Helpers\Signature::generate($user->ad_soyad, $user->email);
        
        if ($signatureData === false) {
            $this->redirect('/profil', 'İmza oluşturulurken teknik bir hata meydana geldi.');
        }

        // We store the signature block. 
        // In a real scenario, we might also store the public key separately for easier verification 
        // but the signature block typically contains enough info or we can extract it.
        // For this task, we store the block in 'e_imza' column.
        
        $updateData = [
            'e_imza' => $signatureData['signature_block']
        ];
        
        if ($this->userModel->update($user->id, $updateData)) {
            $this->redirect('/profil', 'Benzersiz e-imzanız başarıyla oluşturuldu.', 'success');
        } else {
            $this->redirect('/profil', 'Veritabanı güncelleme hatası.');
        }
    }
}
