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

        $users = $this->userModel->all();
        $groups = (new \App\Models\UserGroup($this->pdo))->all();

        // We should also get group names for display (redundant but kept for existing logic if needed)
        foreach ($users as &$user) {
            $group = $this->userModel->getGroup($user->id);
            $user->grup_adi = $group->grupadi ?? 'Bilinmeyen';
        }

        $this->view('admin.users', [
            'users' => $users,
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
