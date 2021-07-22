<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('l_elem')->default(0);
            $table->integer('q_elem')->default(0);
            $table->integer('h_elem')->default(0);
            $table->integer('flaps_a')->default(0);
            $table->integer('flaps_b')->default(0);
            $table->date('date_addmission');
            $table->date('date_production');
            $table->date('date_delivery');
            $table->integer('client_id')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
