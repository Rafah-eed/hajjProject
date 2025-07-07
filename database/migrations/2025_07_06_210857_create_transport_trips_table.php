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
        Schema::create('transport_trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('transport_id');
            $table->unsignedBigInteger('office_id');
            $table->timestamps();
        
            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');
                
            $table->foreign('transport_id')
                ->references('id')->on('transports')
                ->onDelete('cascade');

            $table->foreign('office_id')
                ->references('id')->on('offices')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_trips');
    }
};