<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyPage extends Model
{
    protected $fillable = [
        'privacy_policy',
        'terms_of_service',
        'shipping_returns',
        'faqs',
    ];

    protected $casts = [
        'faqs' => 'array',
    ];
}
