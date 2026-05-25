<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'dob',
        'gender',
        'is_subscribed',
        'agreed_to_tos',
        'is_active', 
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
        'agreed_to_tos' => 'boolean',
        'dob' => 'date',
    ];
}