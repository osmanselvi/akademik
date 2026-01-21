<?php
namespace App\Controllers;

use App\Models\User;

class AdminController extends BaseController {
    protected $userModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->userModel = new User($pdo);
    }

    /**
     * List all users (Admin only)
     */
    public function users() {
        if (!isAdmin()) {
            $this->redirect('/dashboard', 'Bu yetkiye sahip değilsiniz.');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;

        $pagination = $this->userModel->paginateWithGroups($page, $perPage);
        $users = $pagination['data'];
        
        $paginator = new \App\Helpers\Paginator($pagination['total'], $perPage, $page);

        $groups = (new \App\Models\UserGroup($this->pdo))->all();
        
        // Loop removed: Group data is now fetched in the initial query via LEFT JOIN ('grup_adi' column)

        $this->view('admin.users', [
            'users' => $users,
            'paginator' => $paginator,
            'groups' => $groups,
            'pageTitle' => 'Kullanıcı Yönetimi'
        ]);
    }

    /**
     * Update user group
     */
    public function updateUserGroup($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkisiz erişim'], 403);
        }

        $newGroupId = $_POST['grup_id'] ?? null;
        if (!$newGroupId) {
            $this->json(['success' => false, 'message' => 'Grup ID gerekli.'], 400);
        }

        if ($this->userModel->update($id, ['grup_id' => $newGroupId])) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Güncelleme işlemi başarısız.']);
        }
    }

    /**
     * Delete user
     */
    public function deleteUser($id) {
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Yetkisiz erişim'], 403);
        }

        if ($id == $_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'Kendi hesabınızı silemezsiniz.'], 400);
        }

        if ($this->userModel->delete($id)) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Silme işlemi başarısız.']);
        }
    }
}
