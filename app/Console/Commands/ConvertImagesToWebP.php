<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Occasion;
use App\Models\Fabric;

class ConvertImagesToWebP extends Command
{
    protected $signature = 'seo:convert-webp';
    protected $description = 'Convert all existing images to WebP format for better Core Web Vitals';

    public function handle()
    {
        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            $this->error('GD or Imagick extension is required for image conversion.');
            return;
        }

        $this->info('Starting WebP conversion...');

        $this->convertProductInfo();
        $this->convertOccasionInfo();
        $this->convertFabricInfo();

        $this->info('WebP conversion completed successfully.');
    }

    private function convertToWebp($relativePath)
    {
        if (!$relativePath || !Storage::disk('public')->exists($relativePath)) {
            return $relativePath;
        }

        $extension = pathinfo($relativePath, PATHINFO_EXTENSION);
        if (strtolower($extension) === 'webp') {
            return $relativePath;
        }

        $absolutePath = Storage::disk('public')->path($relativePath);
        $newRelativePath = preg_replace('/\.[^.]+$/', '.webp', $relativePath);
        $newAbsolutePath = Storage::disk('public')->path($newRelativePath);

        $image = null;
        switch (strtolower($extension)) {
            case 'jpeg':
            case 'jpg':
                $image = @imagecreatefromjpeg($absolutePath);
                break;
            case 'png':
                $image = @imagecreatefrompng($absolutePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'gif':
                $image = @imagecreatefromgif($absolutePath);
                break;
        }

        if ($image) {
            imagewebp($image, $newAbsolutePath, 85);
            imagedestroy($image);
            return $newRelativePath;
        }
        return $relativePath;
    }

    private function convertProductInfo()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $images = $product->images;
            if (is_array($images)) {
                $newImages = [];
                foreach ($images as $img) {
                    $newImages[] = $this->convertToWebp($img);
                }
                $product->images = $newImages;
                $product->save();
            }
        }
        $this->info('Products processed.');
    }

    private function convertOccasionInfo()
    {
        $occasions = Occasion::all();
        foreach ($occasions as $occasion) {
            $occasion->image = $this->convertToWebp($occasion->image);
            $occasion->save();
        }
        $this->info('Occasions processed.');
    }

    private function convertFabricInfo()
    {
        $fabrics = Fabric::all();
        foreach ($fabrics as $fabric) {
            $fabric->image = $this->convertToWebp($fabric->image);
            $fabric->save();
        }
        $this->info('Fabrics processed.');
    }
}
