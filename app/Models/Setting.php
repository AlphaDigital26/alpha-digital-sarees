<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'logo_type', 'logo_text', 'logo_image', 
        'contact_email', 'contact_phone', 'contact_address', 
        'footer_text', 'whatsapp_number', 
        'facebook_link', 'instagram_link', 'twitter_link', 'youtube_link',
        'footer_newsletter_text', 'footer_copyright_company', 'footer_brand_heading',
        'footer_image', 'footer_background_image', 'footer_description' // Added background image here
    ];

    public static function getSiteSettings()
    {
        return self::firstOrCreate(
            ['id' => 1], 
            [
                'logo_type' => 'text',
                'logo_text' => 'ALMAARI',
                'contact_email' => 'care@almaari.com',
                'contact_phone' => '+91 22 4567 8910',
                'contact_address' => "No. 42 Heritage Street, Near Opera Park,\nMumbai - 400001, India",
                'footer_text' => 'we are here to make changes',
                'whatsapp_number' => '919876543210',
                
                'footer_newsletter_text' => 'Join the Almaari circle for exclusive previews.',
                'footer_copyright_company' => 'ALPHA DIGITAL PVT. LTD.',
                'footer_brand_heading' => 'ALMAARI by Ankita', 
                
                'footer_image' => null,
                'footer_background_image' => null, // Added default null value
                'footer_description' => null,
                'facebook_link' => null,
                'instagram_link' => null,
                'twitter_link' => null,
                'youtube_link' => null,
            ]
        );
    }
}