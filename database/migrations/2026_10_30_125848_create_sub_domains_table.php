<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_domains', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 250);
            $table->string('short_name', 250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_domains');
    }
};
