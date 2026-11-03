<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('name_identifiers', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 250);
            $table->string('scheme', 250);
            $table->string('uri', 250);
            $table->string('identifiable_type', 250);
            $table->unsignedBigInteger('identifiable_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('name_identifiers');
    }
};
