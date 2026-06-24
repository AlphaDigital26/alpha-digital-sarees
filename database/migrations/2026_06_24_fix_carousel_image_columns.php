<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The DB already has image_mobile (added by a prior migration).
     * Our earlier migration tried to add mobile_image but it never landed
     * because the ->after() call referenced a non-existent column.
     * This migration cleans up the duplicate tablet_image column that DID
     * land, so the table is left with only the canonical image_mobile and
     * image_tablet columns.
     */
    public function up(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            // Drop the duplicate column added by the failed naming attempt
            if (Schema::hasColumn('carousels', 'tablet_image')) {
                $table->dropColumn('tablet_image');
            }
            // Ensure mobile column exists under the canonical name
            if (!Schema::hasColumn('carousels', 'image_mobile')) {
                $table->string('image_mobile')->nullable()->after('image');
            }
            // Ensure tablet column exists under the canonical name
            if (!Schema::hasColumn('carousels', 'image_tablet')) {
                $table->string('image_tablet')->nullable()->after('image_mobile');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            if (Schema::hasColumn('carousels', 'image_mobile')) {
                $table->dropColumn('image_mobile');
            }
            if (Schema::hasColumn('carousels', 'image_tablet')) {
                $table->dropColumn('image_tablet');
            }
        });
    }
};
