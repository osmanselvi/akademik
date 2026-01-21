<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MakaleEsas;

class MakaleEsasController extends BaseController {
    protected $esasModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->esasModel = new MakaleEsas($pdo);
    }

    /**
     * List all makale esas
     */
    public function index() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $filters = [
            'is_approved' => $_GET['is_approved'] ?? null
        ];

        // Get all with optional filtering
        $sql = "SELECT * FROM makale_esas WHERE 1=1";
        $params = [];
        
        if (isset($filters['is_approved']) && $filters['is_approved'] !== '') {
            $sql .= " AND is_approved = ?";
            $params[] = $filters['is_approved'];
        }
        
        $sql .= " ORDER BY id ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $esasList = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $this->view('admin.makale_esas.index', [
            'esasList' => $esasList,
            'filters' => $filters,
            'title' => 'Makale Esasları Yönetimi'
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $this->view('admin.makale_esas.create', [
            'title' => 'Yeni Makale Esası Ekle'
        ]);
    }

    /**
     * Store new esas
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'esas_turu' => $_POST['esas_turu'],
            'aciklama' => $_POST['aciklama'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->esasModel->create($data)) {
            redirect('/admin/makale-esas', 'Makale esası başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/makale-esas', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $esas = $this->esasModel->find($id);
        if (!$esas) {
            redirect('/admin/makale-esas', 'Makale esası bulunamadı.');
        }

        $this->view('admin.makale_esas.edit', [
            'esas' => $esas,
            'title' => 'Makale Esası Düzenle'
        ]);
    }

    /**
     * Update esas
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'esas_turu' => $_POST['esas_turu'],
            'aciklama' => $_POST['aciklama'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->esasModel->update($id, $data)) {
            redirect('/admin/makale-esas', 'Makale esası güncellendi.', 'success');
        } else {
            redirect('/admin/makale-esas', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete esas
     */
    public function delete($id) {
        if (!isAdmin() && !isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->esasModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
