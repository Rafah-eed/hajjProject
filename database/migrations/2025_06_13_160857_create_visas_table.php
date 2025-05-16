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
        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pilgrim_id');
            $table->unsignedBigInteger('trip_id');
            $table->string('visa_file');
            $table->enum('status', ['await', 'accepted', 'refused'])->default('await');
            $table->integer('request_number');
            $table->timestamps();

            $table->foreign('guide_id')->references('id')->on('guides')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visas');
    }
};
