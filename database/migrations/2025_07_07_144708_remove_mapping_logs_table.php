<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('mapping_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('mapping_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('message');
            $table->unsignedBigInteger('source_dataset_id');
            $table->unsignedBigInteger('import_id');
            $table->timestamps();
        });
    }
};
