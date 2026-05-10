<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // Logo Settings
            $table->string('logo_type')->default('text'); // 'text' or 'image'
            $table->string('logo_text')->nullable();
            $table->string('logo_image')->nullable();
            
            // Contact & Footer
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('footer_text')->nullable();
            
            // WhatsApp Order Number
            $table->string('whatsapp_number')->nullable();
            
            // Social Links (Optional)
            $table->string('facebook_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
