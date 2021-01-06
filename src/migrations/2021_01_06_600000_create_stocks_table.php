<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winmax4_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Article_ID', false, true);
            $table->bigInteger('Warehouse_ID', false, true);
            $table->double('CurrentStock');
            $table->timestamps();
        });

        Schema::table('winmax4_stocks', function (Blueprint $table) {
            $table->foreign('Article_ID')
                ->references('id')
                ->on('winmax4_articles');

            $table->foreign('Warehouse_ID')
                ->references('id')
                ->on('winmax4_warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winmax4_stocks');
    }
}
