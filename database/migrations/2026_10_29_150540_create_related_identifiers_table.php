<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('related_identifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('identifier', 500);
            $table->string('type', 500);
            $table->string('relation_type', 500);
            $table->string('metadata_scheme', 500);
            $table->string('metadata_scheme_uri', 500);
            $table->string('metadata_scheme_type', 500);
            $table->string('resource_type_general', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_identifiers');
    }
};
