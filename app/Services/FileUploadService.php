<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class FileUploadService
{
    // Allowed image MIME types with their magic bytes signatures
    private const ALLOWED_IMAGE_TYPES = [
        'image/jpeg' => [
            [0xFF, 0xD8, 0xFF],
        ],
        'image/png' => [
            [0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A],
        ],
        'image/gif' => [
            [0x47, 0x49, 0x46, 0x38, 0x37, 0x61],
            [0x47, 0x49, 0x46, 0x38, 0x39, 0x61],
        ],
        'image/webp' => [
            // RIFF....WEBP
            null, // checked separately
        ],
    ];

    private const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar',
        'asp', 'aspx', 'jsp', 'jspx', 'cfm', 'cfc',
        'sh', 'bash', 'zsh', 'ksh', 'csh',
        'py', 'rb', 'pl', 'lua',
        'exe', 'dll', 'so', 'bat', 'cmd', 'com',
        'htaccess', 'htpasswd', 'ini', 'conf',
        'svg', // SVGs can contain embedded JS / XXE
        'xml', // XXE attacks
        'html', 'htm', 'xhtml',
        'js', 'ts', 'vbs', 'wsf',
    ];

    // Max file size: 5MB
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;

    // Max image dimensions to prevent decompression bombs
    private const MAX_IMAGE_WIDTH  = 8000;
    private const MAX_IMAGE_HEIGHT = 8000;

    /**
     * Validate and store an uploaded image securely.
     *
     * @param  UploadedFile  $file
     * @param  string        $directory  e.g. 'avatars', 'logos'
     * @param  string        $disk       filesystem disk name
     * @return string        stored path relative to disk root
     * @throws ValidationException
     */
    public function storeImage(UploadedFile $file, string $directory = 'uploads', string $disk = 'uploads'): string
    {
        $this->validateFile($file);
        $this->validateMagicBytes($file);
        $this->validateImageIntegrity($file);

        // Generate cryptographically random filename — never trust original name
        $extension = $this->getSafeExtension($file);
        $filename  = $directory . '/' . Str::random(40) . '.' . $extension;

        Storage::disk($disk)->put($filename, file_get_contents($file->getRealPath()));

        return $filename;
    }

    /**
     * Delete a stored file.
     */
    public function delete(?string $path, string $disk = 'uploads'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    // ─── Private Validators ────────────────────────────────────────────────────

    private function validateFile(UploadedFile $file): void
    {
        // Check file was actually uploaded (prevent path traversal via move_uploaded_file bypass)
        if (!$file->isValid()) {
            throw ValidationException::withMessages(['file' => 'File upload failed.']);
        }

        // Size check (defence against decompression bombs before we open the image)
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw ValidationException::withMessages(['file' => 'File size exceeds 5MB limit.']);
        }

        // Block dangerous extensions — even if MIME says "image"
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, self::DANGEROUS_EXTENSIONS, true)) {
            throw ValidationException::withMessages(['file' => 'File type not permitted.']);
        }

        // Check for double extensions: shell.php.jpg
        $originalName = strtolower($file->getClientOriginalName());
        foreach (self::DANGEROUS_EXTENSIONS as $ext) {
            if (str_contains($originalName, '.' . $ext . '.')) {
                throw ValidationException::withMessages(['file' => 'File type not permitted.']);
            }
        }
    }

    private function validateMagicBytes(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            throw ValidationException::withMessages(['file' => 'Cannot read uploaded file.']);
        }
        $header = fread($handle, 12);
        fclose($handle);

        $bytes = array_values(unpack('C*', $header));

        // Server-side MIME via finfo — not trusting client Content-Type
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getRealPath());

        if (!array_key_exists($mimeType, self::ALLOWED_IMAGE_TYPES)) {
            throw ValidationException::withMessages(['file' => 'Only JPEG, PNG, GIF, and WebP images are allowed.']);
        }

        // Magic bytes check per MIME type
        $valid = false;
        if ($mimeType === 'image/webp') {
            // RIFF at offset 0 and WEBP at offset 8
            $valid = (
                $bytes[0] === 0x52 && $bytes[1] === 0x49 && $bytes[2] === 0x46 && $bytes[3] === 0x46 &&
                $bytes[8] === 0x57 && $bytes[9] === 0x45 && $bytes[10] === 0x42 && $bytes[11] === 0x50
            );
        } else {
            foreach (self::ALLOWED_IMAGE_TYPES[$mimeType] as $signature) {
                if ($signature === null) {
                    continue;
                }
                $match = true;
                foreach ($signature as $i => $byte) {
                    if (!isset($bytes[$i]) || $bytes[$i] !== $byte) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $valid = true;
                    break;
                }
            }
        }

        if (!$valid) {
            throw ValidationException::withMessages(['file' => 'File content does not match its type. Upload rejected.']);
        }
    }

    private function validateImageIntegrity(UploadedFile $file): void
    {
        // Use GD to verify the file is a real, non-corrupted image
        // This also protects against decompression bombs (memory limit via ini)
        $originalMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '64M'); // Cap memory for this operation

        try {
            // getimagesize doesn't fully decode, use it for dimension check first
            $imageInfo = @getimagesize($file->getRealPath());

            if ($imageInfo === false) {
                throw ValidationException::withMessages(['file' => 'Invalid image file.']);
            }

            [$width, $height] = $imageInfo;

            if ($width > self::MAX_IMAGE_WIDTH || $height > self::MAX_IMAGE_HEIGHT) {
                throw ValidationException::withMessages(['file' => 'Image dimensions too large (max 8000×8000).']);
            }

            // Create image resource via GD to verify integrity
            $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));

            if ($image === false) {
                throw ValidationException::withMessages(['file' => 'Image file appears corrupted or contains invalid data.']);
            }

            imagedestroy($image);
        } finally {
            ini_set('memory_limit', $originalMemoryLimit);
        }
    }

    private function getSafeExtension(UploadedFile $file): string
    {
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getRealPath());

        return match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
            default      => 'bin',
        };
    }
}
