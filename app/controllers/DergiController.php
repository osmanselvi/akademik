<?php
namespace App\Controllers;

use App\Models\Dergi;
use App\Models\Makale;

/**
 * Dergi Controller
 */
class DergiController extends BaseController {
    private $dergiModel;
    private $makaleModel;
    
    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->dergiModel = new Dergi($pdo);
        $this->makaleModel = new Makale($pdo);
    }
    
    /**
     * List all journal issues
     */
    public function index() {
        $dergiler = $this->dergiModel->allWithArticleCounts();
        
        return $this->view('dergi.index', [
            'dergiler' => $dergiler,
            'pageTitle' => 'Dergi Sayıları'
        ]);
    }
    
    /**
     * Show single journal issue
     */
    public function show($id) {
        $dergi = $this->dergiModel->find($id);
        
        if (!$dergi) {
            $this->redirect('/', 'Dergi bulunamadı');
        }
        
        $makaleler = $this->makaleModel->getByDergi($id);
        
        return $this->view('dergi.show', [
            'dergi' => $dergi,
            'makaleler' => $makaleler,
            'pageTitle' => $dergi->dergi_tanim
        ]);
    }
}
