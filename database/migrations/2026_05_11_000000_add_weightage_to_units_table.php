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
        Schema::table('units', function (Blueprint $table) {
            // Add weightage_percent if not exists
            if (!Schema::hasColumn('units', 'weightage_percent')) {
                $table->decimal('weightage_percent', 5, 2)->nullable()->default(0)->after('description');
            }
            // Add is_active if not exists
            if (!Schema::hasColumn('units', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'weightage_percent')) {
                $table->dropColumn('weightage_percent');
            }
            if (Schema::hasColumn('units', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
