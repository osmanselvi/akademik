<?php
namespace App\Controllers;

use App\Models\Makale;

class MakaleController extends BaseController {
    protected $makaleModel;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->makaleModel = new Makale($pdo);
    }

    /**
     * Show single article
     */
    public function show($id) {
        $makale = $this->makaleModel->findWithJournal($id);
        
        if (!$makale) {
            $this->view('errors.404', ['message' => 'Makale bulunamadı.'], 'main');
            return;
        }

        $this->view('makale.show', [
            'makale' => $makale,
            'title' => $makale->makale_baslik
        ]);
    }

    /**
     * Search articles
     */
    public function search() {
        $query = $_GET['q'] ?? '';
        
        // Default years as requested by user
        $defaultStartYear = 2021;
        $defaultEndYear = 2026;

        // Initialize filters
        $filters = [
            'start_year' => $_GET['start_year'] ?? '',
            'end_year' => $_GET['end_year'] ?? '',
            'journal_id' => $_GET['journal_id'] ?? ''
        ];
        
        $results = [];
        // Perform search. If years are empty, Makale::search will not filter by year.
        if (!empty($query) || !empty($filters['start_year']) || !empty($filters['end_year']) || !empty($filters['journal_id'])) {
            $results = $this->makaleModel->search($query, $filters);
        }

        // Now set defaults for the VIEW only if they are empty, so the dropdowns look right
        $viewFilters = $filters;
        if (empty($viewFilters['start_year'])) $viewFilters['start_year'] = $defaultStartYear;
        if (empty($viewFilters['end_year'])) $viewFilters['end_year'] = $defaultEndYear;

        $dergiModel = new \App\Models\Dergi($this->pdo);
        $dbYears = $dergiModel->getDistinctYears();
        
        // Merge DB years with our static range 2021-2026
        $allYears = [];
        $maxYear = max(2026, (!empty($dbYears) ? $dbYears[0]->yil : 0));
        for ($y = $maxYear; $y >= 2021; $y--) {
            $allYears[] = (object)['yil' => $y];
        }
        
        $journals = $dergiModel->all();

        $this->view('makale.search', [
            'results' => $results,
            'query' => $query,
            'filters' => $viewFilters, // Use the one with defaults for UI
            'years' => $allYears,
            'journals' => $journals,
            'title' => 'Gelişmiş Makale Ara'
        ]);
    }
}
