<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplate;

class EmailTemplateController extends BaseController {
    protected $templateModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->templateModel = new EmailTemplate($pdo);
    }

    public function index() {
        if (!\isAdmin()) \redirect('/dashboard');
        
        $templates = $this->templateModel->all();
        
        $this->view('admin.email-templates.index', [
            'templates' => $templates,
            'title' => 'E-posta Şablonları'
        ]);
    }

    public function edit($id) {
        if (!\isAdmin()) \redirect('/dashboard');

        $template = $this->templateModel->find($id);
        
        if (!$template) {
            $this->redirect('/admin/email-templates', 'Şablon bulunamadı.');
        }

        $this->view('admin.email-templates.edit', [
            'template' => $template,
            'title' => 'Şablon Düzenle: ' . $template->name
        ]);
    }

    public function update($id) {
        if (!\isAdmin()) \redirect('/dashboard');

        $data = [
            'subject' => $_POST['subject'],
            'body' => $_POST['body']
        ];

        $this->templateModel->update($id, $data);
        
        $this->redirect('/admin/email-templates', 'Şablon başarıyla güncellendi.');
    }
}
