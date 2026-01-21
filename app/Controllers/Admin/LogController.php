<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class LogController extends BaseController {
    
    /**
     * List log files
     */
    public function index() {
        if (!isAdmin()) \redirect('/dashboard');
        
        $logDir = storagePath('logs');
        $files = [];

        if (is_dir($logDir)) {
            $scanned = scandir($logDir);
            foreach ($scanned as $file) {
                if (strpos($file, '.log') !== false) {
                    $files[] = [
                        'name' => $file,
                        'size' => round(filesize($logDir . '/' . $file) / 1024, 2) . ' KB', // KB
                        'modified' => date("d.m.Y H:i:s", filemtime($logDir . '/' . $file))
                    ];
                }
            }
        }

        // Sort by name desc (newest date first usually)
        rsort($files);

        $selectedFile = $_GET['file'] ?? ($files[0]['name'] ?? null);
        $content = '';

        if ($selectedFile && file_exists($logDir . '/' . $selectedFile)) {
            $content = file_get_contents($logDir . '/' . $selectedFile);
        }

        $this->view('admin.logs.index', [
            'files' => $files,
            'selectedFile' => $selectedFile,
            'content' => $content,
            'title' => 'Sistem Logları'
        ]);
    }
}
