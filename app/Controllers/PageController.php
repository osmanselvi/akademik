<?php
namespace App\Controllers;

class PageController extends BaseController {
    
    /**
     * Show About page
     */
    public function about() {
        $this->view('pages.about', [
            'title' => 'Hakkımızda'
        ]);
    }
    
    /**
     * Show Indexing page
     */
    public function indexing() {
        $dizinModel = new \App\Models\Dizin($this->pdo);
        $dizinler = $dizinModel->getApproved();
        
        $this->view('pages.indexing', [
            'dizinler' => $dizinler,
            'title' => 'Dizinler'
        ]);
    }
    
    /**
     * Show Article Guidelines page
     */
    public function guidelines() {
        $esasModel = new \App\Models\MakaleEsas($this->pdo);
        $esasList = $esasModel->getApproved();
        
        // Get selected esas or default to first one
        $selectedId = $_GET['esas_id'] ?? ($esasList[0]->id ?? 1);
        $selectedEsas = $esasModel->getById($selectedId);
        
        $this->view('pages.guidelines', [
            'esasList' => $esasList,
            'selectedEsas' => $selectedEsas,
            'selectedId' => $selectedId,
            'title' => 'Makale Esasları'
        ]);
    }
    
    /**
     * Show Publication Ethics page
     */
    public function ethics() {
        $this->view('pages.ethics', [
            'title' => 'Yayın Etiği ve İlkeler'
        ]);
    }
    
    /**
     * Show Copyright page
     */
    public function copyright() {
        $sozlesmeModel = new \App\Models\MakaleSozlesme($this->pdo);
        $groupedTerms = $sozlesmeModel->getGroupedApproved();
        
        $this->view('pages.copyright', [
            'groupedTerms' => $groupedTerms,
            'title' => 'Telif Devir Sözleşmesi'
        ]);
    }
}
