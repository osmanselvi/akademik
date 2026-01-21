<?php
/**
 * Database Backup Script
 * 
 * Uses mysqldump to create a SQL backup in storage/backups
 */

require_once __DIR__ . '/../bootstrap.php';

$backupDir = __DIR__ . '/../storage/backups';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$date = date('Y-m-d_H-i-s');
$filename = "backup_{$date}.sql";
$filePath = $backupDir . DIRECTORY_SEPARATOR . $filename;

// Get DB credentials from environment
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_DATABASE'] ?? '';
$dbUser = $_ENV['DB_USERNAME'] ?? '';
$dbPass = $_ENV['DB_PASSWORD'] ?? '';

if (empty($dbName) || empty($dbUser)) {
    die("Error: DB credentials not found in .env\n");
}

// Build mysqldump command
// Note: --result-file handles Windows/Unix path differences better
$command = sprintf(
    'mysqldump --host=%s --user=%s --password=%s %s --result-file=%s',
    escapeshellarg($dbHost),
    escapeshellarg($dbUser),
    escapeshellarg($dbPass),
    escapeshellarg($dbName),
    escapeshellarg($filePath)
);

echo "Starting backup of database '{$dbName}' to '{$filename}'...\n";

exec($command, $output, $returnCode);

if ($returnCode === 0 && file_exists($filePath)) {
    echo "Backup successful! Path: {$filePath}\n";
    echo "Size: " . round(filesize($filePath) / 1024, 2) . " KB\n";
} else {
    echo "Backup failed with return code: {$returnCode}\n";
    if (!empty($output)) {
        echo "Output: " . implode("\n", $output) . "\n";
    }
}
