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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['umrah', 'hajjQ','hajjT','hajjI'])->default('umrah'); /// الحج ممكن أن يكون حج إفراد أو قران أو تمتع
            $table->string('regiment_name');
            $table->integer('days_num_makkah');
            $table->integer('days_num_madinah');
            $table->decimal('price');
            $table->date('start_date');
            $table->boolean('is_active');
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
        Schema::dropIfExists('trips');
    }
};
