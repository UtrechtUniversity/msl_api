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
        Schema::table('keywords_search', function (Blueprint $table) {
            $table->dropColumn('exclude_selection_group_1');
            $table->dropColumn('exclude_selection_group_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keywords_search', function (Blueprint $table) {
            $table->boolean('exclude_selection_group_1')->default(0);
            $table->boolean('exclude_selection_group_2')->default(0);
        });
    }
};
