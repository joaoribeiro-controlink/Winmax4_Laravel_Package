<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winmax4_articles', function (Blueprint $table) {
            $table->id();
            $table->char('ArticleCode', 21)->unique();
            $table->char('Designation', 50);
            $table->bigInteger('Family_ID', false,true);
            $table->string('ImageHTTPPath')->nullable();
            $table->integer('DiscountLevel')->nullable();
            $table->string('SellUnitCode')->nullable();
            $table->string('SAFTType')->nullable();
            $table->date('LastPurchaseDate')->nullable();
            $table->date('LastSellDate')->nullable();
            $table->string('CurrencyCode')->nullable();
            $table->string('GroupPrice')->nullable();
            $table->double('SalesPrice1WithoutTaxesFees')->nullable();
            $table->double('SalesPrice2WithoutTaxesFees')->nullable();
            $table->double('SalesPrice3WithoutTaxesFees')->nullable();
            $table->double('SalesPrice4WithoutTaxesFees')->nullable();
            $table->double('SalesPrice5WithoutTaxesFees')->nullable();
            $table->double('SalesPrice1WithTaxesFees')->nullable();
            $table->double('SalesPrice2WithTaxesFees')->nullable();
            $table->double('SalesPrice3WithTaxesFees')->nullable();
            $table->double('SalesPrice4WithTaxesFees')->nullable();
            $table->double('SalesPrice5WithTaxesFees')->nullable();
            $table->double('SalesPriceExtraWithoutTaxesFees')->nullable();
            $table->double('SalesPriceExtraWithTaxesFees')->nullable();
            $table->double('SalesPriceHoldWithoutTaxesFees')->nullable();
            $table->double('SalesPriceHoldWithTaxesFees')->nullable();
            $table->double('GrossCostPrice')->nullable();
            $table->double('NetCostPrice')->nullable();
            $table->bigInteger('PurchaseTax_ID', false,true);
            $table->bigInteger('SaleTax_ID', false,true);
            $table->timestamps();
        });

        Schema::table('winmax4_articles', function (Blueprint $table){
            $table->foreign('Family_ID')
                ->references('id')
                ->on('winmax4_families');

            $table->foreign('PurchaseTax_ID')
                ->references('id')
                ->on('winmax4_taxes');

            $table->foreign('SaleTax_ID')
                ->references('id')
                ->on('winmax4_taxes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winmax4_articles');
    }
}
