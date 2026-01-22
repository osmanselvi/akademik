<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\YayinKurul;
use App\Models\Unvan;
use App\Models\Kurul;
use App\Models\KurulGorev;
use App\Helpers\Validator;

class KurulController extends BaseController {
    protected $yayinKurulModel;
    protected $unvanModel;
    protected $kurulModel;
    protected $kurulGorevModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        // Only admins and editors can access these routes
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $this->yayinKurulModel = new YayinKurul($pdo);
        $this->unvanModel = new Unvan($pdo);
        $this->kurulModel = new Kurul($pdo);
        $this->kurulGorevModel = new KurulGorev($pdo);
    }

    /**
     * List all board members
     */
    public function index() {
        $filters = [
            'kurul_id' => $_GET['kurul_id'] ?? null,
            'is_approved' => $_GET['is_approved'] ?? null
        ];

        $members = $this->yayinKurulModel->getWithRelations($filters);
        $boards = $this->kurulModel->getApproved();

        $this->view('admin.kurul.index', [
            'members' => $members,
            'boards' => $boards,
            'filters' => $filters,
            'title' => 'Kurul Üyeleri Yönetimi'
        ]);
    }

    /**
     * Show create member form
     */
    public function create() {
        $unvanlar = $this->unvanModel->getApproved();
        $kurullar = $this->kurulModel->getApproved();
        $gorevler = $this->kurulGorevModel->getApproved();

        $this->view('admin.kurul.create', [
            'unvanlar' => $unvanlar,
            'kurullar' => $kurullar,
            'gorevler' => $gorevler,
            'title' => 'Yeni Kurul Üyesi Ekle'
        ]);
    }

    /**
     * Store new member
     */
    public function store() {
        \App\Helpers\CSRF::verify();

        $validator = Validator::make($_POST, [
            'ad_soyad' => 'required|min:3',
            'unvan' => 'required',
            'kurul_gorev' => 'required',
            'email' => 'nullable|email'
        ], [
            'ad_soyad' => 'Ad Soyad',
            'unvan' => 'Unvan',
            'kurul_gorev' => 'Kurul Görevi',
            'email' => 'E-posta'
        ]);

        if ($validator->fails()) {
            $_SESSION['validation_errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            redirect('/admin/kurul/create');
        }

        $data = [
            'ad_soyad' => $_POST['ad_soyad'],
            'unvan' => $_POST['unvan'],
            'kurul_gorev' => $_POST['kurul_gorev'],
            'sira' => $_POST['sira'] ?? 0,
            'aciklama' => $_POST['aciklama'] ?? null,
            'bolum_ad' => $_POST['bolum_ad'] ?? null,
            'email' => $_POST['email'] ?? null,
            'orcid_number' => $_POST['orcid_number'] ?? null,
            'web_page' => $_POST['web_page'] ?? null,
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id'],
            'created_role' => $_SESSION['grup_id'],
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        if ($this->yayinKurulModel->create($data)) {
            redirect('/admin/kurul', 'Üye başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/kurul/create', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit member form
     */
    public function edit($id) {
        $member = $this->yayinKurulModel->findWithRelations($id);
        if (!$member) {
            redirect('/admin/kurul', 'Üye bulunamadı.');
        }

        $unvanlar = $this->unvanModel->getApproved();
        $kurullar = $this->kurulModel->getApproved();
        $gorevler = $this->kurulGorevModel->getApproved(); // In a real app, we might filter by the selected board

        $this->view('admin.kurul.member_edit', [
            'member' => $member,
            'unvanlar' => $unvanlar,
            'kurullar' => $kurullar,
            'gorevler' => $gorevler,
            'title' => 'Kurul Üyesi Düzenle'
        ]);
    }

    /**
     * Update member
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();

        $validator = Validator::make($_POST, [
            'ad_soyad' => 'required|min:3',
            'unvan' => 'required',
            'kurul_gorev' => 'required',
            'email' => 'nullable|email'
        ], [
            'ad_soyad' => 'Ad Soyad',
            'unvan' => 'Unvan',
            'kurul_gorev' => 'Kurul Görevi',
            'email' => 'E-posta'
        ]);

        if ($validator->fails()) {
            $_SESSION['validation_errors'] = $validator->errors();
            redirect("/admin/kurul/edit/{$id}");
        }

        $data = [
            'ad_soyad' => $_POST['ad_soyad'],
            'unvan' => $_POST['unvan'],
            'kurul_gorev' => $_POST['kurul_gorev'],
            'sira' => $_POST['sira'] ?? 0,
            'aciklama' => $_POST['aciklama'] ?? null,
            'bolum_ad' => $_POST['bolum_ad'] ?? null,
            'email' => $_POST['email'] ?? null,
            'orcid_number' => $_POST['orcid_number'] ?? null,
            'web_page' => $_POST['web_page'] ?? null,
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->yayinKurulModel->update($id, $data)) {
            redirect('/admin/kurul', 'Üye bilgileri güncellendi.', 'success');
        } else {
            redirect("/admin/kurul/edit/{$id}", 'Bir hata oluştu.');
        }
    }

    /**
     * Delete member
     */
    public function delete($id) {
        if ($this->yayinKurulModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }

    /**
     * AJAX: Get duties for a board
     */
    public function getDutiesByBoard($kurulId) {
        $duties = $this->kurulGorevModel->getByKurul($kurulId);
        $this->json($duties);
    }

    // ========== LOOKUP TABLES MANAGEMENT ==========

    /**
     * Manage Unvan (Academic Titles)
     */
    public function manageUnvan() {
        if (!isAdmin()) {
            redirect('/dashboard', 'Bu sayfaya sadece yöneticiler erişebilir.');
        }

        $unvanlar = $this->unvanModel->all();
        $this->view('admin.lookup.unvan', [
            'unvanlar' => $unvanlar,
            'title' => 'Unvan Yönetimi'
        ]);
    }

    public function storeUnvan() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'unvan' => $_POST['unvan'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->unvanModel->create($data)) {
            redirect('/admin/lookup/unvan', 'Unvan başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/lookup/unvan', 'Bir hata oluştu.');
        }
    }

    public function updateUnvan($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'unvan' => $_POST['unvan'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->unvanModel->update($id, $data)) {
            redirect('/admin/lookup/unvan', 'Unvan güncellendi.', 'success');
        } else {
            redirect('/admin/lookup/unvan', 'Bir hata oluştu.');
        }
    }

    public function deleteUnvan($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->unvanModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }

    /**
     * Manage Kurul (Boards)
     */
    public function manageKurul() {
        if (!isAdmin()) {
            redirect('/dashboard', 'Bu sayfaya sadece yöneticiler erişebilir.');
        }

        $kurullar = $this->kurulModel->all();
        $this->view('admin.lookup.kurul', [
            'kurullar' => $kurullar,
            'title' => 'Kurul Yönetimi'
        ]);
    }

    public function storeKurul() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'kurul' => $_POST['kurul'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kurulModel->create($data)) {
            redirect('/admin/lookup/kurul', 'Kurul başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/lookup/kurul', 'Bir hata oluştu.');
        }
    }

    public function updateKurul($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'kurul' => $_POST['kurul'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kurulModel->update($id, $data)) {
            redirect('/admin/lookup/kurul', 'Kurul güncellendi.', 'success');
        } else {
            redirect('/admin/lookup/kurul', 'Bir hata oluştu.');
        }
    }

    public function deleteKurul($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->kurulModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }

    /**
     * Manage Görev (Duties)
     */
    public function manageGorev() {
        if (!isAdmin()) {
            redirect('/dashboard', 'Bu sayfaya sadece yöneticiler erişebilir.');
        }

        $gorevler = $this->kurulGorevModel->all();
        $kurullar = $this->kurulModel->getApproved();
        $this->view('admin.lookup.gorev', [
            'gorevler' => $gorevler,
            'kurullar' => $kurullar,
            'title' => 'Görev Yönetimi'
        ]);
    }

    public function storeGorev() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'kurul' => $_POST['kurul'],
            'kurul_gorev' => $_POST['kurul_gorev'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kurulGorevModel->create($data)) {
            redirect('/admin/lookup/gorev', 'Görev başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/lookup/gorev', 'Bir hata oluştu.');
        }
    }

    public function updateGorev($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) redirect('/dashboard');

        $data = [
            'kurul' => $_POST['kurul'],
            'kurul_gorev' => $_POST['kurul_gorev'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->kurulGorevModel->update($id, $data)) {
            redirect('/admin/lookup/gorev', 'Görev güncellendi.', 'success');
        } else {
            redirect('/admin/lookup/gorev', 'Bir hata oluştu.');
        }
    }

    public function deleteGorev($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->kurulGorevModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
