<?php
namespace App\Controllers;

use App\Models\Dergi;

/**
 * Home Controller
 */
class HomeController extends BaseController {
    
    public function index() {
        $dergiModel = new Dergi($this->pdo);
        
        // Get current issue
        $guncelDergi = $dergiModel->getCurrent();
        
        // Get recent issues
        $recentDergiler = $dergiModel->paginate(1, 5);
        
        return $this->view('home', [
            'pageTitle' => 'Ana Sayfa - EBP Dergi',
            'guncelDergi' => $guncelDergi,
            'recentDergiler' => $recentDergiler['data']
        ]);
    }
}
