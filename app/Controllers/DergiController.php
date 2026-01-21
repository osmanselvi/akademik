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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 9; // Grid layout 3x3

        $pagination = $this->dergiModel->paginateWithArticleCounts($page, $perPage);
        $dergiler = $pagination['data'];
        
        $paginator = new \App\Helpers\Paginator($pagination['total'], $perPage, $page);
        
        return $this->view('dergi.index', [
            'dergiler' => $dergiler,
            'paginator' => $paginator,
            'pageTitle' => 'Dergi Sayıları'
        ]);
    }
    
    /**
     * Show single journal issue
     */
    public function show($id) {
        $id = (int)$id;
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
