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
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->string('uri', 1000)->change();
        });
        Schema::table('laboratory_keywords', function (Blueprint $table) {
            $table->string('uri', 1000)->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabularies', function (Blueprint $table) {
            $table->string('uri', 255)->change();
        });
        Schema::table('laboratory_keywords', function (Blueprint $table) {
            $table->string('uri', 255)->change();
        });
    }
};
