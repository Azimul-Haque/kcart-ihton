<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // QR Code এর জন্য
            $table->string('type'); // Misc Case, CR Case, 144/145 etc.
            $table->string('case_number')->nullable(); // যেমন: ১২/২০২৬-২৭
            
            $table->string('badi_name')->nullable();
            $table->string('badi_phone')->nullable();
            $table->string('bibadi_name')->nullable();
            $table->string('bibadi_phone')->nullable();
            
            $table->string('status'); // তদন্তাধীন, শুনানির জন্য, আদেশের জন্য ইত্যাদি
            $table->foreignId('current_desk_id')->constrained('users'); // বর্তমানে কার কাছে আছে
            $table->date('next_hearing_date')->nullable();
            
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
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
        Schema::dropIfExists('files');
    }
}
