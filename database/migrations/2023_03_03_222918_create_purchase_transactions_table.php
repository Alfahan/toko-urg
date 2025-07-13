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
        Schema::create('purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('invoice');
            $table->bigInteger('cash');
            $table->bigInteger('change');
            $table->bigInteger('grand_total');

            //relationship users
            $table->foreign('admin_id')->references('id')->on('users');

            //relationship suppliers
            $table->foreign('supplier_id')->references('id')->on('suppliers');

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
        Schema::dropIfExists('purchase_transactions');
    }
};
