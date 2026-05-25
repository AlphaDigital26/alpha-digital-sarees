<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'reason', 
        'message', 
        'is_read'
    ];
}