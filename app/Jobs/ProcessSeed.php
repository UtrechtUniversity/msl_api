<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Phpoaipmh\Endpoint;
use App\Models\Import;
use App\Models\SourceDatasetIdentifier;


class ProcessSeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $import;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        
        
        dd('jaja');
        
        $importer = $this->import->importer;
        
        if($importer->options['importProcessor']['type'] == 'oaiListing') {
            $endPoint = Endpoint::build($importer->options['importProcessor']['options']['oaiEndpoint']);
            
            try {
                $results = $endPoint->listIdentifiers($importer->options['importProcessor']['options']['metadataPrefix'], null, null, $importer->options['importProcessor']['options']['setDefinition']);
            } catch (\Exception $e) {
                $this->import->response_code = 404;
                $this->import->save();
            }
            
            if($results->getTotalRecordCount() > 0) {
                $counter = 0;
                foreach($results as $item) {                    
                    /*
                    $counter++;
                    if($counter > 4) {
                       break;
                    }
                    */
                    
                    
                    
                    
                    $identifier = SourceDatasetIdentifier::create([
                        'import_id' => $this->import->id,
                        'identifier' => (string)$item->identifier,
                        'extra_payload' => $this->getExtraPayload($importer, (string)$item->identifier)
                    ]);
                    
                    ProcessSourceDatasetIdentifier::dispatch($identifier);
                }
            }
            
            $this->import->response_code = 200;
            $this->import->save();
        } elseif($importer->options['importProcessor']['type'] == 'jsonListing') {
            $filePath = $importer->options['importProcessor']['options']['filePath'];
            
            if(!isset($importer->options['importProcessor']['options']['identifierKey'])) {
                throw new \Exception('IdentifierKey required for jsonListing importProcessor.');
            }
            
            if(Storage::disk()->exists($filePath)) {
                $jsonEntries = json_decode(Storage::get($filePath), true);
                $identifierKey = $importer->options['importProcessor']['options']['identifierKey'];
                
                foreach ($jsonEntries as $jsonEntry)
                {
                    if(isset($jsonEntry[$identifierKey])) {
                        $identifier = SourceDatasetIdentifier::create([
                            'import_id' => $this->import->id,
                            'identifier' => (string)$jsonEntry[$identifierKey],
                            'extra_payload' => $this->getExtraPayload($importer, (string)$jsonEntry[$identifierKey])
                        ]);
                        
                        ProcessSourceDatasetIdentifier::dispatch($identifier);
                    }
                }
                
                $this->import->response_code = 200;
                $this->import->save();
            } else {
                $this->import->response_code = 404;
                $this->import->save();
            }
        } elseif($importer->options['importProcessor']['type'] == 'directoryListing') {
            if(!isset($importer->options['importProcessor']['options']['directoryPath'])) {
                throw new \Exception('DirectoryPath setting required for directoryListing importProcessor.');
            }
            if(!isset($importer->options['importProcessor']['options']['recursive'])) {
                throw new \Exception('Recursive setting required for directoryListing importProcessor.');
            }
            
            
            $directoryPath = $importer->options['importProcessor']['options']['directoryPath'];
            
            $fileList = Storage::disk()->files($directoryPath, (bool)$importer->options['importProcessor']['options']['recursive']);
            
            foreach ($fileList as $file)
            {
                if($file !== "") {
                    $identifier = SourceDatasetIdentifier::create([
                        'import_id' => $this->import->id,
                        'identifier' => (string)$file,
                        'extra_payload' => $this->getExtraPayload($importer, (string)$file)
                    ]);
                    
                    ProcessSourceDatasetIdentifier::dispatch($identifier);
                }
            }
            
            $this->import->response_code = 200;
            $this->import->save();
            
        } else {
            throw new \Exception('Invalid importProcessor defined in importer config.');
        }
        
        
        
    }
    
}
