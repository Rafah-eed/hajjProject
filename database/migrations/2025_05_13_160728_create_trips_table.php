<?php

use Illuminate\Support\Str;
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
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->enum('type', ['umrah','hajj'])->default('umrah');
            $table->string('regiment_name');
            $table->integer('days_num_makkah');
            $table->integer('days_num_madinah');
            $table->decimal('price');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active');
            $table->integer('numOfReservations')->nullable();
            $table->integer('enrollNum')->nullable();
            $table->integer('trip_code');

            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
};