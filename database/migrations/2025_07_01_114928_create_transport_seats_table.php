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
        Schema::create('transport_seats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transport_id');
            $table->string('seat');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        
            $table->foreign('transport_id')
                ->references('id')->on('transports')
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
        Schema::dropIfExists('transport_seats');
    }
};