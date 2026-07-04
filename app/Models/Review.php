<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['customer_id', 'product_id', 'rating', 'comment', 'is_read', 'admin_reply', 'photos'];

    protected $casts = [
        'photos' => 'array',
    ];

    use \App\Traits\OptimizesImages;

    protected static function booted()
    {
        static::saved(function ($model) {
            if (!empty($model->photos) && is_array($model->photos)) {
                $newPhotos = [];
                $changed = false;

                foreach ($model->photos as $photo) {
                    $newPath = $model->optimizeImageToWebp($photo, 800, 800);
                    $newPhotos[] = $newPath;
                    if ($newPath !== $photo) {
                        $changed = true;
                    }
                }

                if ($changed) {
                    $model->photos = $newPhotos;
                    $model->saveQuietly();
                }
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
