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
        Schema::table('course_module_items', function (Blueprint $table) {
            $table->json('grade_scale')->nullable()->after('file_type');
            $table->json('grading_criteria')->nullable()->after('grade_scale');
            $table->json('ai_options')->nullable()->after('grading_criteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_module_items', function (Blueprint $table) {
            $table->dropColumn(['grade_scale', 'grading_criteria', 'ai_options']);
        });
    }
};
