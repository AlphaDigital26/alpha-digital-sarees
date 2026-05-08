<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logo_type', 'logo_text', 'logo_image', 
        'contact_email', 'contact_phone', 'contact_address', 
        'footer_text', 'whatsapp_number', 
        'facebook_link', 'instagram_link'
    ];

    // This method guarantees 1 row always exists in the database
    public static function getSiteSettings()
    {
        return self::firstOrCreate(
            ['id' => 1], // Always look for or create ID 1
            [
                // These defaults will save to the database the very first time the app runs
                'logo_type' => 'text',
                'logo_text' => 'ALMAARI',
                'contact_email' => 'care@almaari.com',
                'contact_phone' => '+91 22 4567 8910',
                'contact_address' => "No. 42 Heritage Street, Near Opera Park,\nMumbai - 400001, India",
                'footer_text' => 'Founded on the principles of preserving traditional Indian handlooms, ALMAARI brings you curated handlooms that tell a story of artisanal mastery and timeless elegance.',
                'whatsapp_number' => '919876543210',
            ]
        );
    }
}