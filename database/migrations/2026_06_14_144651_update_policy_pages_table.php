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
        Schema::table('policy_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('policy_pages', 'refund_policy')) {
                $table->longText('refund_policy')->nullable();
            }
            if (Schema::hasColumn('policy_pages', 'terms_of_service')) {
                $table->renameColumn('terms_of_service', 'terms_and_conditions');
            }
            if (Schema::hasColumn('policy_pages', 'shipping_returns')) {
                $table->renameColumn('shipping_returns', 'shipping_policy');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_pages', function (Blueprint $table) {
            if (Schema::hasColumn('policy_pages', 'refund_policy')) {
                $table->dropColumn('refund_policy');
            }
            if (Schema::hasColumn('policy_pages', 'terms_and_conditions')) {
                $table->renameColumn('terms_and_conditions', 'terms_of_service');
            }
            if (Schema::hasColumn('policy_pages', 'shipping_policy')) {
                $table->renameColumn('shipping_policy', 'shipping_returns');
            }
        });
    }
};
