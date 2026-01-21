<?php
namespace App\Controllers;

use App\Models\DergiKunye;

class KunyeController extends BaseController {
    protected $kunyeModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->kunyeModel = new DergiKunye($pdo);
    }

    /**
     * Show public masthead page
     */
    public function show() {
        $kunye = $this->kunyeModel->getApprovedGrouped();

        $this->view('kunye.show', [
            'kunye' => $kunye,
            'title' => 'Dergi Künyesi'
        ]);
    }
}
