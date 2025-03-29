<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\DatasetCreate;
use App\Models\SourceDataset;
use App\Mappers\MappingService;
use Exception;

class ProcessSourceDataset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $sourceDataset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SourceDataset $sourceDataset)
    {
        $this->sourceDataset = $sourceDataset;
    }
    

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MappingService $mappingService)
    {
        $importer = $this->sourceDataset->source_dataset_identifier->import->importer;
        $import = $this->sourceDataset->source_dataset_identifier->import;

        try {
            $dataPublication = $mappingService->map($this->sourceDataset, $importer->options, $importer);
        } catch(Exception $e) {
            $this->sourceDataset->status = 'error';
            $this->sourceDataset->save();
            $this->fail();
        }

        $datasetCreate = DatasetCreate::create([
            'dataset_type' => $dataPublication::class,
            'dataset' => $dataPublication->toCkanArray(),
            'source_dataset_id' => $this->sourceDataset->id,
            'import_id' => $import->id,
            'response_body' => ''
        ]);
        
        if($datasetCreate) {
            ProcessDatasetCreate::dispatch($datasetCreate);
            
            $this->sourceDataset->status = 'succes';
            $this->sourceDataset->save();
        }
    }
}
