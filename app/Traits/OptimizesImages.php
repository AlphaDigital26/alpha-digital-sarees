<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait OptimizesImages
{
    /**
     * Optimizes a single image file to WebP format if it's a JPG/PNG.
     * Also resizes large images to a maximum dimension.
     * Returns the new file path relative to the storage disk.
     */
    public function optimizeImageToWebp($imagePath, $maxWidth = 800, $maxHeight = 1200)
    {
        // Only process jpg, jpeg, png, webp
        if (!preg_match('/\.(jpg|jpeg|png|webp)$/i', $imagePath)) {
            return $imagePath;
        }

        $fullPath = Storage::disk('public')->path($imagePath);

        // Make sure the file actually exists
        if (!file_exists($fullPath)) {
            return $imagePath;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            
            // Resize to max dimensions while keeping aspect ratio. 
            // If it's already smaller, scaleDown won't upsize it.
            $image->scaleDown($maxWidth, $maxHeight);

            // New path with .webp extension
            $newPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imagePath);
            $fullNewPath = Storage::disk('public')->path($newPath);

            // Encode and save as WebP with 80% quality
            $image->toWebp(80)->save($fullNewPath);

            // Delete original heavy file if conversion succeeded and it was a different format
            if (file_exists($fullNewPath) && $fullPath !== $fullNewPath) {
                unlink($fullPath);
            }

            return $newPath;
        } catch (\Exception $e) {
            // If anything goes wrong, return original path so nothing breaks
            \Log::error('Image optimization failed: ' . $e->getMessage());
            return $imagePath;
        }
    }
}
