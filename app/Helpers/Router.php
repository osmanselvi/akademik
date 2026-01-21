<?php
namespace App\Helpers;

/**
 * Simple Router Class
 */
class Router {
    private $routes = [];
    private $basePath = '';
    
    public function __construct($basePath = '') {
        $this->basePath = $basePath;
    }
    
    /**
     * Register GET route
     */
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Register POST route
     */
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Add route to collection
     */
    private function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    /**
     * Dispatch request to appropriate handler
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string and base path
        $path = parse_url($uri, PHP_URL_PATH);
        $path = str_replace($this->basePath, '', $path);
        
        // Remove index.php from path if present
        $path = str_replace('/index.php', '', $path);
        
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        
        // Debug
        $debug = ($_ENV['APP_DEBUG'] ?? 'true') === 'true';

        // Log request (excluding favicons/assets if they hit here)
        if (strpos($path, '.') === false) {
             logger('VISIT', $path);
        }

        if ($debug) {
            error_log("Router Debug - Method: $method, Path: $path");
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $params = $this->matchRoute($route['path'], $path);
            
            if ($params !== false) {
                if ($debug) {
                    error_log("Router Debug - Matched route: {$route['path']} -> {$route['handler']}");
                }
                return $this->callHandler($route['handler'], $params);
            }
        }
        
        // 404 Not Found
        if ($debug) {
            error_log("Router Debug - No route matched for: $path");
        }
        http_response_code(404);
        echo $this->render404();
        return;
    }
    
    /**
     * Match route pattern with current path
     */
    private function matchRoute($routePath, $requestPath) {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $requestPath, $matches)) {
            array_shift($matches); // Remove full match
            return $matches;
        }
        
        return false;
    }
    
    /**
     * Call route handler
     */
    private function callHandler($handler, $params) {
        global $baglanti;
        
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        
        // Controller@method format
        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $method) = explode('@', $handler);
            
            $controllerClass = "App\\Controllers\\{$controller}";
            
            if (!class_exists($controllerClass)) {
                die("Controller not found: {$controllerClass}");
            }
            
            $controllerInstance = new $controllerClass($baglanti);
            
            if (!method_exists($controllerInstance, $method)) {
                die("Method not found: {$method} in {$controllerClass}");
            }
            
            return call_user_func_array([$controllerInstance, $method], $params);
        }
        
        die("Invalid handler");
    }
    
    /**
     * Render 404 page
     */
    private function render404() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>404 - Sayfa Bulunamadı</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { font-size: 48px; color: #333; }
                p { color: #666; }
                a { color: #667eea; text-decoration: none; }
            </style>
        </head>
        <body>
            <h1>404</h1>
            <p>Aradığınız sayfa bulunamadı.</p>
            <p><a href="/">Ana Sayfaya Dön</a></p>
        </body>
        </html>
        ';
    }
}
