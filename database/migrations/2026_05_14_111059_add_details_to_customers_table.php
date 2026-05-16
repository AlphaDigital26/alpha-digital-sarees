<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->date('dob')->nullable()->after('email');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
            $table->boolean('is_subscribed')->default(false)->after('gender');
            $table->boolean('agreed_to_tos')->default(false)->after('is_subscribed');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 
                'last_name', 
                'dob', 
                'gender', 
                'is_subscribed', 
                'agreed_to_tos'
            ]);
        });
    }
};