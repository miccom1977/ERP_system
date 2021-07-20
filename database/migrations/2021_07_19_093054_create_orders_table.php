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
            $table->integer('status');
            $table->integer('quantity')->default(0);
            $table->integer('l_elem')->default(0);
            $table->integer('q_elem')->default(0);
            $table->integer('h_elem')->default(0);
            $table->integer('flaps_a')->default(0);
            $table->integer('flaps_b')->default(0);

            $table->timestamps();

            // dodać relację z Material_id
            // dodać relację z Client_id
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
