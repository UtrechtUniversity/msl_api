<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_publication_interpreted_sub_domain', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->unsignedBigInteger('sub_domain_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_publication_interpreted_sub_domain');
    }
};
