<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFujiFairAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuji_fair_assessments', function (Blueprint $table) {
            $table->id();            
            $table->string('group_identifier');
            $table->string('doi');
            $table->boolean('processed')->default(0);
            $table->integer('response_code')->nullable();
            $table->mediumText('response_full')->nullable();
            
            $table->integer('score_F')->nullable();
            $table->integer('score_F1')->nullable();
            $table->integer('score_F2')->nullable();
            $table->integer('score_F3')->nullable();
            $table->integer('score_F4')->nullable();
            $table->integer('score_A')->nullable();
            $table->integer('score_A1')->nullable();
            $table->integer('score_A2')->nullable();
            $table->integer('score_I')->nullable();
            $table->integer('score_I1')->nullable();
            $table->integer('score_I2')->nullable();
            $table->integer('score_I3')->nullable();
            $table->integer('score_R')->nullable();
            $table->integer('score_R1')->nullable();
            $table->integer('score_R1_1')->nullable();
            $table->integer('score_R1_2')->nullable();
            $table->integer('score_R1_3')->nullable();            
            
            $table->integer('score_percent')->nullable();            
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
        Schema::dropIfExists('fuji_fair_assessments');
    }
}
