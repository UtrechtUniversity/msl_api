<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('tna_mockup');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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
};
