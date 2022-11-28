<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitPresentationPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_presentation_price', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mango_presentation_id');
            $table->integer('unit_of_measurement_id');
            $table->double('current_price',8,2)->default(0);
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
        Schema::dropIfExists('unit_presentation_price');
    }
}
