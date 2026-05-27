<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'dob',
        'gender',
        'is_subscribed',
        'agreed_to_tos',
        'is_active', 
    ];

    protected $hidden = [
        'password',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    protected $casts = [
        'is_subscribed' => 'boolean',
        'agreed_to_tos' => 'boolean',
        'dob' => 'date',
    ];
}