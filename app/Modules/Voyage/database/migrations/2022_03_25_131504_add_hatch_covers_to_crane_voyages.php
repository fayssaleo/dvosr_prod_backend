<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHatchCoversToCraneVoyages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crane_voyages', function (Blueprint $table) {
            $table->integer("lgbohc_all_inbay_hatch_covers")->nullable();
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
