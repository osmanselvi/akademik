<?php
/**
 * Application Configuration
 */

return [
    'name' => $_ENV['APP_NAME'] ?? 'EBP Dergi Sistemi',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'Europe/Istanbul',
    'locale' => 'tr',
    'fallback_locale' => 'en',
    'editor_email' => $_ENV['EDITOR_EMAIL'] ?? 'bilgi@edebiyatbilim.com',
    
    'upload' => [
        'max_size' => $_ENV['MAX_UPLOAD_SIZE'] ?? 10485760, // 10MB
        'allowed_extensions' => explode(',', $_ENV['ALLOWED_EXTENSIONS'] ?? 'pdf'),
        'path' => __DIR__ . '/../storage/uploads/'
    ],
    
    'pagination' => [
        'per_page' => $_ENV['PER_PAGE'] ?? 20
    ],
    
    'cache' => [
        'driver' => $_ENV['CACHE_DRIVER'] ?? 'file',
        'prefix' => $_ENV['CACHE_PREFIX'] ?? 'ebp_',
        'ttl' => 3600 // 1 hour
    ],
    
    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 120,
        'secure' => filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'http_only' => filter_var($_ENV['SESSION_HTTP_ONLY'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'same_site' => $_ENV['SESSION_SAME_SITE'] ?? 'strict'
    ],
    
    'security' => [
        'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? 'csrf_token',
        'password_algorithm' => PASSWORD_ARGON2ID
    ]
];