<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->string('instruction_file_path')->nullable()->after('instructions');
            $table->string('instruction_file_name')->nullable()->after('instruction_file_path');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['instruction_file_path', 'instruction_file_name']);
        });
    }
};
