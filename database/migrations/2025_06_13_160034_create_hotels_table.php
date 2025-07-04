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
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('office_id');
            $table->string('hotel_name');
            $table->decimal('rate', 10, 2);
            $table->string('address');
            $table->timestamps();

            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};