<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affiliations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->string('identifier', 250);
            $table->string('scheme', 250);
            $table->string('uri', 250);
            $table->string('affiliatable_type', 250);
            $table->unsignedBigInteger('affiliatable_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliations');
    }
};
