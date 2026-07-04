<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;

class ImageOptimizationService
{
    /**
     * Centralized method to optimize an image, resize it, and optionally generate responsive variants.
     * 
     * @param string $imagePath Relative path on the 'public' disk
     * @param int $maxWidth Max width for the main image
     * @param int $maxHeight Max height for the main image
     * @param array $responsiveWidths Array of widths (e.g. [400, 800]) for responsive sizes
     * @return string The path to the optimized main WebP image
     */
    public function optimizeAndGenerateResponsive(string $imagePath, int $maxWidth = 800, int $maxHeight = 1200, array $responsiveWidths = []): string
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
            
            $pathInfo = pathinfo($imagePath);
            $dir = $pathInfo['dirname'] === '.' ? '' : $pathInfo['dirname'] . '/';
            $filenameWithoutExtension = $pathInfo['filename'];
            
            // Generate responsive variants
            foreach ($responsiveWidths as $width) {
                // Read fresh to avoid mutating the same instance sequentially
                $variantImage = $manager->read($fullPath);
                
                // Scale down keeps aspect ratio and prevents upsizing
                $variantImage->scaleDown(width: $width);
                
                $variantPath = $dir . $filenameWithoutExtension . '-' . $width . 'w.webp';
                $variantFullPath = Storage::disk('public')->path($variantPath);
                
                $variantImage->toWebp(80)->save($variantFullPath);
            }

            // Now optimize the MAIN image
            $image = $manager->read($fullPath);
            $image->scaleDown(width: $maxWidth, height: $maxHeight);

            // New path with .webp extension
            $newPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imagePath);
            $fullNewPath = Storage::disk('public')->path($newPath);

            // Encode and save as WebP with 80% quality
            // Note: Architecture is ready for AVIF if needed in the future
            $image->toWebp(80)->save($fullNewPath);

            // Delete original heavy file if conversion succeeded and it was a different format
            if (file_exists($fullNewPath) && $fullPath !== $fullNewPath) {
                @unlink($fullPath);
            }

            return $newPath;
        } catch (Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage(), [
                'path' => $imagePath
            ]);
            return $imagePath;
        }
    }
}
