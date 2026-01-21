<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DergiKunyeBaslik;
use App\Models\DergiKunye;

class KunyeController extends BaseController {
    protected $kunyeBaslikModel;
    protected $kunyeModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->kunyeBaslikModel = new DergiKunyeBaslik($pdo);
        $this->kunyeModel = new DergiKunye($pdo);
    }

    /**
     * List all masthead entries
     */
    public function index() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $filters = [
            'baslik_id' => $_GET['baslik_id'] ?? '',
            'is_approved' => $_GET['is_approved'] ?? null
        ];

        $entries = $this->kunyeModel->getWithCategories($filters);
        $categories = $this->kunyeBaslikModel->getApproved();

        $this->view('admin.kunye.index', [
            'entries' => $entries,
            'categories' => $categories,
            'filters' => $filters,
            'title' => 'Dergi Künyesi Yönetimi'
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = $this->kunyeBaslikModel->getApproved();

        $this->view('admin.kunye.create', [
            'categories' => $categories,
            'title' => 'Yeni Künye Kaydı'
        ]);
    }

    /**
     * Store new entry
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'baslik_id' => $_POST['baslik_id'],
            'ad_soyad' => $_POST['ad_soyad'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => auth()->id,
            'created_role' => auth()->grup_id,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        if ($this->kunyeModel->create($data)) {
            redirect('/admin/kunye', 'Künye kaydı başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/kunye', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $entry = $this->kunyeModel->find($id);
        if (!$entry) {
            redirect('/admin/kunye', 'Kayıt bulunamadı.');
        }

        $categories = $this->kunyeBaslikModel->getApproved();

        $this->view('admin.kunye.edit', [
            'entry' => $entry,
            'categories' => $categories,
            'title' => 'Künye Kaydını Düzenle'
        ]);
    }

    /**
     * Update entry
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'baslik_id' => $_POST['baslik_id'],
            'ad_soyad' => $_POST['ad_soyad'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kunyeModel->update($id, $data)) {
            redirect('/admin/kunye', 'Künye kaydı güncellendi.', 'success');
        } else {
            redirect('/admin/kunye', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete entry
     */
    public function delete($id) {
        if (!isAdmin() && !isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->kunyeModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }

    // ========== CATEGORY MANAGEMENT ==========

    /**
     * Manage categories
     */
    public function manageCategories() {
        if (!isAdmin()) {
            redirect('/dashboard', 'Bu sayfaya sadece yöneticiler erişebilir.');
        }

        $categories = $this->kunyeBaslikModel->all();
        $this->view('admin.lookup.kunye_baslik', [
            'categories' => $categories,
            'title' => 'Künye Kategorileri'
        ]);
    }

    public function storeCategory() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'baslik' => $_POST['baslik'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kunyeBaslikModel->create($data)) {
            redirect('/admin/lookup/kunye-baslik', 'Kategori eklendi.', 'success');
        } else {
            redirect('/admin/lookup/kunye-baslik', 'Bir hata oluştu.');
        }
    }

    public function updateCategory($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'baslik' => $_POST['baslik'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kunyeBaslikModel->update($id, $data)) {
            redirect('/admin/lookup/kunye-baslik', 'Kategori güncellendi.', 'success');
        } else {
            redirect('/admin/lookup/kunye-baslik', 'Bir hata oluştu.');
        }
    }

    public function deleteCategory($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->kunyeBaslikModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
