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
        Schema::create('laboratory_equipment_addons', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->unsignedBigInteger('laboratory_equipment_id');
            $table->unsignedBigInteger('keyword_id')->nullable();
            $table->string('type');
            $table->string('group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_equipment_addons');
    }
};
