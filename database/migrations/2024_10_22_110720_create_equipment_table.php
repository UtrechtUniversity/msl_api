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
        Schema::create('equipment_creates', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_type');
            $table->json('equipment');
            $table->integer('response_code')->nullable();
            $table->text('response_body')->nullable();
            $table->dateTime('processed')->nullable();
            $table->string('processed_type')->nullable();
            $table->unsignedBigInteger('seed_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_creates');
    }
};
