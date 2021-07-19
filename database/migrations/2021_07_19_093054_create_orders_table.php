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
            $table->text('status');
            $table->integer('weight');
            $table->integer('quantity');
            $table->integer('l_elem');
            $table->integer('q_elem');
            $table->integer('h_elem');
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
