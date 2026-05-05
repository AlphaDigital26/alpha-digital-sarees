<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'fabric',
        'price',
        'description',
        'stock',
        'image',
        'is_featured'
    ];
}
