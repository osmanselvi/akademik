<?php
namespace App\Helpers;

/**
 * CSRF Protection Helper
 */
class CSRF {
    /**
     * Generate CSRF token
     */
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    public static function validateToken($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Get CSRF token input field
     */
    public static function field() {
        $token = self::generateToken();
        return "<input type='hidden' name='csrf_token' value='{$token}'>";
    }
    
    /**
     * Get CSRF token meta tag (for AJAX)
     */
    public static function meta() {
        $token = self::generateToken();
        return "<meta name='csrf-token' content='{$token}'>";
    }
    
    /**
     * Verify request or die
     */
    public static function verify() {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        if (!self::validateToken($token)) {
            http_response_code(419);
            die('CSRF token mismatch');
        }
    }
}
