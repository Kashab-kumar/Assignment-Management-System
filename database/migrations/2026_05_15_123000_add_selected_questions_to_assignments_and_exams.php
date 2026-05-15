<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSelectedQuestionsToAssignmentsAndExams extends Migration
{
    public function up()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'selected_questions')) {
                $table->json('selected_questions')->nullable()->after('covered_topics');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'selected_questions')) {
                $table->json('selected_questions')->nullable()->after('covered_topics');
            }
        });
    }

    public function down()
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'selected_questions')) {
                $table->dropColumn('selected_questions');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'selected_questions')) {
                $table->dropColumn('selected_questions');
            }
        });
    }
}
