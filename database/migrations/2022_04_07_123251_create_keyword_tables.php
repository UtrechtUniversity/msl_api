<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('vocabulary_id');
            $table->string('value');
            $table->string('uri');
            $table->integer('level');
            $table->string('hyperlink');
            $table->timestamps();
        });
        
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uri');
            $table->timestamps();
        });
        
        Schema::create('keywords_search', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keyword_id');
            $table->string('search_value')->index();
            $table->boolean('isSynonym');
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
        Schema::dropIfExists('keyword');
        Schema::dropIfExists('vocabularies');
        Schema::dropIfExists('keywords_search');
    }
}
