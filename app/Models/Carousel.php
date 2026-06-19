<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    protected $fillable = [
        'image',
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
            if ($model->image) {
                $newPath = $model->optimizeImageToWebp($model->image, 1920, 1080);
                if ($newPath !== $model->image) {
                    $model->image = $newPath;
                    $model->saveQuietly();
                }
            }
        });
    }
}