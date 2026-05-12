<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_module_items', function (Blueprint $table) {
            if (!Schema::hasColumn('course_module_items', 'unit_id')) {
                $table->foreignId('unit_id')
                    ->nullable()
                    ->after('course_module_id')
                    ->constrained('units')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_module_items', function (Blueprint $table) {
            if (Schema::hasColumn('course_module_items', 'unit_id')) {
                $table->dropForeign(['unit_id']);
                $table->dropColumn('unit_id');
            }
        });
    }
};
