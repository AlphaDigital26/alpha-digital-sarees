<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivered_at')) $table->timestamp('delivered_at')->nullable();
            
            if (!Schema::hasColumn('orders', 'refund_reason')) $table->string('refund_reason')->nullable();
            if (!Schema::hasColumn('orders', 'refund_custom_reason')) $table->text('refund_custom_reason')->nullable();
            if (!Schema::hasColumn('orders', 'refund_evidence')) $table->json('refund_evidence')->nullable();
            
            if (!Schema::hasColumn('orders', 'refund_requested_at')) $table->timestamp('refund_requested_at')->nullable();
            
            if (!Schema::hasColumn('orders', 'refund_approved_by')) $table->foreignId('refund_approved_by')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('orders', 'refund_approved_at')) $table->timestamp('refund_approved_at')->nullable();
            
            if (!Schema::hasColumn('orders', 'refund_rejected_by')) $table->foreignId('refund_rejected_by')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('orders', 'refund_rejected_at')) $table->timestamp('refund_rejected_at')->nullable();
            if (!Schema::hasColumn('orders', 'refund_rejection_reason')) $table->text('refund_rejection_reason')->nullable();
            
            if (!Schema::hasColumn('orders', 'refund_processed_by')) $table->foreignId('refund_processed_by')->nullable()->constrained('users')->nullOnDelete();
            if (!Schema::hasColumn('orders', 'refund_processed_at')) $table->timestamp('refund_processed_at')->nullable();
            
            if (!Schema::hasColumn('orders', 'stock_restored')) $table->boolean('stock_restored')->default(false);
        });
    }

    public function down(): void
    {
        // Safe rollback logic
    }
};