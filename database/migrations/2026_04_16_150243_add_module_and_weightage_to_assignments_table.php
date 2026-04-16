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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained('course_modules')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->after('module_id')->constrained('teachers')->onDelete('cascade');
            $table->decimal('weightage', 5, 2)->default(0)->after('max_score');
            $table->text('instructions')->nullable()->after('weightage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['module_id', 'teacher_id', 'weightage', 'instructions']);
        });
    }
};
