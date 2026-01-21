<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DestekTalep;
use App\Helpers\Mail;

class SupportController extends BaseController {
    protected $supportModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->supportModel = new DestekTalep($pdo);
    }

    /**
     * List all support requests
     */
    public function index() {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $items = $this->supportModel->getAllWithUsers();
        
        $this->view('admin.support.index', [
            'items' => $items,
            'title' => 'Destek Talepleri Yönetimi'
        ]);
    }

    /**
     * Show reply form
     */
    public function reply($id) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $item = $this->supportModel->find($id);
        if (!$item) \redirect('/admin/support', 'Talep bulunamadı.');

        $this->view('admin.support.reply', [
            'item' => $item,
            'title' => 'Talebi Yanıtla'
        ]);
    }

    /**
     * Send reply and notify user
     */
    public function sendReply($id) {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $reply = $_POST['editor_notu'];
        $userEmail = $_POST['user_email'];

        $data = [
            'editor_notu' => $reply,
            'status' => 1 // Resolved/Replied
        ];

        if ($this->supportModel->update($id, $data)) {
            // Notify User
            $subject = "Destek Talebiniz Yanıtlandı - Edebiyat Bilimleri Dergisi";
            $message = "<p>Sayın Araştırmacı,</p>
                        <p>Daha önce iletmiş olduğunuz destek talebi editör ekibimiz tarafından yanıtlanmıştır:</p>
                        <div style='background:#f8f9fa; padding:15px; border-left:4px solid #0d6efd; margin:20px 0;'>
                            " . nl2br(\e($reply)) . "
                        </div>
                        <p>Detayları kullanıcı panelinizden görüntüleyebilirsiniz.</p>";
            
            Mail::send($userEmail, $subject, $message);
            
            \redirect('/admin/support', 'Talep yanıtlandı ve kullanıcıya e-posta gönderildi.', 'success');
        } else {
            \redirect('/admin/support/reply/' . $id, 'Bir hata oluştu.');
        }
    }

    /**
     * Delete request
     */
    public function delete($id) {
        if (!\isAdmin() && !\isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkisiz erişim.']);
            return;
        }

        if ($this->supportModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme başarısız.']);
        }
    }
}
