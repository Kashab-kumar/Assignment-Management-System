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
        Schema::create('tests', function (Blueprint $table) {
            $table->id('test_id');
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->integer('total_marks');
            $table->integer('passing_marks');
            $table->integer('duration');
            $table->boolean('is_ai_generated')->default(false);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
