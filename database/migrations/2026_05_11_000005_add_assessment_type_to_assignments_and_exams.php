<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add assessment_type to assignments and exams to categorize them
     * This links to unit_assessment_configurations for weight calculation
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'assessment_type')) {
                // e.g., 'assignment', 'quiz', 'project', 'homework', etc.
                $table->string('assessment_type')->nullable()->default('assignment')->after('type');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'assessment_type')) {
                // e.g., 'exam', 'quiz', 'test', etc.
                $table->string('assessment_type')->nullable()->default('exam')->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'assessment_type')) {
                $table->dropColumn('assessment_type');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'assessment_type')) {
                $table->dropColumn('assessment_type');
            }
        });
    }
};
