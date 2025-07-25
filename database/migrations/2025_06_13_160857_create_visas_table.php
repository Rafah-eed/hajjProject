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
            $table->string('visa_file') ->nullable();
            $table->enum('status', ['await', 'accepted', 'refused'])->default('await');
            $table->integer('request_number')->default(1);
            $table->unsignedBigInteger('hajj_type_id')->nullable();
    
            $table->timestamps();

            $table->foreign('pilgrim_id')->references('id')->on('pilgrims')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
            $table->foreign('hajj_type_id')->references('id')->on('hajj_types')->onDelete('cascade');

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