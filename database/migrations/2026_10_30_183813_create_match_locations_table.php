<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('match_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enriched_keyword_id');
            $table->string('location', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_locations');
    }
};
