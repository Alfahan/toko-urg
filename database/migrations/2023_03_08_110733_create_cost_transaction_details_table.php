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
        Schema::create('cost_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_transaction_id');
            $table->string('name_cost');
            $table->bigInteger('price_per_qty');
            $table->integer('qty');
            $table->bigInteger('price');
            $table->timestamps();

            //relationship transactions
            $table->foreign('cost_transaction_id')->references('id')->on('cost_transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_transaction_details');
    }
};
