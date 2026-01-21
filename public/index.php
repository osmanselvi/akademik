<?php
// Route static files directly for built-in PHP server
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file(__DIR__ . $path)) {
        return false;
    }
}

// Load application bootstrap
try {
    require_once __DIR__ . '/../bootstrap.php';
} catch (Exception $e) {
    die("Bootstrap Error: " . $e->getMessage());
}

// Load routes
try {
    $router = require_once __DIR__ . '/../routes/web.php';
} catch (Exception $e) {
    die("Routes Error: " . $e->getMessage());
}

// Dispatch request
try {
    $router->dispatch();
} catch (Exception $e) {
    echo "<h1>Router Error</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
