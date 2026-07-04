<?php

namespace App\Traits;

use App\Services\ImageOptimizationService;

trait OptimizesImages
{
    /**
     * Optimizes a single image file to WebP format if it's a JPG/PNG.
     * Also resizes large images to a maximum dimension and generates responsive variants.
     * Returns the new file path relative to the storage disk.
     */
    public function optimizeImageToWebp($imagePath, $maxWidth = 800, $maxHeight = 1200)
    {
        $responsiveWidths = [];
        
        // Generate responsive variants for these specific models
        if (in_array(class_basename($this), ['Product', 'Fabric', 'Occasion'])) {
            $responsiveWidths = [400, 800];
        }

        /** @var ImageOptimizationService $service */
        $service = app(ImageOptimizationService::class);
        
        return $service->optimizeAndGenerateResponsive($imagePath, $maxWidth, $maxHeight, $responsiveWidths);
    }
}
