<?php
/**
 * Helper Functions
 * 
 * Global helper functions used throughout the application
 */

/**
 * Get environment variable with default value
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

/**
 * Get config value
 */
function config($key, $default = null) {
    static $config = null;
    
    if ($config === null) {
        $config = [
            'app' => require __DIR__ . '/../../config/app.php',
            'database' => require __DIR__ . '/../../config/database.php'
        ];
    }
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}

/**
 * Generate URL for given path
 */
function url($path = '') {
    $baseUrl = rtrim(config('app.url'), '/');
    $path = ltrim($path, '/');
    return $baseUrl . '/' . $path;
}

/**
 * Redirect to URL
 */
function redirect($url, $message = null, $type = 'danger') {
    if (is_string($message)) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
        $statusCode = 302;
    } else {
        $statusCode = is_int($message) ? $message : 302;
    }
    
    header("Location: $url", true, $statusCode);
    exit;
}

/**
 * Get old input value (from session after validation error)
 */
function old($key, $default = '') {
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Get validation error for a field
 */
function error($field) {
    if (isset($_SESSION['validation_errors'][$field])) {
        return $_SESSION['validation_errors'][$field][0];
    }
    return null;
}

/**
 * Check if field has error
 */
function has_error($field) {
    return isset($_SESSION['validation_errors'][$field]);
}

/**
 * Clear validation data
 */
function clear_validation() {
    unset($_SESSION['validation_errors']);
    unset($_SESSION['old_input']);
}

/**
 * Check if user is authenticated
 */
function auth() {
    return isset($_SESSION['user_id']);
}

/**
 * Alias for auth()
 */
function isLoggedIn() {
    return auth();
}

/**
 * Check if user is Admin
 */
function isAdmin() {
    return (auth() && ($_SESSION['grup_id'] == 1));
}

/**
 * Check if user is Editor
 */
function isEditor() {
    return (auth() && ($_SESSION['grup_id'] == 2));
}

/**
 * Check if user has specific role by group ID or name
 */
function hasRole($role) {
    if (!auth()) {
        return false;
    }
    
    $userRole = $_SESSION['grup_id'] ?? null;
    
    // Support numeric ID directly
    if (is_numeric($role)) {
        return $userRole == $role;
    }

    // Role mapping
    $roles = [
        'admin' => 1,
        'editor' => 2,
        'reviewer' => 4, // Database shows 4 for Hakem
        'member' => 5,
        'visitor' => 6
    ];
    
    return $userRole == ($roles[$role] ?? null);
}

/**
 * Return JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Dump and die (for debugging)
 */
function dd(...$vars) {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Asset URL helper
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Storage path helper
 */
function storagePath($path = '') {
    return __DIR__ . '/../../storage/' . ltrim($path, '/');
}

/**
 * Public path helper
 */
function publicPath($path = '') {
    return __DIR__ . '/../../public/' . ltrim($path, '/');
}

/**
 * Format date in Turkish locale
 */
function formatDate($date, $format = 'd.m.Y') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Truncate string
 */
function str_limit($string, $limit = 100, $end = '...') {
    if (mb_strlen($string) <= $limit) {
        return $string;
    }
    return mb_substr($string, 0, $limit) . $end;
}
/**
 * Get CSRF token
 */
function csrf_token() {
    return \App\Helpers\CSRF::generateToken();
}

/**
 * Get CSRF hidden input field
 */
function csrf_field() {
    return \App\Helpers\CSRF::field();
}
/**
 * Check if a file contains PHP code tags
 */
function preg_php_content($path) {
    if (!file_exists($path)) return false;
    $content = file_get_contents($path);
    return preg_match('/<\?php|<\?=| \?>/i', $content);
}
/**
 * Escape HTML for output
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Escape HTML attributes
 */
function attr($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
/**
 * Get client IP address
 */
function getIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Get image URL by checking multiple directories (uploads, images)
 */
function get_image_url($filename) {
    if (!$filename) return null;
    
    // Check if it's already a full URL
    if (strpos($filename, 'http') === 0) {
        return $filename;
    }

    // Try uploads directory first
    if (file_exists(publicPath('uploads/' . $filename))) {
        return '/uploads/' . $filename;
    }
    
    // Fallback to images directory
    return '/images/' . $filename;
}
