<?php
namespace App\Controllers;

/**
 * Base Controller Class
 * 
 * All controllers should extend this class
 */
abstract class BaseController {
    protected $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->checkSession();
    }

    /**
     * Check if session has expired based on SESSION_LIFETIME
     */
    private function checkSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            $lastActivity = $_SESSION['last_activity'] ?? time();
            $lifetime = (int)($_ENV['SESSION_LIFETIME'] ?? getenv('SESSION_LIFETIME') ?: 120) * 60;

            if (time() - $lastActivity > $lifetime) {
                session_unset();
                session_destroy();
                $this->redirect('/login', 'Oturum süreniz doldu, lütfen tekrar giriş yapın.');
            }

            $_SESSION['last_activity'] = time();
        }
    }
    
    /**
     * Render a view
     */
    protected function view($viewPath, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $viewPath) . '.php';
        
        if (!file_exists($viewFile)) {
            die("View not found: {$viewPath}");
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        // If there's a layout, include it
        $layoutFile = __DIR__ . '/../views/layouts/main.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $message = null) {
        if ($message) {
            $_SESSION['flash_message'] = $message;
        }
        header("Location: $url");
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Get request input
     */
    protected function input($key, $default = null) {
        return $_REQUEST[$key] ?? $default;
    }
    
    /**
     * Validate request
     */
    protected function validate($rules) {
        $validator = \App\Helpers\Validator::make($_POST, $rules);
        
        if ($validator->fails()) {
            $_SESSION['errors'] = $validator->errors();
            $_SESSION['old_input'] = $_POST;
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        
        return true;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function requireAuth() {
        if (!auth()) {
            $this->redirect('/login');
        }
    }
    
    /**
     * Check if user has role
     */
    protected function requireRole($role) {
        $this->requireAuth();
        
        if (!hasRole($role)) {
            http_response_code(403);
            die('Unauthorized');
        }
    }
}
