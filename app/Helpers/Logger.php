<?php
namespace App\Helpers;

class Logger {
    /**
     * Log an action to daily file
     */
    public static function log($action, $details = '') {
        $logDir = storagePath('logs');
        
        // Create directory if not exists
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $filename = 'activity_' . date('d_m_Y') . '.log';
        $filepath = $logDir . '/' . $filename;

        // User info
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'GUEST';
        $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
        $ip = getIp();
        
        // Format: [TIME] [IP] [USER_ID] [USER_NAME] [ACTION] DETAILS
        $logEntry = sprintf(
            "[%s] [%s] [%s] [%s] [%s] %s" . PHP_EOL,
            date('H:i:s'),
            $ip,
            $userId,
            $userName,
            strtoupper($action),
            $details
        );

        file_put_contents($filepath, $logEntry, FILE_APPEND);
    }
}
