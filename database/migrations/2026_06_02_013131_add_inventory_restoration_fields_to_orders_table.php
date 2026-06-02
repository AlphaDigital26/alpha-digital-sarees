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
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('stock_restored_at')->nullable()->after('stock_restored');
            $table->foreignId('stock_restored_by')->nullable()->constrained('users')->nullOnDelete()->after('stock_restored_at');
            $table->timestamp('return_received_at')->nullable()->after('stock_restored_by');
            $table->foreignId('return_verified_by')->nullable()->constrained('users')->nullOnDelete()->after('return_received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['stock_restored_by']);
            $table->dropForeign(['return_verified_by']);
            $table->dropColumn([
                'stock_restored_at',
                'stock_restored_by',
                'return_received_at',
                'return_verified_by',
            ]);
        });
    }
};
