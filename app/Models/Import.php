<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    protected $fillable = [
        'importer_id',
    ];

    public function importer(): BelongsTo
    {
        return $this->belongsTo(Importer::class);
    }

    public function sourceDatasetIdentifiers(): HasMany
    {
        return $this->hasMany(SourceDatasetIdentifier::class);
    }

    public function sourceDatasets(): HasMany
    {
        return $this->hasMany(SourceDataset::class);
    }

    public function dataset_creates(): HasMany
    {
        return $this->hasMany(DatasetCreate::class);
    }

    public function getStatsOverview()
    {
        $results = [
            'step_1_success' => DB::table('source_dataset_identifiers')->where('import_id', $this->id)->where('response_code', '=', 200)->count(),
            'step_1_total' => DB::table('source_dataset_identifiers')->where('import_id', $this->id)->count(),
            'step_2_success' => DB::table('source_datasets')->where('import_id', $this->id)->where('status', '=', 'succes')->count(),
            'step_2_total' => DB::table('source_datasets')->where('import_id', $this->id)->count(),
            'step_3_success' => $this->dataset_creates->where('response_code', '=', 200)->where('response_code', '=', 200)->count(),
            'step_3_total' => DB::table('dataset_creates')->where('import_id', $this->id)->count(),
        ];

        return $results;
    }
}
