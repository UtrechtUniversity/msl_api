<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('data_publication_id');
            $table->string('file_name', 250);
            $table->string('download_url', 500);
            $table->string('extension', 50);
            $table->string('type', 50);
            $table->timestamp('origin_timestamp_created')->nullable();
            $table->timestamp('origin_timestamp_updated')->nullable();
            $table->string('origin_full_path', 500);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('checksum', 100)->nullable();
            $table->integer('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
