<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hotel_name');
            $table->double('price');
            $table->string('package_validity');
            $table->string('duration');
            $table->longText('description');
            $table->string('photo')->default('default.png');
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
        Schema::dropIfExists('hotel_packages');
    }
}
