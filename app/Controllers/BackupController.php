<?php
/**
 * Backup Controller
 */

namespace App\Controllers;

class BackupController extends Controller {
    
    protected $backupPath;
    protected $encryptionKey;
    
    public function __construct($route_params) {
        parent::__construct($route_params);
        $config = require CONFIG_PATH . '/app.php';
        $this->backupPath = $config['backup_path'];
        $this->encryptionKey = $config['backup_encryption_key'];
        
        if (!is_dir($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }
    
    public function index() {
        $this->checkPermission('backup.view');
        
        $backups = $this->getBackupsList();
        $settings = $this->getBackupSettings();
        
        $this->view('backup/index', [
            'backups' => $backups,
            'settings' => $settings,
            'title' => 'النسخ الاحتياطي والاستعادة'
        ]);
    }
    
    public function create() {
        $this->checkPermission('backup.create');
        $this->validateMethod('POST');
        
        try {
            $config = require CONFIG_PATH . '/database.php';
            $timestamp = date('Y-m-d_H-i-s');
            $backupName = "backup_{$timestamp}";
            $tempDir = sys_get_temp_dir() . '/' . $backupName;
            
            // Create temp directory
            mkdir($tempDir, 0755, true);
            mkdir($tempDir . '/uploads', 0755, true);
            
            // Generate SQL dump
            $sqlFile = $tempDir . '/database.sql';
            $this->createDatabaseDump($config, $sqlFile);
            
            // Copy uploads
            $uploadPath = PUBLIC_PATH . '/assets/uploads';
            if (is_dir($uploadPath)) {
                $this->copyDirectory($uploadPath, $tempDir . '/uploads');
            }
            
            // Create ZIP file
            $zipFile = $this->backupPath . '/' . $backupName . '.zip';
            $this->createZipArchive($tempDir, $zipFile);
            
            // Encrypt ZIP if enabled
            $settings = $this->getBackupSettings();
            if ($settings['backup_encryption']) {
                $encryptedFile = $zipFile . '.enc';
                $this->encryptFile($zipFile, $encryptedFile);
                unlink($zipFile);
                $zipFile = $encryptedFile;
            }
            
            // Clean up temp directory
            $this->removeDirectory($tempDir);
            
            // Log backup
            $this->db->query(
                "INSERT INTO backup_logs (file_name, file_path, file_size, backup_type, includes_files, created_by) 
                 VALUES (?, ?, ?, 'manual', 1, ?)",
                [basename($zipFile), $zipFile, filesize($zipFile), $this->auth->id()]
            );
            
            // Apply retention policy
            $this->applyRetentionPolicy($settings['backup_retention_days']);
            
            $this->setFlash('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'حدث خطأ: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/backup');
    }
    
    public function download($filename) {
        $this->checkPermission('backup.view');
        
        $file = $this->backupPath . '/' . basename($filename);
        
        if (!file_exists($file)) {
            $this->setFlash('error', 'الملف غير موجود');
            $this->redirect(BASE_URL . '/backup');
        }
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: no-cache, must-revalidate');
        
        readfile($file);
        exit;
    }
    
    public function delete($filename) {
        $this->checkPermission('backup.delete');
        
        $file = $this->backupPath . '/' . basename($filename);
        
        if (file_exists($file)) {
            unlink($file);
            $this->db->query("DELETE FROM backup_logs WHERE file_name = ?", [basename($filename)]);
            $this->json(['success' => true, 'message' => 'تم حذف النسخة الاحتياطية']);
        } else {
            $this->json(['success' => false, 'message' => 'الملف غير موجود']);
        }
    }
    
    public function restore() {
        $this->checkPermission('backup.restore');
        $this->validateMethod('POST');
        
        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] != 0) {
            $this->setFlash('error', 'الرجاء اختيار ملف النسخة الاحتياطية');
            $this->redirect(BASE_URL . '/backup');
        }
        
        try {
            $uploadedFile = $_FILES['backup_file']['tmp_name'];
            $originalName = $_FILES['backup_file']['name'];
            $tempDir = sys_get_temp_dir() . '/restore_' . time();
            
            mkdir($tempDir, 0755, true);
            
            // Decrypt if needed
            if (substr($originalName, -4) === '.enc') {
                $decryptedFile = $tempDir . '/backup.zip';
                $this->decryptFile($uploadedFile, $decryptedFile);
                $uploadedFile = $decryptedFile;
            }
            
            // Extract ZIP
            $zip = new \ZipArchive();
            if ($zip->open($uploadedFile) === TRUE) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                throw new \Exception('فشل في فتح ملف ZIP');
            }
            
            // Restore database
            $config = require CONFIG_PATH . '/database.php';
            $this->restoreDatabase($config, $tempDir . '/database.sql');
            
            // Restore uploads
            if (is_dir($tempDir . '/uploads')) {
                $uploadPath = PUBLIC_PATH . '/assets/uploads';
                $this->copyDirectory($tempDir . '/uploads', $uploadPath);
            }
            
            // Clean up
            $this->removeDirectory($tempDir);
            
            $this->setFlash('success', 'تم استعادة النسخة الاحتياطية بنجاح');
            
        } catch (\Exception $e) {
            $this->setFlash('error', 'حدث خطأ أثناء الاستعادة: ' . $e->getMessage());
        }
        
        $this->redirect(BASE_URL . '/backup');
    }
    
    public function settings() {
        $this->checkPermission('backup.view');
        $this->validateMethod('POST');
        
        $settings = [
            'backup_enabled' => $this->getPost('backup_enabled') ? 1 : 0,
            'backup_frequency' => $this->getPost('backup_frequency'),
            'backup_time' => $this->getPost('backup_time'),
            'backup_retention_days' => intval($this->getPost('backup_retention_days')),
            'backup_encryption' => $this->getPost('backup_encryption') ? 1 : 0
        ];
        
        foreach ($settings as $key => $value) {
            $this->db->query(
                "UPDATE settings SET value = ? WHERE `key` = ?",
                [$value, $key]
            );
        }
        
        $this->setFlash('success', 'تم حفظ الإعدادات بنجاح');
        $this->redirect(BASE_URL . '/backup');
    }
    
    /**
     * Create database dump
     */
    protected function createDatabaseDump($config, $outputFile) {
        $command = sprintf(
            'mysqldump -h %s -u %s %s %s > %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            $config['password'] ? '-p' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            escapeshellarg($outputFile)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('فشل في إنشاء نسخة من قاعدة البيانات');
        }
    }
    
    /**
     * Restore database from SQL file
     */
    protected function restoreDatabase($config, $sqlFile) {
        $command = sprintf(
            'mysql -h %s -u %s %s %s < %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            $config['password'] ? '-p' . escapeshellarg($config['password']) : '',
            escapeshellarg($config['database']),
            escapeshellarg($sqlFile)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception('فشل في استعادة قاعدة البيانات');
        }
    }
    
    /**
     * Create ZIP archive
     */
    protected function createZipArchive($source, $destination) {
        $zip = new \ZipArchive();
        
        if ($zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception('فشل في إنشاء ملف ZIP');
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        
        $zip->close();
    }
    
    /**
     * Encrypt file using AES-256
     */
    protected function encryptFile($inputFile, $outputFile) {
        $data = file_get_contents($inputFile);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16));
        file_put_contents($outputFile, $encrypted);
    }
    
    /**
     * Decrypt file using AES-256
     */
    protected function decryptFile($inputFile, $outputFile) {
        $data = file_get_contents($inputFile);
        $decrypted = openssl_decrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16));
        file_put_contents($outputFile, $decrypted);
    }
    
    /**
     * Get backups list
     */
    protected function getBackupsList() {
        $backups = [];
        
        if (is_dir($this->backupPath)) {
            $files = glob($this->backupPath . '/*');
            
            foreach ($files as $file) {
                if (is_file($file)) {
                    $backups[] = [
                        'filename' => basename($file),
                        'size' => filesize($file),
                        'created_at' => date('Y-m-d H:i:s', filemtime($file))
                    ];
                }
            }
        }
        
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $backups;
    }
    
    /**
     * Get backup settings
     */
    protected function getBackupSettings() {
        $settings = $this->db->query(
            "SELECT * FROM settings WHERE `key` LIKE 'backup%'"
        )->fetchAll();
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }
    
    /**
     * Apply retention policy
     */
    protected function applyRetentionPolicy($days) {
        $cutoffDate = strtotime("-$days days");
        $backups = $this->getBackupsList();
        
        foreach ($backups as $backup) {
            if (strtotime($backup['created_at']) < $cutoffDate) {
                $file = $this->backupPath . '/' . $backup['filename'];
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
    
    /**
     * Copy directory recursively
     */
    protected function copyDirectory($source, $destination) {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $destPath = $destination . '/' . $iterator->getSubPathName();
            
            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                copy($item->getPathname(), $destPath);
            }
        }
    }
    
    /**
     * Remove directory recursively
     */
    protected function removeDirectory($directory) {
        if (!is_dir($directory)) {
            return;
        }
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        
        rmdir($directory);
    }
}