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
use App\Models\SourceDatasetIdentifier;
use App\Models\SourceDataset;
use App\Datacite\Datacite;

class ProcessSourceDatasetIdentifier implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $sourceDatasetIdentifier;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SourceDatasetIdentifier $sourceDatasetIdentifier)
    {
        $this->sourceDatasetIdentifier = $sourceDatasetIdentifier;
    }
        

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $importer = $this->sourceDatasetIdentifier->import->importer;
        $import = $this->sourceDatasetIdentifier->import;
        
        if($importer->options['identifierProcessor']['type'] == 'oaiRetrieval') {        
            $endPoint = Endpoint::build($importer->options['identifierProcessor']['options']['oaiEndpoint']);
            
            $result = $endPoint->getRecord($this->sourceDatasetIdentifier->identifier, $importer->options['identifierProcessor']['options']['metadataPrefix']);
            
            if($result) {
                $SourceDataset = SourceDataset::create([
                    'source_dataset_identifier_id'=> $this->sourceDatasetIdentifier->id,
                    'import_id' => $import->id,
                    'source_dataset' => $result->asXML()
                ]);
                
                ProcessSourceDataset::dispatch($SourceDataset);
                
                $this->sourceDatasetIdentifier->response_code = 200;
                $this->sourceDatasetIdentifier->save();
            } else {
                $this->sourceDatasetIdentifier->response_code = 404;
                $this->sourceDatasetIdentifier->save();
            }
        } elseif ($importer->options['identifierProcessor']['type'] == 'dataciteXmlRetrieval') {
            $datacite = new Datacite();
            
            $result = $datacite->doisRequest($this->sourceDatasetIdentifier->identifier, true);
            
            if($result->response_code == 200) {
                $xml = base64_decode($result->response_body['data']['attributes']['xml']);
                
                $SourceDataset = SourceDataset::create([
                    'source_dataset_identifier_id'=> $this->sourceDatasetIdentifier->id,
                    'import_id' => $import->id,
                    'source_dataset' => $xml
                ]);
                
                ProcessSourceDataset::dispatch($SourceDataset);
                
                $this->sourceDatasetIdentifier->response_code = 200;
                $this->sourceDatasetIdentifier->save();                
            } else {
                
                
                $this->sourceDatasetIdentifier->response_code = 404;
                $this->sourceDatasetIdentifier->save();
            }
        } elseif ($importer->options['identifierProcessor']['type'] == 'urlXmlRetrieval') {
            $client = new \GuzzleHttp\Client();
            
            $response = $client->request('GET', $this->sourceDatasetIdentifier->identifier);
                        
            if($response->getStatusCode() == 200) {
                $SourceDataset = SourceDataset::create([
                    'source_dataset_identifier_id'=> $this->sourceDatasetIdentifier->id,
                    'import_id' => $import->id,
                    'source_dataset' => $response->getBody()->getContents()
                ]);
                
                ProcessSourceDataset::dispatch($SourceDataset);
            }
            
            $this->sourceDatasetIdentifier->response_code = $response->getStatusCode();
            $this->sourceDatasetIdentifier->save();            
        } elseif ($importer->options['identifierProcessor']['type'] == 'fileRetrieval') {
            if(Storage::disk()->exists($this->sourceDatasetIdentifier->identifier)) {
                $fileContent = Storage::get($this->sourceDatasetIdentifier->identifier);
                
                $SourceDataset = SourceDataset::create([
                    'source_dataset_identifier_id'=> $this->sourceDatasetIdentifier->id,
                    'import_id' => $import->id,
                    'source_dataset' => $fileContent
                ]);
                
                ProcessSourceDataset::dispatch($SourceDataset);
                
                $this->sourceDatasetIdentifier->response_code = 200;
                $this->sourceDatasetIdentifier->save();
            } else {
                $this->sourceDatasetIdentifier->response_code = 404;
                $this->sourceDatasetIdentifier->save();
            }
            
        } else {
            throw new \Exception('Invalid identifierProcessor definined in importer config.');
        }
        
    }
}
