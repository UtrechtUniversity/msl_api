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
        // Remove contact person reference to be replaced with 1:N relation
        Schema::table('laboratories', function (Blueprint $table) {
            $table->dropColumn('laboratory_contact_person_id');
        });

        // From now on we will only have emails for contact personse, remove all unused fields
        Schema::table('laboratory_contact_persons', function (Blueprint $table) {
            $table->dropColumn('fast_id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('orcid');
            $table->dropColumn('address_street_1');
            $table->dropColumn('address_street_2');
            $table->dropColumn('address_postalcode');
            $table->dropColumn('address_city');
            $table->dropColumn('address_country_code');
            $table->dropColumn('address_country_name');
            $table->dropColumn('affiliation_fast_id');
            $table->dropColumn('nationality_code');
            $table->dropColumn('nationality_name');
        });

        // Add reference to laboratory
        Schema::table('laboratory_contact_persons', function (Blueprint $table) {
            $table->unsignedBigInteger('laboratory_id');             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratories', function (Blueprint $table) {
            $table->unsignedBigInteger('laboratory_contact_person_id')->nullable();
        });

        Schema::table('laboratory_contact_persons', function (Blueprint $table) {
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
        });

        Schema::table('laboratory_contact_persons', function (Blueprint $table) {
            $table->dropColumn('laboratory_id');
        });
    }
};
