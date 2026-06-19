<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Carousel;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts all existing jpg/png images in Products and Carousels to webp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting image optimization process...');

        $products = Product::all();
        $productCount = 0;

        foreach ($products as $product) {
            // By simply triggering a fake "update" with the same images,
            // the `saved` event hook with the OptimizesImages trait will kick in
            // and magically compress all images in the array.
            // Wait, actually, the saved event looks at $model->images and checks if it can optimize it.
            // We just need to trigger a save. Since we use `saveQuietly` inside the saved event,
            // we can just call `save()` here. BUT `save()` only triggers if the model is dirty.
            // So we explicitly mark it dirty or just call the trait method manually to be safe.
            $changed = false;
            $processedCount = 0;
            $images = $product->images ?? [];
            if (is_array($images)) {
                foreach ($images as $key => $imagePath) {
                    $newPath = $product->optimizeImageToWebp($imagePath);
                    $processedCount++;
                    if ($newPath !== $imagePath) {
                        $images[$key] = $newPath;
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                $product->images = array_values($images);
                $product->saveQuietly();
            }

            if ($processedCount > 0) {
                $productCount++;
                $this->line("Processed & Resized images for Product ID: {$product->id}");
            }
        }

        $carousels = Carousel::all();
        $carouselCount = 0;

        foreach ($carousels as $carousel) {
            if ($carousel->image) {
                $newPath = $carousel->optimizeImageToWebp($carousel->image);
                if ($newPath !== $carousel->image) {
                    $carousel->image = $newPath;
                    $carousel->saveQuietly();
                }
                $carouselCount++;
                $this->line("Processed & Resized image for Carousel ID: {$carousel->id}");
            }
        }

        $settings = \App\Models\Setting::all();
        $settingCount = 0;

        foreach ($settings as $setting) {
            $changed = false;
            
            if ($setting->logo_image) {
                $newLogoPath = $setting->optimizeImageToWebp($setting->logo_image);
                if ($newLogoPath !== $setting->logo_image) {
                    $setting->logo_image = $newLogoPath;
                    $changed = true;
                }
            }

            if ($setting->footer_background_image) {
                $newFooterBg = $setting->optimizeImageToWebp($setting->footer_background_image);
                if ($newFooterBg !== $setting->footer_background_image) {
                    $setting->footer_background_image = $newFooterBg;
                    $changed = true;
                }
            }

            if ($changed) {
                $setting->saveQuietly();
            }

            // We count as processed if it had images to check
            if ($setting->logo_image || $setting->footer_background_image) {
                $settingCount++;
                $this->line("Processed & Resized images for Setting ID: {$setting->id}");
            }
        }

        $this->info("Successfully optimized images for {$productCount} Products, {$carouselCount} Carousels, and {$settingCount} Settings.");
    }
}
