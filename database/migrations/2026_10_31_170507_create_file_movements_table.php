<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->constrained('users');
            
            $table->boolean('is_received')->default(false); // স্ক্যান করে রিসিভ করেছে কিনা
            $table->timestamp('received_at')->nullable(); // কখন স্ক্যান করলো
            
            $table->text('remarks')->nullable(); // পাঠানোর সময় কোনো নোট (যেমন: প্রতিবেদন দিন)
            $table->timestamps(); // transferred_at হিসেবে কাজ করবে
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_movements');
    }
}
