<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHearingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conducted_by')->constrained('users'); // কে শুনানি নিলেন
            
            $table->date('hearing_date');
            $table->date('next_hearing_date')->nullable();
            
            $table->text('order_details')->nullable(); // আদেশ বা বিবরণ
            $table->boolean('sms_sent')->default(false);
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
        Schema::dropIfExists('hearings');
    }
}
