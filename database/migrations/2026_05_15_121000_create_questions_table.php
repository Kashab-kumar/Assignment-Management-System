<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id')->nullable()->index();
            $table->unsignedBigInteger('unit_id')->nullable()->index();
            $table->string('topic')->nullable()->index();
            $table->string('question_type')->nullable();
            $table->text('question_text');
            $table->json('options')->nullable();
            $table->text('answer')->nullable();
            $table->decimal('marks', 8, 2)->default(0);
            $table->string('difficulty')->nullable();
            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
