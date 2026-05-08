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
            $table->integer('max_marks')->nullable()->after('description');
            $table->string('content_type')->nullable()->after('max_marks');
            $table->text('grading_criteria')->nullable()->after('content_type');
            $table->text('grade_scale')->nullable()->after('grading_criteria');
            $table->text('ai_options')->nullable()->after('grade_scale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['max_marks', 'content_type', 'grading_criteria', 'grade_scale', 'ai_options']);
        });
    }
};
