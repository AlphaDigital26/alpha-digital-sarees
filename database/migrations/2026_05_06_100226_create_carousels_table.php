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
            Schema::create('carousels', function (Blueprint $table) {
                $table->id();
                $table->string('image'); // This is the only required field
                $table->string('heading')->nullable();
                $table->string('sub_heading')->nullable();
                $table->text('text')->nullable();
                $table->string('button_text')->nullable();
                $table->string('button_link')->nullable();
                $table->boolean('is_active')->default(true); // Helpful to turn slides on/off
                $table->integer('sort_order')->default(0); // Helpful for rearranging slides
                $table->timestamps();
            });
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousels');
    }
};
