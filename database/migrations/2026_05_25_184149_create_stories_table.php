<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('stories'); // Ensure a clean slate
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            // Hero
            $table->string('main_image')->nullable();
            $table->string('main_heading')->nullable();
            $table->text('para_1')->nullable();
            // Craftsmanship
            $table->string('control_image_1')->nullable();
            $table->string('control_image_2')->nullable();
            $table->string('heading_2')->nullable();
            $table->text('para_2')->nullable();
            // Bottom/Journey
            $table->string('heading_3')->nullable();
            $table->text('text_3')->nullable();
            $table->string('control_image_3')->nullable();
            $table->string('journey_img_1')->nullable();
            $table->string('journey_img_2')->nullable();
            $table->string('journey_img_3')->nullable();
            $table->string('journey_img_4')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('stories'); }
};