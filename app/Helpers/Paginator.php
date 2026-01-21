<?php
namespace App\Helpers;

class Paginator {
    protected $totalItems;
    protected $perPage;
    protected $currentPage;
    protected $totalPages;
    protected $baseUrl;
    protected $queryParams;

    public function __construct($totalItems, $perPage = 10, $currentPage = 1, $baseUrl = '', $queryParams = []) {
        $this->totalItems = $totalItems;
        $this->perPage = $perPage;
        $this->currentPage = (int) max(1, $currentPage);
        $this->totalPages = (int) ceil($totalItems / $perPage);
        $this->baseUrl = $baseUrl ?: $_SERVER['PHP_SELF'];
        $this->queryParams = $queryParams ?: $_GET;
        
        // Remove 'page' from query params to avoid duplication
        if (isset($this->queryParams['page'])) {
            unset($this->queryParams['page']);
        }
    }

    public function getLinks($classes = 'pagination justify-content-center') {
        if ($this->totalPages <= 1) {
            return '';
        }

        $html = '<nav aria-label="Page navigation"><ul class="' . $classes . '">';

        // Previous Link
        $prevClass = ($this->currentPage <= 1) ? 'disabled' : '';
        $prevUrl = $this->getUrl($this->currentPage - 1);
        $html .= '<li class="page-item ' . $prevClass . '">';
        $html .= '<a class="page-link" href="' . $prevUrl . '" aria-label="Önceki"><span aria-hidden="true">&laquo;</span></a></li>';

        // Page Numbers
        // Show limited window of pages around current page
        $start = max(1, $this->currentPage - 2);
        $end = min($this->totalPages, $this->currentPage + 2);

        if ($start > 1) {
             $html .= '<li class="page-item"><a class="page-link" href="' . $this->getUrl(1) . '">1</a></li>';
             if ($start > 2) {
                 $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
             }
        }

        for ($i = $start; $i <= $end; $i++) {
            $active = ($i == $this->currentPage) ? 'active' : '';
            $html .= '<li class="page-item ' . $active . '">';
            $html .= '<a class="page-link" href="' . $this->getUrl($i) . '">' . $i . '</a></li>';
        }

        if ($end < $this->totalPages) {
             if ($end < $this->totalPages - 1) {
                 $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
             }
             $html .= '<li class="page-item"><a class="page-link" href="' . $this->getUrl($this->totalPages) . '">' . $this->totalPages . '</a></li>';
        }

        // Next Link
        $nextClass = ($this->currentPage >= $this->totalPages) ? 'disabled' : '';
        $nextUrl = $this->getUrl($this->currentPage + 1);
        $html .= '<li class="page-item ' . $nextClass . '">';
        $html .= '<a class="page-link" href="' . $nextUrl . '" aria-label="Sonraki"><span aria-hidden="true">&raquo;</span></a></li>';

        $html .= '</ul></nav>';

        return $html;
    }

    protected function getUrl($page) {
        // If baseUrl contains (:num) pattern, use it
        if (strpos($this->baseUrl, '(:num)') !== false) {
            return str_replace('(:num)', $page, $this->baseUrl);
        }

        $params = $this->queryParams;
        $params['page'] = $page;
        
        // Remove 'page' from params if it's already in the query string of baseUrl (edge case)
        $separator = (strpos($this->baseUrl, '?') !== false) ? '&' : '?';
        return $this->baseUrl . $separator . http_build_query($params);
    }

    public function getInfo() {
        $start = ($this->currentPage - 1) * $this->perPage + 1;
        $end = min($this->totalItems, $this->currentPage * $this->perPage);
        return "Toplam {$this->totalItems} kayıttan {$start}-{$end} arası gösteriliyor.";
    }
}
