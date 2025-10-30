<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_organization_id')->nullable();
            $table->unsignedBigInteger('laboratory_contact_person_id')->nullable();
            $table->unsignedBigInteger('laboratory_manager_id')->nullable();
            $table->unsignedBigInteger('fast_id')->nullable();
            $table->string('msl_identifier');
            $table->string('lab_portal_name');
            $table->string('lab_editor_name');
            $table->string('msl_identifier_inputstring');
            $table->string('original_domain');
            $table->string('name');
            $table->text('description');
            $table->text('description_html');
            $table->string('website');
            $table->string('address_street_1');
            $table->string('address_street_2');
            $table->string('address_postalcode');
            $table->string('address_city');
            $table->string('address_country_code');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('altitude');
            $table->string('external_identifier');
            $table->unsignedBigInteger('fast_domain_id')->nullable();
            $table->string('fast_domain_name');
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
        Schema::dropIfExists('laboratories');
    }
}
