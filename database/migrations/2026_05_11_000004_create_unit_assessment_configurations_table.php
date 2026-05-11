<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table defines how different assessment types (assignments, quizzes, exams)
     * contribute to a unit's final grade. Teachers can set different weights for
     * different assessment types within each unit.
     *
     * Example:
     * Unit 1: Introduction to Networks
     * ├── Assignment (40% of unit grade)
     * ├── Quiz (30% of unit grade)
     * └── Exam (30% of unit grade)
     */
    public function up(): void
    {
        Schema::create('unit_assessment_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');

            // Assessment type: 'assignment', 'quiz', 'exam', 'project', 'practical', etc.
            $table->string('assessment_type')->comment('Type of assessment (assignment, quiz, exam, project, etc.)');

            // Weight within this unit (should sum to 100% for all assessment types in unit)
            $table->decimal('weight_percent', 5, 2)->comment('Percentage weight within this unit (0-100)');

            // Optional description
            $table->text('description')->nullable()->comment('Description of this assessment type for the unit');

            // Whether this assessment type is active/enabled for this unit
            $table->boolean('is_active')->default(true);

            // Timestamps
            $table->timestamps();

            // Index for faster queries
            $table->index(['unit_id', 'assessment_type']);
            $table->unique(['unit_id', 'assessment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_assessment_configurations');
    }
};
