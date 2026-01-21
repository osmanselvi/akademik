<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class StatsController extends BaseController {
    public function index() {
        if (!\isAdmin() && !\isEditor()) \redirect('/dashboard');

        // 1. Article Status Counts (For Pie Chart)
        $statusCounts = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM gonderilen_makale 
            GROUP BY status
        ")->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Most Viewed Articles (Top 5)
        $mostViewed = $this->pdo->query("
            SELECT makale_baslik, view_count, is_approved 
            FROM online_makale 
            ORDER BY view_count DESC 
            LIMIT 5
        ")->fetchAll(\PDO::FETCH_OBJ);

        // 3. Most Downloaded Articles (Top 5)
        $mostDownloaded = $this->pdo->query("
            SELECT makale_baslik, download_count, is_approved 
            FROM online_makale 
            ORDER BY download_count DESC 
            LIMIT 5
        ")->fetchAll(\PDO::FETCH_OBJ);

        // 4. Articles by Journal Issue (Bar Chart)
        $journalStats = $this->pdo->query("
            SELECT d.dergi_tanim, COUNT(m.id) as article_count
            FROM dergi_tanim d
            LEFT JOIN online_makale m ON d.id = m.dergi_tanim
            GROUP BY d.id
            ORDER BY d.yayin_created_at DESC
            LIMIT 10
        ")->fetchAll(\PDO::FETCH_OBJ);

        // 5. Total Counts
        $totalArticles = $this->pdo->query("SELECT COUNT(*) FROM online_makale")->fetchColumn();
        $totalDownloads = $this->pdo->query("SELECT SUM(download_count) FROM online_makale")->fetchColumn();
        $totalViews = $this->pdo->query("SELECT SUM(view_count) FROM online_makale")->fetchColumn();

        $this->view('admin.stats.index', [
            'statusCounts' => $statusCounts,
            'mostViewed' => $mostViewed,
            'mostDownloaded' => $mostDownloaded,
            'journalStats' => $journalStats,
            'totalArticles' => $totalArticles ?? 0,
            'totalDownloads' => $totalDownloads ?? 0,
            'totalViews' => $totalViews ?? 0,
            'title' => 'Makale İstatistikleri'
        ]);
    }
}
