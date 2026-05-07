<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    // Add this property to allow the image path to be saved
    protected $fillable = [
        'image',
    ];
}