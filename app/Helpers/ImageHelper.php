<?php 
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageHelper
{
    /**
     * Upload and store an image in multiple sizes
     */
    public static function uploadImage($file, $folder = 'default')
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "images/admin/{$folder}";

        // Original
        $file->move(public_path($path . '/original'), $filename);

        // Create resized versions
        $img = Image::make(public_path("$path/original/$filename"));

        // Thumbnail
        $img->resize(150, 150)->save(public_path("$path/thumb/$filename"));

        // Medium
        $img->resize(400, 300)->save(public_path("$path/medium/$filename"));

        // Large
        $img->resize(1024, 768)->save(public_path("$path/large/$filename"));

        return "$folder/$filename"; // stored relative path
    }

    /**
     * Get the image URL with fallback
     */
    public static function getImage($folderedPath, $size = 'original', $width = null, $height = null)
    {
        $basePath = public_path("images/admin/{$size}/" . $folderedPath);
        $url = asset("images/admin/{$size}/" . $folderedPath);

        if (!file_exists($basePath)) {
            return asset('images/no-image.png'); // fallback image
        }

        // Dynamically resize and cache on the fly (if needed)
        if ($width || $height) {
            $resizedPath = "images/cache/{$width}x{$height}/" . $folderedPath;
            $fullResizedPath = public_path($resizedPath);

            if (!file_exists($fullResizedPath)) {
                $img = Image::make($basePath)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($fullResizedPath);
            }

            return asset($resizedPath);
        }

        return $url;
    }

    public static function deleteImage($folderedPath)
    {
        $sizes = ['original', 'thumb', 'medium', 'large'];
        $deleted = false;

        foreach ($sizes as $size) {
            $path = public_path("images/admin/{$size}/{$folderedPath}");
            if (file_exists($path)) {
                unlink($path);
                $deleted = true;
            }
        }

    // Optionally delete cached resized versions
        $cachePath = public_path("images/cache");
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

}

?>