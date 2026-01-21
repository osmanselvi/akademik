<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\User;
use App\Models\UserGroup;

class UserController extends BaseController {
    protected $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->userModel = new User($pdo);
    }

    /**
     * List users
     */
    public function index() {
        if (!isAdmin()) \redirect('/dashboard');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $search = $_GET['q'] ?? '';

        // TODO: Implement search in model if needed
        $pagination = $this->userModel->paginateWithGroups($page, $perPage);
        
        $this->view('admin.users.index', [
            'users' => $pagination['data'],
            'paginator' => new \App\Helpers\Paginator($pagination['total'], $perPage, $page, '/admin/users'),
            'groups' => (new UserGroup($this->pdo))->all(),
            'title' => 'Kullanıcı Yönetimi'
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id) {
        if (!isAdmin()) \redirect('/dashboard');

        $user = $this->userModel->find($id);
        if (!$user) \redirect('/admin/users', 'Kullanıcı bulunamadı.');

        $groups = (new UserGroup($this->pdo))->all();

        $this->view('admin.users.edit', [
            'user' => $user,
            'groups' => $groups,
            'title' => 'Kullanıcı Düzenle: ' . $user->ad_soyad
        ]);
    }

    /**
     * Update user
     */
    public function update($id) {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) \redirect('/dashboard');

        // Prevent modifying own active status or group if it locks out admin
        if ($id == $_SESSION['user_id'] && isset($_POST['is_active']) && $_POST['is_active'] == 0) {
             \redirect('/admin/users/edit/' . $id, 'Kendi hesabınızı pasife alamazsınız.', 'danger');
        }

        $data = [
            'ad_soyad' => $_POST['ad_soyad'],
            'email' => $_POST['email'],
            'grup_id' => $_POST['grup_id'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'is_verified' => isset($_POST['is_verified']) ? 1 : 0,
            'telefon' => $_POST['telefon'] ?? '',
        ];

        // Password Reset
        if (!empty($_POST['new_password'])) {
            $data['sifre'] = md5($_POST['new_password']); // Legacy MD5 support
        }

        if ($this->userModel->update($id, $data)) {
            \redirect('/admin/users', 'Kullanıcı başarıyla güncellendi.', 'success');
        } else {
            \redirect('/admin/users/edit/' . $id, 'Güncelleme hatası.', 'danger');
        }
    }

    /**
     * Delete user
     */
    public function delete($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkisiz erişim']);
            return;
        }

        if ($id == $_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'Kendini silemezsin.']);
            return;
        }

        if ($this->userModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme hatası.']);
        }
    }
}
