<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DergiTanim;

class DergiTanimController extends BaseController {
    protected $dergiModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->dergiModel = new DergiTanim($pdo);
    }

    /**
     * List all journal issues
     */
    public function index() {
        if (!isAdmin() && !isEditor()) redirect('/dashboard');
        
        $items = $this->dergiModel->all();
        
        $this->view('admin.dergi_tanim.index', [
            'items' => $items,
            'title' => 'Dergi Sayı Yönetimi'
        ]);
    }

    /**
     * Show create form
     */
    public function create() {
        if (!isAdmin() && !isEditor()) redirect('/dashboard');
        
        $this->view('admin.dergi_tanim.create', [
            'title' => 'Yeni Dergi Sayısı Ekle'
        ]);
    }

    /**
     * Store new issue
     */
    public function store() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'dergi_tanim' => $_POST['dergi_tanim'],
            'ing_baslik' => $_POST['ing_baslik'],
            'yayin_created_at' => $_POST['yayin_created_at'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0,
            'created_at' => date('Y-m-d'),
            'created_by' => $_SESSION['user_id'] ?? 0,
            'created_role' => $_SESSION['grup_id'] ?? 0,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];

        // Handle File Uploads (Cover and Generic PDF)
        if (isset($_FILES['dergi_kapak']) && $_FILES['dergi_kapak']['error'] === 0) {
            $fileName = time() . '_cap_' . $_FILES['dergi_kapak']['name'];
            move_uploaded_file($_FILES['dergi_kapak']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['dergi_kapak'] = $fileName;
        }

        if (isset($_FILES['jenerikdosyasi']) && $_FILES['jenerikdosyasi']['error'] === 0) {
            $fileName = time() . '_gen_' . $_FILES['jenerikdosyasi']['name'];
            move_uploaded_file($_FILES['jenerikdosyasi']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['jenerikdosyasi'] = $fileName;
        }

        if ($this->dergiModel->create($data)) {
            redirect('/admin/dergi-tanim', 'Dergi sayısı başarıyla oluşturuldu.', 'success');
        } else {
            redirect('/admin/dergi-tanim', 'Bir hata oluştu.');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $item = $this->dergiModel->find($id);
        if (!$item) redirect('/admin/dergi-tanim', 'Sayı bulunamadı.');

        $this->view('admin.dergi_tanim.edit', [
            'item' => $item,
            'title' => 'Dergi Sayısı Düzenle'
        ]);
    }

    /**
     * Update issue
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin() && !isEditor()) redirect('/dashboard');

        $data = [
            'dergi_tanim' => $_POST['dergi_tanim'],
            'ing_baslik' => $_POST['ing_baslik'],
            'yayin_created_at' => $_POST['yayin_created_at'],
            'is_approved' => isset($_POST['is_approved']) ? 1 : 0
        ];

        // Handle File Uploads
        if (isset($_FILES['dergi_kapak']) && $_FILES['dergi_kapak']['error'] === 0) {
            $fileName = time() . '_cap_' . $_FILES['dergi_kapak']['name'];
            move_uploaded_file($_FILES['dergi_kapak']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['dergi_kapak'] = $fileName;
        }

        if (isset($_FILES['jenerikdosyasi']) && $_FILES['jenerikdosyasi']['error'] === 0) {
            $fileName = time() . '_gen_' . $_FILES['jenerikdosyasi']['name'];
            move_uploaded_file($_FILES['jenerikdosyasi']['tmp_name'], PUBLIC_PATH . '/uploads/' . $fileName);
            $data['jenerikdosyasi'] = $fileName;
        }

        if ($this->dergiModel->update($id, $data)) {
            redirect('/admin/dergi-tanim', 'Dergi sayısı güncellendi.', 'success');
        } else {
            redirect('/admin/dergi-tanim', 'Bir hata oluştu.');
        }
    }

    /**
     * Delete issue
     */
    public function delete($id) {
        if (!isAdmin() && !isEditor()) {
            $this->json(['success' => false, 'message' => 'Yetkiniz yok.']);
            return;
        }

        if ($this->dergiModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
