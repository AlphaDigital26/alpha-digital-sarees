<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Change "extends Page" to "extends Model"
class Setting extends Model
{
    // This allows the form to save all the fields we created in the migration
    protected $fillable = [
        'site_title',
        'about_us',
        'shutdown_mode',
        'address',
        'google_map_link',
        'phone_1',
        'phone_2',
        'email',
        'facebook',
        'instagram',
        'twitter',
        'iframe_src',
    ];
}