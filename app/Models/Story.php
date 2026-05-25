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
}