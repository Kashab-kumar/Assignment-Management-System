<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            if (!Schema::hasColumn('tests', 'weightage')) {
                $table->decimal('weightage', 5, 2)->default(0)->after('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            if (Schema::hasColumn('tests', 'weightage')) {
                $table->dropColumn('weightage');
            }
        });
    }
};
