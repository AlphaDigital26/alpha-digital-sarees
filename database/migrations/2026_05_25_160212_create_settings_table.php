<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('settings');

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // Branding
            $table->string('logo_type')->default('text');
            $table->string('logo_text')->nullable();
            $table->string('logo_image')->nullable();
            
            // Footer Controls
            $table->string('footer_brand_heading')->nullable();
            $table->string('footer_image')->nullable();
            $table->text('footer_text')->nullable(); // Description
            $table->text('footer_description')->nullable();
            $table->string('footer_newsletter_text')->nullable();
            $table->string('footer_copyright_company')->nullable();
            $table->string('footer_background_image')->nullable();
            
            // Contact & Social
            $table->string('whatsapp_number')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('youtube_link')->nullable();
            
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};