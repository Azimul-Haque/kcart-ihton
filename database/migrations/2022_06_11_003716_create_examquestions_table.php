<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamquestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('examquestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->unsigned();
            $table->foreignId('question_id')->unsigned();
            $table->timestamps();

            $table->unique(['exam_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examquestions');
    }
}
