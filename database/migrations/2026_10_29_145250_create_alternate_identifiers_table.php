<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alternate_identifiers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('identifier', 500);
            $table->string('type', 500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternate_identifiers');
    }
};
