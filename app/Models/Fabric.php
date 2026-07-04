<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = [
        'name', 
        'image', 
        'is_featured',
        'slug', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
    ];

    use \App\Traits\OptimizesImages;

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name);
            }
        });

        static::saved(function ($model) {
            if ($model->image) {
                $newPath = $model->optimizeImageToWebp($model->image, 800, 800);
                if ($newPath !== $model->image) {
                    $model->image = $newPath;
                    $model->saveQuietly();
                }
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}