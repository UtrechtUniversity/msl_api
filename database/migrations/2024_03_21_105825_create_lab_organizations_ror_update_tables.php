<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabOrganizationsRorUpdateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_organization_update_group_ror', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('laboratory_organization_update_ror', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_organization_update_group_ror_id');
            $table->unsignedBigInteger('laboratory_organization_id');
            $table->integer('response_code')->nullable();
            $table->longText('source_organization_data')->nullable();
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
        Schema::dropIfExists('laboratory_organization_update_group_ror');
        Schema::dropIfExists('laboratory_organization_update_ror');
    }
}
