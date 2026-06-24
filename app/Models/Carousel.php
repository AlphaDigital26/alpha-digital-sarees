<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $fillable = [
        'image',
        'image_mobile',
        'image_tablet',
        'heading',
        'sub_heading',
        'text',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];
    use \App\Traits\OptimizesImages;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function ($model) {
            $dirty = false;

            // Optimize desktop image (landscape 16:9)
            if ($model->image) {
                $newPath = $model->optimizeImageToWebp($model->image, 1920, 1080);
                if ($newPath !== $model->image) {
                    $model->image = $newPath;
                    $dirty = true;
                }
            }

            // Optimize mobile image (portrait 4:5 — 900×1125 px)
            if ($model->image_mobile) {
                $newMobilePath = $model->optimizeImageToWebp($model->image_mobile, 900, 1125);
                if ($newMobilePath !== $model->image_mobile) {
                    $model->image_mobile = $newMobilePath;
                    $dirty = true;
                }
            }

            // Optimize tablet image (landscape 4:3 — 1280×960 px)
            if ($model->image_tablet) {
                $newTabletPath = $model->optimizeImageToWebp($model->image_tablet, 1280, 960);
                if ($newTabletPath !== $model->image_tablet) {
                    $model->image_tablet = $newTabletPath;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $model->saveQuietly();
            }
        });
    }
}