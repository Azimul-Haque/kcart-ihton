<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->integer('examcategory_id')->unsigned();
            $table->string('name');
            $table->string('duration');
            $table->string('qsweight', 10);
            $table->integer('negativepercentage');
            $table->integer('price_type');
            $table->date('available_from');
            $table->date('available_to');
            $table->text('syllabus');
            $table->integer('participation')->default(0);
            $table->string('cutmark')->default(0);
            $table->string('lecture_url')->nullable();
            $table->integer('alltimeavailability')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
