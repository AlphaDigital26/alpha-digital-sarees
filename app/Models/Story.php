<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = [
        'main_image', 'main_heading', 'para_1', 'control_image_1', 'control_image_2', 
        'heading_2', 'para_2', 'heading_3', 'text_3', 'control_image_3',
        'journey_img_1', 'journey_img_2', 'journey_img_3', 'journey_img_4'
    ];

    use \App\Traits\OptimizesImages;

    protected static function booted()
    {
        static::saved(function ($model) {
            $changed = false;
            
            $imageFields = [
                'main_image', 'control_image_1', 'control_image_2', 'control_image_3',
                'journey_img_1', 'journey_img_2', 'journey_img_3', 'journey_img_4'
            ];

            foreach ($imageFields as $field) {
                if ($model->{$field}) {
                    $newPath = $model->optimizeImageToWebp($model->{$field}, 1920, 1080);
                    if ($newPath !== $model->{$field}) {
                        $model->{$field} = $newPath;
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                $model->saveQuietly();
            }
        });
    }
}