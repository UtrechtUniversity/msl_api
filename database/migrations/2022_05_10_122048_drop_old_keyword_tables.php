<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropOldKeywordTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('material_keywords');
        Schema::drop('apparatus_keywords');
        Schema::drop('ancillary_equipment_keywords');
        Schema::drop('pore_fluid_keywords');
        Schema::drop('measured_property_keywords');
        Schema::drop('inferred_deformation_behavior_keywords');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
