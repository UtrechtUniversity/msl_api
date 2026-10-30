<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enriched_keyword_child_keywords', function (Blueprint $table) {
            $table->unsignedBigInteger('enriched_keyword_id');
            $table->unsignedBigInteger('keyword_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enriched_keyword_child_keywords');
    }
};
