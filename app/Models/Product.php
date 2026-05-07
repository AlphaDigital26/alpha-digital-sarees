<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'fabric',
        'current_price',
        'original_price',
        'stock',
        'image',
        'is_new',
        'is_best_seller',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
    ];
}