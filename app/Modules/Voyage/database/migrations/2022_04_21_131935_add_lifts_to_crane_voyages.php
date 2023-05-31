<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLiftsToCraneVoyages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crane_voyages', function (Blueprint $table) {
            $table->dateTime("fofl")->nullable();
            $table->dateTime("foll")->nullable();
            $table->dateTime("fifl")->nullable();
            $table->dateTime("fill")->nullable();
            $table->dateTime("sifl")->nullable();
            $table->dateTime("sill")->nullable();
            $table->dateTime("sevfl")->nullable();
            $table->dateTime("sevll")->nullable();
            $table->dateTime("eifl")->nullable();
            $table->dateTime("eill")->nullable();
            $table->dateTime("nfl")->nullable();
            $table->dateTime("nll")->nullable();
            $table->dateTime("tenfl")->nullable();
            $table->dateTime("tenll")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crane_voyages', function (Blueprint $table) {
            //
        });
    }
}
