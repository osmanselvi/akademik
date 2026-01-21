<?php
namespace App\Controllers;

use App\Models\DestekTalep;
use App\Helpers\CSRF;
use App\Helpers\Mail;

class SupportController extends BaseController {
    protected $supportModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->supportModel = new DestekTalep($pdo);
    }

    /**
     * My Support Requests
     */
    public function index() {
        if (!\isLoggedIn()) \redirect('/login');
        
        $userId = $_SESSION['user_id'];
        $requests = $this->supportModel->where('user_id', $userId);
        
        $this->view('user.support.index', [
            'requests' => $requests,
            'title' => 'Destek Taleplerim'
        ]);
    }

    /**
     * New Support Request Form
     */
    public function create() {
        if (!\isLoggedIn()) \redirect('/login');
        
        $this->view('user.support.create', [
            'title' => 'Yeni Destek Talebi'
        ]);
    }

    /**
     * Store New Support Request
     */
    public function store() {
        CSRF::verify();
        if (!\isLoggedIn()) \redirect('/login');

        $editorEmail = \config('app.editor_email', 'bilgi@edebiyatbilim.com');

        $data = [
            'user_id' => $_SESSION['user_id'],
            'submission_id' => $_POST['submission_id'] ?? null,
            'konu' => $_POST['konu'],
            'mesaj' => $_POST['mesaj'],
            'status' => 0
        ];

        if ($this->supportModel->create($data)) {
            // Notify Editor
            $editorEmail = \config('app.editor_email', 'bilgi@edebiyatbilim.com');
            $subject = "Yeni Destek Talebi - " . $data['konu'];
            $message = "<p>Yeni bir destek talebi oluşturuldu:</p>
                        <p><strong>Gönderen:</strong> {$_SESSION['user_name']}</p>
                        <p><strong>Konu:</strong> {$data['konu']}</p>
                        <p><strong>Mesaj:</strong> " . nl2br(\e($data['mesaj'])) . "</p>";
            
            Mail::send($editorEmail, $subject, $message);
            
            // Notify Researcher (Confirmation)
            $researcherEmail = $_SESSION['user_email'] ?? '';
            if (empty($researcherEmail) && isset($_SESSION['user_id'])) {
                $user = (new \App\Models\User($this->pdo))->find($_SESSION['user_id']);
                $researcherEmail = $user->email ?? '';
            }

            if ($researcherEmail) {
                $confSubject = "Destek Talebiniz Alındı - Edebiyat Bilimleri Dergisi";
                $confMessage = "<p>Sayın {$_SESSION['user_name']},</p>
                                <p>Destek talebiniz başarıyla editör ekibimize ulaşmıştır. Talebiniz en kısa sürede incelenerek yanıtlanacaktır.</p>
                                <hr>
                                <p><strong>Talep Konusu:</strong> {$data['konu']}</p>
                                <p><strong>Mesajınız:</strong> " . nl2br(\e($data['mesaj'])) . "</p>
                                <p>Durumu kullanıcı panelinizden takip edebilirsiniz.</p>";
                Mail::send($researcherEmail, $confSubject, $confMessage);
            }
            
            \redirect('/support', 'Destek talebiniz başarıyla iletildi ve tarafınıza bilgilendirme e-postası gönderildi.', 'success');
        } else {
            \redirect('/support/create', 'Bir hata oluştu.');
        }
    }
}
