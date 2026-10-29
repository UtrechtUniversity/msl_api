<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_publications', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('title_annotated');
            $table->string('source_landing_page_url', 500);
            $table->string('resource_type', 100);
            $table->string('resource_type_general', 100);
            $table->string('doi', 250);
            $table->year('publication_year');
            $table->string('language', 100);
            $table->string('datacite_version', 100);
            $table->string('publisher', 250);
            $table->text('citation');
            $table->json('geojson_feature_collection');
            $table->integer('surface_area')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_publications');
    }
};
