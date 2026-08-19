<?php

namespace App\Services;

/**
 * File Upload Service
 *
 * Handles secure file uploads with validation.
 */
class FileUploadService
{
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
    ];

    private const MAX_SIZE = 5 * 1024 * 1024; // 5MB

    private string $uploadPath;

    public function __construct(?string $uploadPath = null)
    {
        $this->uploadPath = $uploadPath ?? dirname(__DIR__, 2) . '/public/uploads';
    }

    /**
     * Upload a product image.
     *
     * @return array{success: bool, filename?: string, error?: string}
     */
    public function uploadProductImage(array $file): array
    {
        return $this->upload($file, 'products');
    }

    /**
     * Upload a banner image.
     *
     * @return array{success: bool, filename?: string, error?: string}
     */
    public function uploadBannerImage(array $file): array
    {
        return $this->upload($file, 'banners');
    }

    /**
     * Perform the file upload with validation.
     *
     * @return array{success: bool, filename?: string, error?: string}
     */
    private function upload(array $file, string $subdirectory): array
    {
        // Validate file exists
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload gagal'];
        }

        // Validate MIME type
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED_MIMES, true)) {
            return ['success' => false, 'error' => 'Format file harus JPG atau PNG'];
        }

        // Validate file size
        if ($file['size'] > self::MAX_SIZE) {
            return ['success' => false, 'error' => 'Ukuran file maksimal 5MB'];
        }

        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = time() . '-' . random_int(1000, 9999) . '.' . $extension;

        // Ensure directory exists
        $targetDir = $this->uploadPath . '/' . $subdirectory;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Move the uploaded file
        $targetPath = $targetDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => false, 'error' => 'Gagal menyimpan file'];
        }

        return ['success' => true, 'filename' => $filename];
    }
}
