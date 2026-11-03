<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('right', 500);
            $table->string('uri', 500);
            $table->string('identifier', 500);
            $table->string('identifier_scheme', 500);
            $table->string('identifier_scheme_uri', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rights');
    }
};
