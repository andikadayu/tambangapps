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
        Schema::create('schedule_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('location_from');
            $table->string('location_to');
            $table->double('distance');
            $table->integer('vehicleid');
            $table->integer('driverid');
            $table->dateTime('date_picks');
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
        Schema::dropIfExists('schedule_vehicles');
    }
};
