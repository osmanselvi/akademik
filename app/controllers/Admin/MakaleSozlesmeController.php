<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MakaleSozlesme;

class MakaleSozlesmeController extends BaseController {
    protected $sozlesmeModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->sozlesmeModel = new MakaleSozlesme($pdo);
    }

    /**
     * List all agreement items
     */
    public function index() {
        if (!isAdmin() && !isEditor()) {
            redirect('/dashboard', 'Bu sayfaya erişim yetkiniz yok.');
        }

        $sql = "SELECT * FROM makale_sozlesme ORDER BY baslik ASC, id ASC";
        $stmt = $this->pdo->query($sql);
        $items = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $this->view('admin.makale_sozlesme.index', [
            'items' => $items,
            'title' => 'Telif Devir Sözleşmesi Yönetimi'
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        if (!isAdmin() && !isEditor()) redirect('/dashboard');
        
        $this->view('admin.makale_sozlesme.create', [
            'title' => 'Yeni Sözleşme Maddesi Ekle'
        ]);
    }

    /**
     * Store new item
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'baslik' => $_POST['baslik'],
            'metin' => $_POST['metin'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id'],
            'created_role' => $_SESSION['grup_id'],
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        if ($this->sozlesmeModel->create($data)) {
            redirect('/admin/makale-sozlesme', 'Madde başarıyla eklendi.', 'success');
        } else {
            redirect('/admin/makale-sozlesme', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $item = $this->sozlesmeModel->find($id);
        if (!$item) redirect('/admin/makale-sozlesme', 'Madde bulunamadı.');

        $this->view('admin.makale_sozlesme.edit', [
            'item' => $item,
            'title' => 'Sözleşme Maddesi Düzenle'
        ]);
    }

    /**
     * Update item
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'baslik' => $_POST['baslik'],
            'metin' => $_POST['metin'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        if ($this->sozlesmeModel->update($id, $data)) {
            redirect('/admin/makale-sozlesme', 'Madde güncellendi.', 'success');
        } else {
            redirect('/admin/makale-sozlesme', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete item
     */
    public function delete($id) {
        if (!isAdmin() && !isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->sozlesmeModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
