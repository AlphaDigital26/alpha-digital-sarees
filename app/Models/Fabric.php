<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fabric extends Model
{
    protected $fillable = [
        'name', 
        'image', 
        'is_featured'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}