<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Carousel;
use App\Models\Story;
use App\Models\Setting;
use App\Models\Fabric;
use App\Models\Occasion;
use App\Models\Review;
use App\Services\ImageOptimizationService;

class OptimizeAllImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize-all {--dry-run : Only show what would be optimized}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all images across all models, generating WebP and responsive variants.';

    protected ImageOptimizationService $imageService;

    public function __construct(ImageOptimizationService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY-RUN mode. No changes will be made.');
        }

        $this->info('Starting global image optimization process...');

        $this->processProducts($dryRun);
        $this->processCarousels($dryRun);
        $this->processStories($dryRun);
        $this->processSettings($dryRun);
        $this->processFabrics($dryRun);
        $this->processOccasions($dryRun);
        $this->processReviews($dryRun);

        $this->info('Image optimization process completed.');
    }

    protected function processProducts(bool $dryRun)
    {
        $products = Product::all();
        $this->info("Processing {$products->count()} Products...");
        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            if (!empty($product->images) && is_array($product->images)) {
                $newImages = [];
                $changed = false;

                foreach ($product->images as $image) {
                    if (!$dryRun) {
                        $newPath = $this->imageService->optimizeAndGenerateResponsive($image, 800, 1200, [400, 800]);
                        $newImages[] = $newPath;
                        if ($newPath !== $image) {
                            $changed = true;
                        }
                    } else {
                        $newImages[] = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image);
                    }
                }

                if (!$dryRun && $changed) {
                    $product->images = $newImages;
                    $product->saveQuietly();
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processCarousels(bool $dryRun)
    {
        $carousels = Carousel::all();
        $this->info("Processing {$carousels->count()} Carousels...");
        $bar = $this->output->createProgressBar($carousels->count());

        foreach ($carousels as $carousel) {
            $changed = false;

            if ($carousel->image && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($carousel->image, 1920, 1080);
                if ($newPath !== $carousel->image) {
                    $carousel->image = $newPath;
                    $changed = true;
                }
            }

            if ($carousel->image_mobile && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($carousel->image_mobile, 900, 1125);
                if ($newPath !== $carousel->image_mobile) {
                    $carousel->image_mobile = $newPath;
                    $changed = true;
                }
            }

            if ($carousel->image_tablet && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($carousel->image_tablet, 1280, 960);
                if ($newPath !== $carousel->image_tablet) {
                    $carousel->image_tablet = $newPath;
                    $changed = true;
                }
            }

            if (!$dryRun && $changed) {
                $carousel->saveQuietly();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processStories(bool $dryRun)
    {
        $stories = Story::all();
        $this->info("Processing {$stories->count()} Stories...");
        $bar = $this->output->createProgressBar($stories->count());

        foreach ($stories as $story) {
            $changed = false;

            $imageFields1920 = [
                'main_image', 'control_image_1', 'control_image_2', 'control_image_3',
                'journey_img_1', 'journey_img_2', 'journey_img_3', 'journey_img_4'
            ];

            foreach ($imageFields1920 as $field) {
                if ($story->{$field} && !$dryRun) {
                    $newPath = $this->imageService->optimizeAndGenerateResponsive($story->{$field}, 1920, 1080);
                    if ($newPath !== $story->{$field}) {
                        $story->{$field} = $newPath;
                        $changed = true;
                    }
                }
            }

            if ($story->main_image_mobile && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($story->main_image_mobile, 900, 1125);
                if ($newPath !== $story->main_image_mobile) {
                    $story->main_image_mobile = $newPath;
                    $changed = true;
                }
            }

            if ($story->main_image_tablet && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($story->main_image_tablet, 1280, 960);
                if ($newPath !== $story->main_image_tablet) {
                    $story->main_image_tablet = $newPath;
                    $changed = true;
                }
            }

            if (!$dryRun && $changed) {
                $story->saveQuietly();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processSettings(bool $dryRun)
    {
        $settings = Setting::all();
        $this->info("Processing {$settings->count()} Settings...");
        $bar = $this->output->createProgressBar($settings->count());

        foreach ($settings as $setting) {
            $changed = false;

            if ($setting->logo_image && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($setting->logo_image, 600, 600);
                if ($newPath !== $setting->logo_image) {
                    $setting->logo_image = $newPath;
                    $changed = true;
                }
            }

            if ($setting->footer_background_image && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($setting->footer_background_image, 1920, 1080);
                if ($newPath !== $setting->footer_background_image) {
                    $setting->footer_background_image = $newPath;
                    $changed = true;
                }
            }

            if (!$dryRun && $changed) {
                $setting->saveQuietly();
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processFabrics(bool $dryRun)
    {
        $fabrics = Fabric::all();
        $this->info("Processing {$fabrics->count()} Fabrics...");
        $bar = $this->output->createProgressBar($fabrics->count());

        foreach ($fabrics as $fabric) {
            if ($fabric->image && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($fabric->image, 800, 800, [400, 800]);
                if ($newPath !== $fabric->image) {
                    $fabric->image = $newPath;
                    $fabric->saveQuietly();
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processOccasions(bool $dryRun)
    {
        $occasions = Occasion::all();
        $this->info("Processing {$occasions->count()} Occasions...");
        $bar = $this->output->createProgressBar($occasions->count());

        foreach ($occasions as $occasion) {
            if ($occasion->image && !$dryRun) {
                $newPath = $this->imageService->optimizeAndGenerateResponsive($occasion->image, 800, 800, [400, 800]);
                if ($newPath !== $occasion->image) {
                    $occasion->image = $newPath;
                    $occasion->saveQuietly();
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine();
    }

    protected function processReviews(bool $dryRun)
    {
        // Try to fetch reviews, gracefully handle if table doesn't exist
        try {
            $reviews = Review::all();
            $this->info("Processing {$reviews->count()} Reviews...");
            $bar = $this->output->createProgressBar($reviews->count());

            foreach ($reviews as $review) {
                if (!empty($review->photos) && is_array($review->photos)) {
                    $newPhotos = [];
                    $changed = false;

                    foreach ($review->photos as $photo) {
                        if (!$dryRun) {
                            $newPath = $this->imageService->optimizeAndGenerateResponsive($photo, 800, 800);
                            $newPhotos[] = $newPath;
                            if ($newPath !== $photo) {
                                $changed = true;
                            }
                        } else {
                            $newPhotos[] = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $photo);
                        }
                    }

                    if (!$dryRun && $changed) {
                        $review->photos = $newPhotos;
                        $review->saveQuietly();
                    }
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
        } catch (\Exception $e) {
            $this->warn("Could not process reviews: " . $e->getMessage());
        }
    }
}
