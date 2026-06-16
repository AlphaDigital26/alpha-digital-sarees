<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'specifications', 'care_instructions', // CHANGED
        'current_price', 'original_price', 'fabric_id', 'color_id', 'pattern_id', 
        'occasion', 'stock', 'images', 'is_new', 'is_best_seller',
        'slug', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name);
            }
        });
    }
    

    protected $casts = [
        'images' => 'array',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    // ADDED: Relationships
    public function fabric() {
        return $this->belongsTo(Fabric::class);
    }
    public function color() {
        return $this->belongsTo(Color::class);
    }
    public function pattern() {
        return $this->belongsTo(Pattern::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}