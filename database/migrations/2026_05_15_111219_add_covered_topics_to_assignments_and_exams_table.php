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
            if (!Schema::hasColumn('assignments', 'covered_topics')) {
                $table->json('covered_topics')->nullable()->comment('Topics from unit outline covered by this assignment');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'covered_topics')) {
                $table->json('covered_topics')->nullable()->comment('Topics from unit outline covered by this exam');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasColumn('assignments', 'covered_topics')) {
                $table->dropColumn('covered_topics');
            }
        });

        Schema::table('exams', function (Blueprint $table) {
            if (Schema::hasColumn('exams', 'covered_topics')) {
                $table->dropColumn('covered_topics');
            }
        });
    }
};
