<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id', 'first_name', 'last_name', 'company', 
        'address_1', 'address_2', 'city', 'province', 
        'country', 'postal_code', 'phone', 'is_default'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
