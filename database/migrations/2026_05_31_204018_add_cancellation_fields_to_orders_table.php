<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable();
            }
            if (!Schema::hasColumn('orders', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable();
            }
            if (!Schema::hasColumn('orders', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable();
            }
            if (!Schema::hasColumn('orders', 'cancelled_by_role')) {
                $table->string('cancelled_by_role')->nullable(); // 'admin' or 'customer'
            }
            if (!Schema::hasColumn('orders', 'refund_required')) {
                $table->boolean('refund_required')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('orders', 'cancelled_at')) $columns[] = 'cancelled_at';
            if (Schema::hasColumn('orders', 'cancelled_by')) $columns[] = 'cancelled_by';
            if (Schema::hasColumn('orders', 'cancellation_reason')) $columns[] = 'cancellation_reason';
            if (Schema::hasColumn('orders', 'cancelled_by_role')) $columns[] = 'cancelled_by_role';
            if (Schema::hasColumn('orders', 'refund_required')) $columns[] = 'refund_required';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};