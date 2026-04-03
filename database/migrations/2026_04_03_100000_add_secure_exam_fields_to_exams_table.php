<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('secure_mode')->default(false)->after('max_score');
            $table->text('secure_instructions')->nullable()->after('secure_mode');
            $table->integer('max_violations')->default(3)->after('secure_instructions');
            $table->integer('max_warnings')->default(5)->after('max_violations');
        });
    }

    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['secure_mode', 'secure_instructions', 'max_violations', 'max_warnings']);
        });
    }
};
