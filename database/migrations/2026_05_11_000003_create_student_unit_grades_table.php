<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores the aggregated performance of each student per unit.
     * It calculates and caches the student's weighted average based on all
     * assignments, exams, and submissions for that specific unit.
     */
    public function up(): void
    {
        Schema::create('student_unit_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');

            // Achievement metrics
            $table->decimal('achieved_score', 5, 2)->nullable()->comment('Weighted average score for this unit');
            $table->decimal('total_possible_score', 5, 2)->nullable()->comment('Total possible marks for this unit');
            $table->decimal('percentage', 5, 2)->nullable()->comment('Percentage achieved (0-100)');

            // Status tracking
            $table->enum('status', ['Mastered', 'In Progress', 'Needs Attention'])->default('In Progress');
            $table->integer('attempt_count')->default(0)->comment('Number of attempts/submissions');

            // Progress tracking
            $table->date('first_attempted_at')->nullable();
            $table->date('last_attempted_at')->nullable();

            // Timestamps
            $table->timestamps();

            // Composite unique index to prevent duplicate records
            $table->unique(['student_id', 'unit_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_unit_grades');
    }
};
