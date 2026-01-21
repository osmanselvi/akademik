<?php
namespace App\Controllers;

use App\Models\MakaleHakem;
use App\Models\GonderilenMakale;
use App\Models\HakemDegerlendirme;
use App\Models\User;

class ReviewerController extends BaseController {
    /**
     * Reviewer Dashboard - List Assignments
     */
    public function index() {
        if (!\isLoggedIn()) \redirect('/login');
        
        // Ensure user is authorized (simple check for now, can be role based)
        // Ideally: if (!hasRole('reviewer')) redirect... 

        $reviewerId = $_SESSION['user_id'];
        $assignments = (new MakaleHakem($this->pdo))->getByReviewer($reviewerId);
        
        $this->view('reviewer.index', [
            'assignments' => $assignments,
            'title' => 'Hakem Paneli'
        ]);
    }

    /**
     * Show Evaluation Form
     */
    public function show($id) {
        if (!\isLoggedIn()) \redirect('/login');
        
        $assignment = (new MakaleHakem($this->pdo))->find($id);
        
        // Security Check
        if (!$assignment || $assignment->hakem_id != $_SESSION['user_id']) {
            \redirect('/reviewer', 'Göreviniz bulunamadı.');
        }

        $article = (new GonderilenMakale($this->pdo))->find($assignment->makale_id);
        
        // Get Criteria (Hardcoded for now as per DB inspection showing table but no logic to manage it yet)
        // We can fetch from DB later. For now let's mock or use basic criteria
        // DB Inspect showed 'hakem_kriter' table. Let's try to fetch if exists
        $criteria = [];
        try {
            $stmt = $this->pdo->query("SELECT * FROM hakem_kriter WHERE is_approved = 1");
            $criteria = $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            // Table might be empty or missing
        }

        // If no criteria in DB, use defaults
        if (empty($criteria)) {
             $defaultCriteria = [
                (object)['id' => 1, 'kriter' => 'Konunun Özgünlüğü'],
                (object)['id' => 2, 'kriter' => 'Yöntem ve Metodoloji'],
                (object)['id' => 3, 'kriter' => 'Literatür Taraması'],
                (object)['id' => 4, 'kriter' => 'Sonuç ve Tartışma'],
                (object)['id' => 5, 'kriter' => 'Dil ve Anlatım']
             ];
             $criteria = $defaultCriteria;
        }

        $this->view('reviewer.show', [
            'assignment' => $assignment,
            'article' => $article,
            'criteria' => $criteria,
            'title' => 'Makale Değerlendirme'
        ]);
    }

    /**
     * Store Evaluation
     */
    public function store($id) {
        \App\Helpers\CSRF::verify();
        if (!\isLoggedIn()) \redirect('/login');
        
        $assignment = (new MakaleHakem($this->pdo))->find($id);
        if (!$assignment || $assignment->hakem_id != $_SESSION['user_id']) {
            \redirect('/reviewer', 'Yetkisiz işlem.');
        }

        if ($assignment->status == 3) {
             \redirect('/reviewer', 'Bu değerlendirme zaten tamamlanmış.');
        }

        // File Upload (Optional Annotation File)
        $fileName = null;
        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
            $fileName = time() . '_review_' . $_FILES['dosya']['name'];
            move_uploaded_file($_FILES['dosya']['tmp_name'], \publicPath('uploads/' . $fileName));
        }

        // Prepare Data
        $data = [
            'karar' => $_POST['karar'],
            'notlar_yazar' => $_POST['notlar_yazar'] ?? '',
            'notlar_editor' => $_POST['notlar_editor'] ?? '',
            'dosya' => $fileName
        ];

        // Prepare Scores
        $scores = $_POST['puan'] ?? [];

        // Save
        try {
            $evalModel = new HakemDegerlendirme($this->pdo);
            $evalModel->saveEvaluation($id, $data, $scores);

            // Notify Editor
            $editorEmail = \config('app.editor_email', 'bilgi@edebiyatbilim.com');
            $subject = "Hakem Değerlendirmesi Tamamlandı";
            $message = "<p>Sayın Editör,</p>
                        <p><strong>{$_SESSION['user_name']}</strong> isimli hakem bir makale için değerlendirmesini tamamladı.</p>
                        <p>Yönetim panelinden sonucu inceleyebilirsiniz.</p>";
            \App\Helpers\Mail::send($editorEmail, $subject, $message);

            \redirect('/reviewer', 'Değerlendirme başarıyla kaydedildi. Teşekkür ederiz.', 'success');
        } catch (\Exception $e) {
            \redirect('/reviewer/show/' . $id, 'Kaydetme sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * Download Review File
     */
     public function download($id) {
         // Security check for file download to ensure only assigned reviewer or admin can download
     }
}
