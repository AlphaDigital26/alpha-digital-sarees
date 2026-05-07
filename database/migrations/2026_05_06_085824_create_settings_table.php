<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('site_title')->nullable();
        $table->text('about_us')->nullable();
        $table->boolean('shutdown_mode')->default(false);
        $table->string('address')->nullable();
        $table->string('google_map_link')->nullable();
        $table->string('phone_1')->nullable();
        $table->string('phone_2')->nullable();
        $table->string('email')->nullable();
        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('twitter')->nullable();
        $table->text('iframe_src')->nullable(); // For Google Map embed
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
