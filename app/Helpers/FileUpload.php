<?php
namespace App\Helpers;

use Exception;

/**
 * File Upload Helper
 */
class FileUpload {
    private $maxSize;
    private $allowedExtensions;
    private $uploadDir;
    
    private $mimeMap = [
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png'
    ];
    
    public function __construct() {
        $this->maxSize = config('app.upload.max_size', 10485760);
        $this->allowedExtensions = config('app.upload.allowed_extensions', ['pdf']);
        $this->uploadDir = config('app.upload.path', storage_path('uploads/'));
        
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Upload a file with validation
     * 
     * @param array $file $_FILES array element
     * @param string $prefix Filename prefix
     * @return string Uploaded filename
     * @throws Exception
     */
    public function upload($file, $prefix = 'file_') {
        // 1. Basic check
        if (!isset($file) || !isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception('Yüklenecek dosya seçilmedi.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors = [
                UPLOAD_ERR_INI_SIZE   => 'Dosya sunucu limitini aşıyor (php.ini).',
                UPLOAD_ERR_FORM_SIZE  => 'Dosya form limitini aşıyor.',
                UPLOAD_ERR_PARTIAL    => 'Dosya sadece kısmen yüklendi.',
                UPLOAD_ERR_NO_FILE    => 'Dosya yüklenmedi.',
                UPLOAD_ERR_NO_TMP_DIR => 'Geçici klasör bulunamadı.',
                UPLOAD_ERR_CANT_WRITE => 'Dosya diske yazılamadı.',
                UPLOAD_ERR_EXTENSION  => 'Bir PHP uzantısı dosya yüklemeyi durdurdu.'
            ];
            throw new Exception($errors[$file['error']] ?? 'Bilinmeyen dosya yükleme hatası.');
        }
        
        // 2. Size check
        if ($file['size'] > $this->maxSize) {
            $maxMB = round($this->maxSize / 1048576, 2);
            throw new Exception("Dosya boyutu çok büyük. Maksimum izin verilen: {$maxMB}MB.");
        }
        
        // 3. Extension check
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            throw new Exception("Geçersiz dosya uzantısı. İzin verilenler: " . implode(', ', $this->allowedExtensions));
        }

        // 4. MIME type check (The most critical security check)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $expectedMime = $this->mimeMap[$extension] ?? null;
        if ($expectedMime && $mimeType !== $expectedMime) {
            throw new Exception('Dosya içeriği belirtilen uzantı ile uyuşmuyor.');
        }

        // Extra safety check for PHP files inside allowed extensions (if someone adds it by mistake)
        if (preg_php_content($file['tmp_name'])) {
            throw new Exception('Güvenlik ihlali: Dosya içeriği zararlı kod içeriyor olabilir.');
        }
        
        // 5. Generate safe filename
        $safeFilename = $prefix . bin2hex(random_bytes(8)) . '.' . $extension;
        $destination = rtrim($this->uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $safeFilename;
        
        // 6. Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Dosya sunucuya kaydedilemedi.');
        }
        
        return $safeFilename;
    }
    
    /**
     * Delete a file
     */
    public function delete($filename) {
        $filePath = $this->uploadDir . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
    
    /**
     * Check if file exists
     */
    public function exists($filename) {
        return file_exists($this->uploadDir . $filename);
    }
    
    /**
     * Get file path
     */
    public function getPath($filename) {
        return $this->uploadDir . $filename;
    }
    
    /**
     * Get file URL
     */
    public function getUrl($filename) {
        return url('storage/uploads/' . $filename);
    }
}
