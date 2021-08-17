<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('l_elem')->default(0);
            $table->integer('q_elem')->default(0);
            $table->integer('h_elem')->default(0);
            $table->integer('flaps_a')->default(0);
            $table->integer('flaps_b')->default(0);
            $table->integer('l_elem_pieces')->default(0);
            $table->integer('q_elem_pieces')->default(0);
            $table->text('division_flapsL');
            $table->text('division_flapsQ');
            $table->text('pallets');
            $table->text('article_number');
            $table->integer('packaging');
            $table->date('date_shipment');
            $table->date('date_production');
            $table->date('date_delivery');
            $table->integer('custom_order_id');
            $table->integer('order_place');
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
        Schema::dropIfExists('order_positions');
    }
}
