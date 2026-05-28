<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'favicon_image',
        'logo_type', 
        'logo_text', 
        'logo_image', 
        'footer_brand_heading', 
        'footer_text', 
        'footer_description',
        'footer_newsletter_text', 
        'footer_copyright_company', 
        'footer_background_image',
        'whatsapp_number', 
        'contact_email', 
        'contact_phone', 
        'contact_address',
        'facebook_link', 
        'instagram_link', 
        'twitter_link', 
        'youtube_link',
        'policy_privacy_link', 
        'terms_service_link', 
        'shipping_returns_link'
    ];

    public static function getSiteSettings()
    {
        return self::firstOrCreate(['id' => 1]);
    }
}