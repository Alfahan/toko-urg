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
        Schema::create('purchase_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_transaction_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('price_per_qty');
            $table->integer('qty');
            $table->bigInteger('price');

            //relationship transactions
            $table->foreign('purchase_transaction_id')->references('id')->on('purchase_transactions');

            //relationship products
            $table->foreign('product_id')->references('id')->on('products');

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
        Schema::dropIfExists('purchase_transaction_details');
    }
};
