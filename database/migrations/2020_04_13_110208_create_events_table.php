<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->foreignId('flat_id')->nullable();
            $table->dateTime('start_date', 0);
            $table->dateTime('end_date', 0)->nullable();
            $table->bigInteger('interval', 0)->nullable();
            $table->bigInteger('duration', 0)->default(3600000);
            $table->enum('confirm', ['before', 'during'])->nullable();
            $table->timestamps();

            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
