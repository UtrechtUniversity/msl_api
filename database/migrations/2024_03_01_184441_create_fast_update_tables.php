<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFastUpdateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratory_update_group_fast', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('laboratory_update_fast', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_update_group_fast_id');
            $table->unsignedBigInteger('laboratory_id');
            $table->integer('response_code')->nullable();
            $table->longText('source_laboratory_data')->nullable();
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
        Schema::dropIfExists('laboratory_update_group_fast');
        Schema::dropIfExists('laboratory_update_fast');
    }
}
