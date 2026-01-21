<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Setting;

class SettingsController extends BaseController {
    protected $model;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->model = new Setting($pdo);
    }

    /**
     * Show settings form
     */
    public function index() {
        if (!isAdmin()) \redirect('/dashboard');

        $settings = $this->model->get();

        $this->view('admin.settings.index', [
            'settings' => $settings,
            'title' => 'Genel Ayarlar'
        ]);
    }

    /**
     * Update settings
     */
    public function update() {
        \App\Helpers\CSRF::verify();
        if (!isAdmin()) \redirect('/dashboard');

        $data = [
            'SiteAdi' => $_POST['SiteAdi'],
            'SiteTitle' => $_POST['SiteTitle'],
            'SiteDescription' => $_POST['SiteDescription'],
            'SiteKeywords' => $_POST['SiteKeywords'],
            'siteCopyRightMetni' => $_POST['siteCopyRightMetni'],
            'SiteEmailAdresi' => $_POST['SiteEmailAdresi'],
            'SiteEmailHost' => $_POST['SiteEmailHost'],
            'SiteEmailSifresi' => $_POST['SiteEmailSifresi']
        ];

        // Logo Upload
        if (!empty($_FILES['siteLogosu']['name'])) {
            $uploadDir = publicPath('images/');
            $fileName = 'logo_' . time() . '.' . pathinfo($_FILES['siteLogosu']['name'], PATHINFO_EXTENSION);
            
            if (move_uploaded_file($_FILES['siteLogosu']['tmp_name'], $uploadDir . $fileName)) {
                $data['siteLogosu'] = $fileName;
            }
        }

        if ($this->model->update(1, $data)) {
            \redirect('/admin/settings', 'Ayarlar başarıyla güncellendi.', 'success');
        } else {
            \redirect('/admin/settings', 'Bir hata oluştu.', 'danger');
        }
    }
}
