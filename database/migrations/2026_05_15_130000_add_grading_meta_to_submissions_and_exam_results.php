<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('score');
            $table->foreignId('graded_by')->nullable()->after('grade')->constrained('teachers')->nullOnDelete();
            $table->timestamp('graded_at')->nullable()->after('graded_by');
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('score');
            $table->text('feedback')->nullable()->after('grade');
            $table->foreignId('graded_by')->nullable()->after('feedback')->constrained('teachers')->nullOnDelete();
            $table->timestamp('graded_at')->nullable()->after('graded_by');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['grade', 'graded_by', 'graded_at']);
        });

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropForeign(['graded_by']);
            $table->dropColumn(['grade', 'feedback', 'graded_by', 'graded_at']);
        });
    }
};
