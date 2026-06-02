<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Idempotent checks ensure this migration never crashes if columns exist
            if (!Schema::hasColumn('orders', 'courier_partner')) {
                $table->string('courier_partner')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('courier_partner');
            }
            if (!Schema::hasColumn('orders', 'shipping_date')) {
                $table->timestamp('shipping_date')->nullable()->after('tracking_number');
            }
            if (!Schema::hasColumn('orders', 'expected_delivery_date')) {
                $table->timestamp('expected_delivery_date')->nullable()->after('shipping_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('orders', 'courier_partner')) {
                $columnsToDrop[] = 'courier_partner';
            }
            if (Schema::hasColumn('orders', 'tracking_number')) {
                $columnsToDrop[] = 'tracking_number';
            }
            if (Schema::hasColumn('orders', 'shipping_date')) {
                $columnsToDrop[] = 'shipping_date';
            }
            if (Schema::hasColumn('orders', 'expected_delivery_date')) {
                $columnsToDrop[] = 'expected_delivery_date';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};