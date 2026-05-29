<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    






    public function uploadCV(UploadedFile $file, string $candidateName): string
    {
        $fileName = $this->generateFileName($candidateName, 'CV', $file->getClientOriginalExtension());
        $path = $file->storeAs('cvs', $fileName, 'public');
        
        return $path;
    }

    






    public function uploadPortfolio(UploadedFile $file, string $candidateName): string
    {
        $fileName = $this->generateFileName($candidateName, 'Portfolio', $file->getClientOriginalExtension());
        $path = $file->storeAs('portfolios', $fileName, 'public');
        
        return $path;
    }

    







    public function uploadDocument(UploadedFile $file, string $candidateName, string $type = 'Document'): string
    {
        $fileName = $this->generateFileName($candidateName, $type, $file->getClientOriginalExtension());
        $path = $file->storeAs('documents', $fileName, 'public');
        
        return $path;
    }

    





    public function deleteFile(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }

    







    protected function generateFileName(string $candidateName, string $type, string $extension): string
    {
        $slug = Str::slug($candidateName);
        $timestamp = now()->format('YmdHis');
        $random = Str::random(6);
        
        return "{$slug}_{$type}_{$timestamp}_{$random}.{$extension}";
    }

    





    public function getFileSize(string $path): string
    {
        if (!Storage::disk('public')->exists($path)) {
            return '0 B';
        }

        $bytes = Storage::disk('public')->size($path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    







    public function validateFile(UploadedFile $file, array $allowedExtensions = ['pdf', 'doc', 'docx'], int $maxSizeMB = 5): array
    {
        $errors = [];

         
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = "File harus berformat: " . implode(', ', $allowedExtensions);
        }

         
        $sizeMB = $file->getSize() / 1024 / 1024;
        if ($sizeMB > $maxSizeMB) {
            $errors[] = "Ukuran file maksimal {$maxSizeMB}MB";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
