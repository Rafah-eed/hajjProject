<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hajj_types', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Q', 'T', 'I'])->default('T');/// الحج ممكن أن يكون حج إفراد أو قران أو تمتع
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('pilgrim_id');;
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('pilgrim_id')->references('id')->on('pilgrims')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hajj_types');
    }
};
