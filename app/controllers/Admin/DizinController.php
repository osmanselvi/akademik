<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Dizin;

class DizinController extends BaseController {
    protected $dizinModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->dizinModel = new Dizin($pdo);
    }

    /**
     * List all dizinler
     */
    public function index() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $filters = [
            'is_approved' => $_GET['is_approved'] ?? null
        ];

        $dizinler = $this->dizinModel->getFiltered($filters);

        $this->view('admin.dizin.index', [
            'dizinler' => $dizinler,
            'filters' => $filters,
            'title' => 'Dizin Yönetimi'
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $this->view('admin.dizin.create', [
            'title' => 'Yeni Dizin Ekle'
        ]);
    }

    /**
     * Store new dizin
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $logoPath = null;
        if (isset($_FILES['dizin_logo']) && $_FILES['dizin_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $ext = pathinfo($_FILES['dizin_logo']['name'], PATHINFO_EXTENSION);
            $logoPath = 'dizin_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['dizin_logo']['tmp_name'], $uploadDir . $logoPath);
        }

        $data = [
            'dizin_adi' => $_POST['dizin_adi'],
            'dizin_link' => $_POST['dizin_link'] ?? null,
            'dizin_logo' => $logoPath,
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => auth()->id,
            'created_role' => auth()->grup_id,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        if ($this->dizinModel->create($data)) {
            redirect('/admin/dizin', 'Dizin başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/dizin', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $dizin = $this->dizinModel->find($id);
        if (!$dizin) {
            redirect('/admin/dizin', 'Dizin bulunamadı.');
        }

        $this->view('admin.dizin.edit', [
            'dizin' => $dizin,
            'title' => 'Dizin Düzenle'
        ]);
    }

    /**
     * Update dizin
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'dizin_adi' => $_POST['dizin_adi'],
            'dizin_link' => $_POST['dizin_link'] ?? null,
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        // Handle logo upload
        if (isset($_FILES['dizin_logo']) && $_FILES['dizin_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Delete old logo if exists
            $oldDizin = $this->dizinModel->find($id);
            if ($oldDizin && isset($oldDizin->dizin_logo) && $oldDizin->dizin_logo) {
                $oldFile = $uploadDir . $oldDizin->dizin_logo;
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            
            $ext = pathinfo($_FILES['dizin_logo']['name'], PATHINFO_EXTENSION);
            $logoPath = 'dizin_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['dizin_logo']['tmp_name'], $uploadDir . $logoPath);
            $data['dizin_logo'] = $logoPath;
        }

        if ($this->dizinModel->update($id, $data)) {
            redirect('/admin/dizin', 'Dizin güncellendi.', 'success');
        } else {
            redirect('/admin/dizin', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete dizin
     */
    public function delete($id) {
        if (!isAdmin() && !isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->dizinModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
