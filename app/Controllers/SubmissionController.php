<?php
namespace App\Controllers;

use App\Models\GonderilenMakale;
use App\Models\MakaleTur;
use App\Helpers\Mail;
use App\Helpers\CSRF;

class SubmissionController extends BaseController {
    protected $submissionModel;
    protected $turModel;
    protected $sozlesmeModel;
    protected $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->submissionModel = new GonderilenMakale($pdo);
        $this->turModel = new MakaleTur($pdo);
        $this->sozlesmeModel = new \App\Models\MakaleSozlesme($pdo);
        $this->userModel = new \App\Models\User($pdo);
    }

    /**
     * My Submissions List
     */
    public function index() {
        if (!\isLoggedIn()) \redirect('/login');
        
        $userId = $_SESSION['user_id'];
        $submissions = $this->submissionModel->where('created_by', $userId);
        
        $this->view('user.submissions.index', [
            'submissions' => $submissions,
            'title' => 'Makale Gönderilerim'
        ]);
    }

    /**
     * New Submission Form
     */
    public function create() {
        if (!\isLoggedIn()) \redirect('/login');
        
        $turler = $this->turModel->all();
        $user = $this->userModel->find($_SESSION['user_id']);
        $sozlesmeMaddeleri = $this->sozlesmeModel->getGroupedApproved();
        
        $this->view('user.submissions.create', [
            'turler' => $turler,
            'user' => $user, // Pass user for signature check
            'sozlesmeMaddeleri' => $sozlesmeMaddeleri,
            'title' => 'Yeni Makale Gönder'
        ]);
    }

    /**
     * Store New Submission
     */
    public function store() {
        CSRF::verify();
        if (!\isLoggedIn()) \redirect('/login');

        // Check E-Signature
        $user = $this->userModel->find($_SESSION['user_id']);
        if (empty($user->e_imza)) {
            \redirect('/profil', 'Makale göndermek için öncelikle profilinizden benzersiz e-imza oluşturmalısınız.');
        }

        if (!isset($_POST['sozlesme_onay'])) {
            \redirect('/submissions/create', 'Lütfen yayın hakları devir sözleşmesini e-imzanız ile onaylayın.');
        }

        $data = [
            'makale_adi' => $_POST['makale_adi'],
            'makale_tur' => $_POST['makale_tur'],
            'yazar_adi' => $_POST['yazar_adi'],
            'ceviren_adi' => $_POST['ceviren_adi'] ?? '',
            'yazar_sayisi' => $_POST['yazar_sayisi'] ?? 1,
            'makale_konu' => $_POST['makale_konu'],
            'anahtar_kelimeler' => $_POST['anahtar_kelimeler'],
            'is_approved' => 0,
            'status' => 0,
            'created_at' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id'],
            'created_role' => $_SESSION['grup_id'],
            'ip_address' => \getIp()
        ];

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
            $fileName = time() . '_sub_' . $_FILES['dosya']['name'];
            if (move_uploaded_file($_FILES['dosya']['tmp_name'], \publicPath('uploads/' . $fileName))) {
                $data['dosya'] = $fileName;
            } else {
                \redirect('/submissions/create', 'Dosya sunucuya kaydedilemedi. Lütfen sistem yöneticisi ile iletişime geçin.');
            }
        } elseif (isset($_FILES['dosya']) && $_FILES['dosya']['error'] !== 0) {
            $errorMsg = 'Dosya yükleme hatası. ';
            if ($_FILES['dosya']['error'] === 1 || $_FILES['dosya']['error'] === 2) $errorMsg .= 'Dosya boyutu çok büyük.';
            \redirect('/submissions/create', $errorMsg);
        }

        if (empty($data['dosya'])) {
            \redirect('/submissions/create', 'Lütfen geçerli bir makale dosyası seçin.');
        }

        if ($id = $this->submissionModel->create($data)) {
            
            // Log the signature action
            $signatureSql = "INSERT INTO imza_loglari (user_id, makale_id, sozlesme_metni, imza_kodu, ip_address) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($signatureSql);
            // We save a snaphot of current terms just in case, but for now just a placeholder text is enough or complex logic
            // To keep it simple, we will save "Sözleşme Onaylandı" + Date as text, or we could fetch all terms strings.
            // Let's verify what we have. Ideally we dump the JSON of terms.
            
            $terms = $this->sozlesmeModel->getGroupedApproved();
            $termsJson = json_encode($terms, JSON_UNESCAPED_UNICODE);
            
            $stmt->execute([
                $_SESSION['user_id'],
                $id,
                $termsJson,
                $user->e_imza,
                \getIp()
            ]);

            // Notify Researcher
            $userEmail = $_SESSION['user_email'] ?? '';
            
            // Fallback: Fetch email from DB if not in session
            if (empty($userEmail)) {
                $user = (new \App\Models\User($this->pdo))->find($_SESSION['user_id']);
                $userEmail = $user->email ?? '';
            }

            if ($userEmail) {
                $subject = "Makale Gönderiniz Alındı - Edebiyat Bilimleri Dergisi";
                $message = "<p>Sayın {$_SESSION['user_name']},</p>
                            <p><strong>\"{$data['makale_adi']}\"</strong> başlıklı makaleniz Edebiyat Bilimleri Dergisi tarafından incelenmek üzere kabul edilmiştir.</p>
                            <p>Telif devir sözleşmesi e-imzanız ile başarıyla onaylanmıştır.</p>
                            <p>Süreci kullanıcı panelinizden takip edebilirsiniz.</p>";
                Mail::send($userEmail, $subject, $message);
            }

            // Notify Editor
            $editorEmail = \config('app.editor_email', 'bilgi@edebiyatbilim.com');
            $edSubject = "Yeni Makale Gönderisi: " . $data['makale_adi'];
            $edMessage = "<p>Sisteme yeni bir makale yüklendi:</p>
                          <p><strong>Makale:</strong> {$data['makale_adi']}</p>
                          <p><strong>Yazar:</strong> {$data['yazar_adi']}</p>
                          <p><strong>E-İmza:</strong> Onaylı</p>
                          <p>Yönetim panelinden detayları inceleyebilirsiniz.</p>";
            Mail::send($editorEmail, $edSubject, $edMessage);
            
            redirect('/submissions', 'Makaleniz başarıyla gönderildi, sözleşme e-imzanız ile onaylandı ve editör ekibine bilgi verildi.', 'success');
        } else {
            redirect('/submissions/create', 'Bir hata oluştu.');
        }
    }

    /**
     * Submission Detail / Revisions
     */
    public function show($id) {
        if (!\isLoggedIn()) \redirect('/login');
        
        $submission = $this->submissionModel->find($id);
        if (!$submission || $submission->created_by != $_SESSION['user_id']) {
            redirect('/submissions', 'Gönderi bulunamadı.');
        }

        $revisions = $this->submissionModel->getRevisions($id);
        
        // Fetch linked support requests
        $supportModel = new \App\Models\DestekTalep($this->pdo);
        $supportRequests = $supportModel->where('submission_id', $id);
        
        // Check if copyright agreement is signed
        $signCheckSql = "SELECT id, created_at FROM imza_loglari WHERE makale_id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($signCheckSql);
        $stmt->execute([$id, $_SESSION['user_id']]);
        $signatureRecord = $stmt->fetch(\PDO::FETCH_OBJ);
        
        $sozlesmeMaddeleri = $this->sozlesmeModel->getGroupedApproved();

        $this->view('user.submissions.show', [
            'item' => $submission,
            'revisions' => $revisions,
            'supportRequests' => $supportRequests,
            'signatureRecord' => $signatureRecord,
            'sozlesmeMaddeleri' => $sozlesmeMaddeleri,
            'title' => 'Gönderi Detayı'
        ]);
    }

    /**
     * Resubmit Revised Version
     */
    public function resubmit($id) {
        CSRF::verify();
        if (!\isLoggedIn()) \redirect('/login');

        $submission = $this->submissionModel->find($id);
        if (!$submission || $submission->created_by != $_SESSION['user_id']) {
            redirect('/submissions', 'Gönderi bulunamadı.');
        }

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
            $fileName = time() . '_rev_' . $_FILES['dosya']['name'];
            if (move_uploaded_file($_FILES['dosya']['tmp_name'], \publicPath('uploads/' . $fileName))) {
                $data = [
                    'dosya' => $fileName,
                    'status' => 2 // Revised
                ];
                $this->submissionModel->update($id, $data);
                
                // Add revision note
                $noteSql = "INSERT INTO makale_revizyon_notlari (makale_id, sender_id, metin) VALUES (?, ?, ?)";
                $stmt = $this->pdo->prepare($noteSql);
                $stmt->execute([$id, $_SESSION['user_id'], "Araştırmacı tarafından düzeltilmiş metin yüklendi."]);

                \redirect('/submissions/' . $id, 'Düzeltilmiş makale başarıyla yüklendi.', 'success');
            }
        }
        
        \redirect('/submissions/' . $id, 'Dosya yükleme hatası.');
    }

    /**
     * Post-Submission Signing
     */
    public function signAgreement($id) {
        CSRF::verify();
        if (!\isLoggedIn()) \redirect('/login');

        $submission = $this->submissionModel->find($id);
        if (!$submission || $submission->created_by != $_SESSION['user_id']) {
            \redirect('/submissions', 'Gönderi bulunamadı.');
        }

        // Check E-Signature
        $user = $this->userModel->find($_SESSION['user_id']);
        if (empty($user->e_imza)) {
            \redirect('/submissions/' . $id, 'İmzalama işlemi için profilinizden e-imza oluşturmalısınız.');
        }

        // Check if already signed
        $checkSql = "SELECT id FROM imza_loglari WHERE makale_id = ? AND user_id = ?";
        $stmt = $this->pdo->prepare($checkSql);
        $stmt->execute([$id, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            \redirect('/submissions/' . $id, 'Bu sözleşme zaten imzalanmış.');
        }

        // Create log
        $terms = $this->sozlesmeModel->getGroupedApproved();
        $termsJson = json_encode($terms, JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO imza_loglari (user_id, makale_id, sozlesme_metni, imza_kodu, ip_address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute([
            $_SESSION['user_id'],
            $id,
            $termsJson,
            $user->e_imza,
            \getIp()
        ])) {
            \redirect('/submissions/' . $id, 'Telif devir sözleşmesi e-imzanız ile başarıyla imzalanmıştır.', 'success');
        } else {
            \redirect('/submissions/' . $id, 'İmzalama sırasında bir hata oluştu.');
        }
    }
}
