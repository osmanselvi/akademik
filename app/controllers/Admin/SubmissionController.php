<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class SubmissionController extends BaseController {
    public function index() {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $sql = "SELECT gm.*, y.ad_soyad as researcher_name, y.email as researcher_email 
                FROM gonderilen_makale gm
                LEFT JOIN yonetim y ON gm.created_by = y.id
                ORDER BY gm.id DESC";
        $stmt = $this->pdo->query($sql);
        $items = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
        $this->view('admin.submissions.index', [
            'items' => $items,
            'title' => 'Hakem Süreci & Makale Gönderileri'
        ]);
    }

    /**
     * Show revision request form
     */
    public function revision($id) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $stmt = $this->pdo->prepare("SELECT gm.*, y.ad_soyad as researcher_name, y.email as researcher_email 
                                     FROM gonderilen_makale gm
                                     LEFT JOIN yonetim y ON gm.created_by = y.id
                                     WHERE gm.id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$item) \redirect('/admin/submissions', 'Gönderi bulunamadı.');

        $this->view('admin.submissions.revision', [
            'item' => $item,
            'title' => 'Düzeltme Talep Et'
        ]);
    }

    /**
     * Store revision request and notify researcher
     */
    public function sendRevisionRequest($id) {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $metin = $_POST['metin'];
        $researcherEmail = $_POST['email'] ?? '';

        if (empty($researcherEmail)) {
            $stmt = $this->pdo->prepare("SELECT y.email FROM gonderilen_makale gm JOIN yonetim y ON gm.created_by = y.id WHERE gm.id = ?");
            $stmt->execute([$id]);
            $researcherEmail = $stmt->fetchColumn();
        }

        // Update submission status
        $stmt = $this->pdo->prepare("UPDATE gonderilen_makale SET status = 1 WHERE id = ?");
        $stmt->execute([$id]);

        // Add revision note
        $noteSql = "INSERT INTO makale_revizyon_notlari (makale_id, sender_id, metin) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($noteSql);
        $stmt->execute([$id, $_SESSION['user_id'], $metin]);

        // Send Email
        $subject = "Makale Düzeltme Talebi - Edebiyat Bilimleri Dergisi";
        $message = "<p>Sayın Yazar,</p>
                    <p>Gönderdiğiniz makale incelenmiş olup, aşağıda belirtilen düzeltmelerin yapılması talep edilmektedir:</p>
                    <div style='background:#f8f9fa; padding:15px; border-left:4px solid #ffc107; margin:20px 0;'>
                        " . nl2br(\e($metin)) . "
                    </div>
                    <p>Düzeltilmiş makalenizi kullanıcı paneliniz üzerinden sisteme tekrar yükleyebilirsiniz.</p>";
        
        \App\Helpers\Mail::send($researcherEmail, $subject, $message);

        \redirect('/admin/submissions', 'Düzeltme talebi ve e-posta başarıyla gönderildi.', 'success');
    }

    /**
     * Approve submission for publication
     */
    public function approve($id) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $stmt = $this->pdo->prepare("UPDATE gonderilen_makale SET status = 3, is_approved = 1 WHERE id = ?");
        if ($stmt->execute([$id])) {
            \redirect('/admin/submissions', 'Makale onaylandı ve yayına hazır.', 'success');
        } else {
            \redirect('/admin/submissions', 'Onaylama işlemi başarısız.');
        }
    }

    public function delete($id) {
        if (!\isAdmin() && !\isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM gonderilen_makale WHERE id = ?");
        if ($stmt->execute([$id])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
