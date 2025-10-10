<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TnaMockup;

class CreateTnaMockupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tna_mockup', function (Blueprint $table) {
            $table->id();
            $table->string('organizationName');
            $table->string('facilityName');
            $table->string('facilityUrl');
            $table->string('facilityCountry');
            $table->string('facilityCity');
            $table->string('equipmentType');
            $table->string('equipmentGroup');
            $table->string('equipmentName');
            $table->string('equipmentUrl');
            $table->string('equipmentManufacturer');
            $table->string('tnaStartDate');
            $table->string('tnaEndDate');
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
        Schema::dropIfExists('tna_mockup');
    }
}
