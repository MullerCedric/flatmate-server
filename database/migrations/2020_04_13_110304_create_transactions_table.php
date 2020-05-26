<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('label', 250);
            $table->integer('amount')->default(0);
            $table->foreignId('flat_id')->nullable();
            $table->foreignId('from_id');
            $table->foreignId('to_id')->nullable();
            $table->jsonb('tags')->nullable();
            $table->dateTime('start_date', 0);
            $table->dateTime('end_date', 0)->nullable();
            $table->timestamp('interval', 0)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
