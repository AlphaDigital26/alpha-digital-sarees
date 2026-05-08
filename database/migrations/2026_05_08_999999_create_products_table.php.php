<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('specifications')->nullable();
            $table->text('care_instructions')->nullable();
            $table->decimal('current_price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->foreignId('fabric_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pattern_id')->nullable()->constrained()->nullOnDelete();
            $table->string('occasion')->nullable();
            $table->integer('stock')->default(0);
            $table->json('images')->nullable(); 
            $table->boolean('is_new')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->timestamps();
        });
    }
                
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};