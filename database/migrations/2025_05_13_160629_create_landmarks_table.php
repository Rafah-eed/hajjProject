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
        Schema::create('landmarks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo');
            $table->unsignedBigInteger('office_id');
            $table->string('description');
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
    public function down()
    {
        Schema::dropIfExists('landmarks');
    }
};
