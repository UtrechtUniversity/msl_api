<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_publication_original_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('data_publication_id');
            $table->unsignedBigInteger('keyword_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_publication_original_keyword');
    }
};
