<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('type', 250);
            $table->string('name', 500);
            $table->string('first_name', 500);
            $table->string('last_name', 500);
            $table->string('name_type', 250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
