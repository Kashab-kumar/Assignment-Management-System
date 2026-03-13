<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_module_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->string('type', 40);
            $table->string('title');
            $table->text('content')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['course_module_id', 'type']);
            $table->index(['course_module_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_module_items');
    }
};