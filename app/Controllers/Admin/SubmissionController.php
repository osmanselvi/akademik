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

    /**
     * Show reviewer assignment form
     */
    public function assign($id) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        // Get Submission
        $stmt = $this->pdo->prepare("SELECT * FROM gonderilen_makale WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch(\PDO::FETCH_OBJ);
        
        if (!$item) \redirect('/admin/submissions', 'Gönderi bulunamadı.');

        // Get Available Reviewers
        $reviewers = (new \App\Models\User($this->pdo))->getReviewers();

        // Get Current Assignments
        $assignments = (new \App\Models\MakaleHakem($this->pdo))->getByArticle($id);

        $this->view('admin.submissions.assign', [
            'item' => $item,
            'reviewers' => $reviewers,
            'assignments' => $assignments,
            'title' => 'Hakem Atama'
        ]);
    }

    /**
     * Store reviewer assignment
     */
    public function storeAssignment($id) {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $reviewerId = $_POST['hakem_id'];
        $deadline = $_POST['deadline'];
        
        $hakemModel = new \App\Models\MakaleHakem($this->pdo);

        if ($hakemModel->isAssigned($id, $reviewerId)) {
            \redirect('/admin/submissions/assign/' . $id, 'Bu hakem zaten atanmış.');
        }

        $data = [
            'makale_id' => $id,
            'hakem_id' => $reviewerId,
            'deadline' => $deadline,
            'status' => 0 // Pending
        ];

        if ($hakemModel->create($data)) {
            // Send Notification Email (Using Template)
            $userModel = new \App\Models\User($this->pdo);
            $reviewer = $userModel->find($reviewerId);
            
            // Fetch article for title in email
            $stmt = $this->pdo->prepare("SELECT makale_adi FROM gonderilen_makale WHERE id = ?");
            $stmt->execute([$id]);
            $makaleAdi = $stmt->fetchColumn();

            if ($reviewer && $reviewer->email) {
                \App\Helpers\Mail::sendTemplate($reviewer->email, 'reviewer_assignment', [
                    'ad_soyad' => $reviewer->ad_soyad,
                    'makale_baslik' => $makaleAdi,
                    'son_tarih' => \formatDate($deadline),
                    'link' => $_ENV['APP_URL'] . '/reviewer',
                    'dergi_adi' => $_ENV['APP_NAME'] ?? 'Edebiyat Bilimleri Dergisi'
                ]);
            }

            \redirect('/admin/submissions/assign/' . $id, 'Hakem başarıyla atandı ve bilgilendirildi.', 'success');
        } else {
            \redirect('/admin/submissions/assign/' . $id, 'Atama sırasında bir hata oluştu.');
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

    /**
     * Handle bulk actions from index page
     */
    public function bulkAction() {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($action) || empty($ids) || !is_array($ids)) {
            \redirect('/admin/submissions', 'Lütfen işlem ve en az bir kayıt seçiniz.', 'warning');
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        switch ($action) {
            case 'delete':
                $sql = "DELETE FROM gonderilen_makale WHERE id IN ($placeholders)";
                $msg = "Seçilen kayıtlar silindi.";
                break;
            case 'approve':
                $sql = "UPDATE gonderilen_makale SET status = 3, is_approved = 1 WHERE id IN ($placeholders)";
                $msg = "Seçilen makaleler onaylandı.";
                break;
            case 'pending':
                $sql = "UPDATE gonderilen_makale SET status = 0, is_approved = 0 WHERE id IN ($placeholders)";
                $msg = "Seçilen makaleler beklemeye alındı.";
                break;
            default:
                \redirect('/admin/submissions', 'Geçersiz işlem.', 'danger');
                return;
        }

        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute($ids)) {
            \redirect('/admin/submissions', $msg, 'success');
        } else {
            \redirect('/admin/submissions', 'İşlem sırasında bir hata oluştu.', 'danger');
        }
    }
}
