<?php
namespace App\Controllers;

use App\Models\Kurul;
use App\Models\YayinKurul;

class KurulController extends BaseController {
    protected $kurulModel;
    protected $yayinKurulModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->kurulModel = new Kurul($pdo);
        $this->yayinKurulModel = new YayinKurul($pdo);
    }

    /**
     * Show members of a specific board
     */
    public function show($id) {
        $kurul = $this->kurulModel->find($id);
        
        if (!$kurul || !$kurul->is_approved) {
            $this->view('errors.404', ['message' => 'Kurul bulunamadı.'], 'main');
            return;
        }

        $members = $this->yayinKurulModel->getWithRelations([
            'kurul_id' => $id,
            'is_approved' => 1
        ]);

        $this->view('kurul.show', [
            'kurul' => $kurul,
            'members' => $members,
            'title' => $kurul->kurul
        ]);
    }
}
