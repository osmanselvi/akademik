<?php
/**
 * Application Bootstrap File
 * 
 * This file initializes the application by:
 * - Loading environment variables
 * - Setting up autoloading
 * - Initializing database connection
 * - Starting session
 */

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables (if .env exists)
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad(); // Use safeLoad to avoid errors
}

// Initialize Logger
$logger = new \App\Helpers\Logger();

// Global Exception Handler
set_exception_handler(function ($e) use ($logger) {
    global $appDebug;
    $logger->error("Uncaught Exception: " . $e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);

    if ($appDebug === 'true' || $appDebug === true) {
        // Default PHP handling will display it if display_errors is on
        throw $e;
    } else {
        http_response_code(500);
        echo "Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyiniz.";
        exit;
    }
});

// Global Error Handler
set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($logger) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $type = match($errno) {
        E_USER_ERROR => 'FATAL',
        E_USER_WARNING => 'WARNING',
        E_USER_NOTICE => 'NOTICE',
        default => 'UNKNOWN'
    };

    $logger->error("PHP Error [$type]: $errstr", [
        'file' => $errfile,
        'line' => $errline
    ]);

    return false; // Let PHP handle standard display/reporting based on ini settings
});

// Error reporting based on environment
$appDebug = $_ENV['APP_DEBUG'] ?? 'true';
if ($appDebug === 'true' || $appDebug === true) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set timezone
date_default_timezone_set('Europe/Istanbul');

// Session configuration
$sessionPath = __DIR__ . '/storage/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

ini_set('session.cookie_httponly', $_ENV['SESSION_HTTP_ONLY'] ?? '1');
ini_set('session.cookie_samesite', $_ENV['SESSION_SAME_SITE'] ?? 'Strict');
if (($_ENV['SESSION_SECURE'] ?? 'false') === 'true') {
    ini_set('session.cookie_secure', '1');
}
ini_set('session.gc_maxlifetime', $_ENV['SESSION_LIFETIME'] ?? '7200');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize database connection
function getDatabase() {
    static $pdo = null;
    
    if ($pdo === null) {
        $config = require __DIR__ . '/config/database.php';
        $dbConfig = $config['connections'][$config['default']];
        
        $dsn = sprintf(
            "%s:host=%s;port=%s;dbname=%s;charset=%s",
            $dbConfig['driver'],
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['database'],
            $dbConfig['charset']
        );
        
        try {
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
        } catch (PDOException $e) {
            if ($_ENV['APP_DEBUG'] === 'true') {
                die('Database connection failed: ' . $e->getMessage());
            } else {
                error_log('Database connection failed: ' . $e->getMessage());
                die('Database connection error. Please contact support.');
            }
        }
    }
    
    return $pdo;
}

// Make database connection available globally (for legacy compatibility)
$baglanti = getDatabase();

// Load helper functions
require_once __DIR__ . '/app/Helpers/functions.php';

return [
    'db' => $baglanti,
    'config' => [
        'app' => require __DIR__ . '/config/app.php',
        'database' => require __DIR__ . '/config/database.php'
    ]
];
