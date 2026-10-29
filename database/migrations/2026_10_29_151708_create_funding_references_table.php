<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('funding_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('funder_name', 500);
            $table->string('funder_identifier', 500);
            $table->string('funder_identifier_type', 500);
            $table->string('scheme_uri', 500);
            $table->string('award_number', 500);
            $table->string('award_uri', 500);
            $table->string('award_title', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funding_references');
    }
};
