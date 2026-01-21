<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DergiTanim;
use App\Models\OnlineMakale;
use App\Models\MakaleTur;

class OnlineMakaleController extends BaseController {
    protected $makaleModel;
    protected $dergiModel;
    protected $turModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->makaleModel = new OnlineMakale($pdo);
        $this->dergiModel = new DergiTanim($pdo);
        $this->turModel = new MakaleTur($pdo);
    }

    /**
     * List all online articles
     */
    public function index() {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');
        
        $dergiId = $_GET['dergi'] ?? null;
        
        $sql = "SELECT om.*, dt.dergi_tanim as dergi_adi, mt.makale_turu as tur_adi 
                FROM online_makale om
                LEFT JOIN dergi_tanim dt ON om.dergi_tanim = dt.id
                LEFT JOIN makale_tur mt ON om.makale_turu = mt.id";
        
        $params = [];
        if ($dergiId) {
            $sql .= " WHERE om.dergi_tanim = ?";
            $params[] = $dergiId;
        }
        
        $sql .= " ORDER BY om.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll(\PDO::FETCH_OBJ);
        
        $dergiler = $this->dergiModel->all();

        $this->view('admin.online_makale.index', [
            'items' => $items,
            'dergiler' => $dergiler,
            'selectedDergi' => $dergiId,
            'title' => 'Yayımlanmış Makale Yönetimi'
        ]);
    }

    /**
     * Show create form (direct manual creation)
     */
    public function create() {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $dergiler = $this->dergiModel->all();
        $turler = $this->turModel->all();
        $selectedDergi = $_GET['dergi'] ?? null;

        $this->view('admin.online_makale.create', [
            'dergiler' => $dergiler,
            'turler' => $turler,
            'selectedDergi' => $selectedDergi,
            'title' => 'Yeni Makale Kaydet'
        ]);
    }

    /**
     * Show publication form (convert submission to online article)
     */
    public function publish($submissionId) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        // Fetch submission data
        $stmt = $this->pdo->prepare("SELECT * FROM gonderilen_makale WHERE id = ?");
        $stmt->execute([$submissionId]);
        $submission = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$submission) \redirect('/admin/online-makale', 'Gönderilen makale bulunamadı.');

        $dergiler = $this->dergiModel->all();
        $turler = $this->turModel->all();

        $this->view('admin.online_makale.publish', [
            'submission' => $submission,
            'dergiler' => $dergiler,
            'turler' => $turler,
            'title' => 'Makale Yayınla'
        ]);
    }

    /**
     * Store published article
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $data = [
            'makale_turu' => $_POST['makale_turu'],
            'dergi_tanim' => $_POST['dergi_tanim'],
            'makale_baslik' => $_POST['makale_baslik'],
            'makale_yazar' => $_POST['makale_yazar'],
            'mutercim' => $_POST['mutercim'] ?? '0',
            'makale_ozet' => $_POST['makale_ozet'],
            'kaynakca' => $_POST['kaynakca'],
            'kaynak_dil' => $_POST['kaynak_dil'] ?? '1',
            'anahtar_kelime' => $_POST['anahtar_kelime'],
            'yayin_created_at' => $_POST['yayin_created_at'] ?: date('Y-m-d'),
            'kisaad' => $_POST['kisaad'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id'] ?? 0,
            'created_role' => $_SESSION['grup_id'] ?? 0,
            'ip_address' => \getIp()
        ];

        // Handle File Mapping/Upload
        if (isset($_POST['submission_id']) && !empty($_POST['submission_id'])) {
            // Use existing file from submission
            $data['dosya'] = $_POST['existing_file'];
        }

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
            $fileName = time() . '_art_' . $_FILES['dosya']['name'];
            move_uploaded_file($_FILES['dosya']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['dosya'] = $fileName;
        }

        if ($this->makaleModel->create($data)) {
            // If it was a submission, we might want to update its status
            if (isset($_POST['submission_id'])) {
                $this->pdo->prepare("UPDATE gonderilen_makale SET is_approved = 1 WHERE id = ?")
                          ->execute([$_POST['submission_id']]);
            }
            \redirect('/admin/online-makale', 'Makale başarıyla yayımlandı.', 'success');
        } else {
            \redirect('/admin/online-makale', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $item = $this->makaleModel->find($id);
        if (!$item) \redirect('/admin/online-makale', 'Makale bulunamadı.');

        $dergiler = $this->dergiModel->all();
        $turler = $this->turModel->all();

        $this->view('admin.online_makale.edit', [
            'item' => $item,
            'dergiler' => $dergiler,
            'turler' => $turler,
            'title' => 'Yayımlanmış Makaleyi Düzenle'
        ]);
    }

    /**
     * Update article
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        $data = [
            'makale_turu' => $_POST['makale_turu'],
            'dergi_tanim' => $_POST['dergi_tanim'],
            'makale_baslik' => $_POST['makale_baslik'],
            'makale_yazar' => $_POST['makale_yazar'],
            'mutercim' => $_POST['mutercim'] ?? '0',
            'makale_ozet' => $_POST['makale_ozet'],
            'kaynakca' => $_POST['kaynakca'],
            'kaynak_dil' => $_POST['kaynak_dil'] ?? '1',
            'anahtar_kelime' => $_POST['anahtar_kelime'],
            'yayin_created_at' => $_POST['yayin_created_at'],
            'kisaad' => $_POST['kisaad'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if (isset($_FILES['dosya']) && $_FILES['dosya']['error'] === 0) {
            $fileName = time() . '_art_' . $_FILES['dosya']['name'];
            move_uploaded_file($_FILES['dosya']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['dosya'] = $fileName;
        }

        if ($this->makaleModel->update($id, $data)) {
            \redirect('/admin/online-makale', 'Makale güncellendi.', 'success');
        } else {
            \redirect('/admin/online-makale', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete article
     */
    public function delete($id) {
        if (!\isAdmin() && !\isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->makaleModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
