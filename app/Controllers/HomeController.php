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
        // Get recent issues with pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 6; // Increased to 6 for better grid layout (2 rows of 3)
        
        $pagination = $dergiModel->paginateWithArticleCounts($page, $perPage);
        $paginator = new \App\Helpers\Paginator($pagination['total'], $perPage, $page, '/?page=(:num)');
        
        return $this->view('home', [
            'pageTitle' => 'Ana Sayfa - EBP Dergi',
            'guncelDergi' => $guncelDergi,
            'recentDergiler' => $pagination['data'],
            'paginator' => $paginator
        ]);
    }
}
