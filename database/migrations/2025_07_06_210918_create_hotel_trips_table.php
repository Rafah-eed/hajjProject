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
        Schema::create('hotel_trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('office_id');
            $table->timestamps();
        
            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');
                
            $table->foreign('hotel_id')
                ->references('id')->on('hotels')
                ->onDelete('cascade');

            $table->foreign('office_id')
                ->references('id')->on('offices')
                ->onDelete('cascade');

            $table->enum('place', ['Madina', 'Makka'])->default('Makka');
            
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_trips');
    }
};