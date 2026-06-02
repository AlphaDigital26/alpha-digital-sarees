<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // These checks make the migration idempotent (safe to run multiple times)
            if (!Schema::hasColumn('orders', 'cancelled_by_role')) {
                $table->string('cancelled_by_role')->nullable()->after('cancelled_by'); // 'admin' or 'customer'
            }
            if (!Schema::hasColumn('orders', 'refund_required')) {
                $table->boolean('refund_required')->default(false)->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('orders', 'cancelled_by_role')) $columns[] = 'cancelled_by_role';
            if (Schema::hasColumn('orders', 'refund_required')) $columns[] = 'refund_required';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};