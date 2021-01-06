<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubfamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winmax4_subfamilies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Family_ID', false, true);
            $table->string('Code');
            $table->string('Designation');
            $table->timestamps();
        });

        Schema::table('winmax4_subfamilies', function (Blueprint $table) {
            $table->foreign('family_id')
                ->references('id')
                ->on('winmax4_families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winmax4_subfamilies');
    }
}
