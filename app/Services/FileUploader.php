<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class FileUploader
{
    protected $basePath = 'images/admin';

    protected $sizes = [
        'original' => null,
        'thumb'    => [150, 150],
        'medium'   => [400, 300],
        'large'    => [1024, 768],
    ];

    /**
     * Upload image (with SVG support, resizing only raster images)
     */
    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $filename = uniqid() . '.' . strtolower($file->getClientOriginalExtension());
        $isSvg = in_array($file->getClientOriginalExtension(), ['svg', 'svg+xml']);

        foreach ($this->sizes as $size => $dimensions) {
            $path = public_path("{$this->basePath}/{$size}/{$folder}");
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $fullPath = "{$path}/{$filename}";

            if ($size === 'original') {
                $file->move($path, $filename);
            } elseif (!$isSvg) {
                $img = Image::make($file->getRealPath())
                    ->resize($dimensions[0], $dimensions[1], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $img->save($fullPath);
            }
        }

        return "{$folder}/{$filename}";
    }

    /**
     * Delete all image sizes and cached sizes
     */
    public function deleteImage(string $folderedPath): bool
    {
        $deleted = false;

        foreach ($this->sizes as $size => $_) {
            $path = public_path("{$this->basePath}/{$size}/{$folderedPath}");
            if (file_exists($path)) {
                unlink($path);
                $deleted = true;
            }
        }

        // Clean up cache if resized images exist
        $cachePath = public_path('images/cache');
        if (is_dir($cachePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($cachePath, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getFilename() === basename($folderedPath)) {
                    unlink($file->getPathname());
                    $deleted = true;
                }
            }
        }

        return $deleted;
    }

    /**
     * Get the image URL, or generate resized cache if needed
     */
    public function getImageUrl(string $folderedPath, string $size = 'original', int $width = null, int $height = null): string
    {
        $path = "{$this->basePath}/{$size}/{$folderedPath}";
        $fullPath = public_path($path);

        if (!file_exists($fullPath)) {
            return asset('images/no-image.png');
        }

        $extension = pathinfo($folderedPath, PATHINFO_EXTENSION);
        $isSvg = in_array(strtolower($extension), ['svg', 'svg+xml']);

        // For raster images, return resized version if width/height are passed
        if (($width || $height) && !$isSvg) {
            $resizedPath = "images/cache/{$width}x{$height}/{$folderedPath}";
            $fullResizedPath = public_path($resizedPath);

            if (!file_exists($fullResizedPath)) {
                $resizedDir = dirname($fullResizedPath);
                if (!File::exists($resizedDir)) {
                    File::makeDirectory($resizedDir, 0755, true);
                }

                $img = Image::make($fullPath)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($fullResizedPath);
            }

            return asset($resizedPath);
        }

        return asset($path);
    }
}
