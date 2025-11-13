<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoriesExtraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fast_id')->nullable();
            $table->string('name');
            $table->string('external_identifier');
            $table->timestamps();
        });

        Schema::create('laboratory_contact_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fast_id')->nullable();
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('orcid');
            $table->string('address_street_1');
            $table->string('address_street_2');
            $table->string('address_postalcode');
            $table->string('address_city');
            $table->string('address_country_code');
            $table->string('address_country_name');
            $table->unsignedBigInteger('affiliation_fast_id');
            $table->string('nationality_code');
            $table->string('nationality_name');
            $table->timestamps();
        });

        Schema::create('laboratory_managers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fast_id')->nullable();
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('orcid');
            $table->string('address_street_1');
            $table->string('address_street_2');
            $table->string('address_postalcode');
            $table->string('address_city');
            $table->string('address_country_code');
            $table->string('address_country_name');
            $table->unsignedBigInteger('affiliation_fast_id');
            $table->string('nationality_code');
            $table->string('nationality_name');
            $table->timestamps();
        });

        Schema::create('laboratory_equipment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fast_id')->nullable();
            $table->unsignedBigInteger('laboratory_id')->nullable();
            $table->text('description');
            $table->text('description_html');
            $table->string('category_name');
            $table->string('type_name');
            $table->string('domain_name');
            $table->string('group_name');
            $table->string('brand');
            $table->string('website');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('altitude');
            $table->string('external_identifier');
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
        Schema::dropIfExists('laboratory_organizations');
        Schema::dropIfExists('laboratory_contact_persons');
        Schema::dropIfExists('laboratory_managers');
        Schema::dropIfExists('laboratory_equipment');
    }
}
