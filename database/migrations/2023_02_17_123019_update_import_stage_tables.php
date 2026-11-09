<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateImportStageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add import id columns to source_datasets and dataset_creates tables
        if (! Schema::hasColumn('source_datasets', 'import_id')) {
            Schema::table('source_datasets', function (Blueprint $table) {
                $table->unsignedBigInteger('import_id')->after('source_dataset_identifier_id');
            });
        }

        if (! Schema::hasColumn('dataset_creates', 'import_id')) {
            Schema::table('dataset_creates', function (Blueprint $table) {
                $table->unsignedBigInteger('import_id')->after('source_dataset_id');
            });
        }

        // Update newly created columns to contain correct references to imports
        DB::statement('UPDATE source_datasets
            SET import_id = (SELECT U2.import_id FROM source_dataset_identifiers AS U2
                WHERE U2.id = source_datasets.source_dataset_identifier_id)
            WHERE source_dataset_identifier_id IN (SELECT id FROM source_dataset_identifiers);');

        DB::statement('UPDATE dataset_creates
            SET import_id = (SELECT U2.import_id FROM source_datasets AS U2
                WHERE U2.id = dataset_creates.source_dataset_id)
            WHERE source_dataset_id IN (SELECT id FROM source_datasets);');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('source_datasets', function (Blueprint $table) {
            $table->dropColumn('import_id');
        });

        Schema::table('dataset_creates', function (Blueprint $table) {
            $table->dropColumn('import_id');
        });
    }
}
