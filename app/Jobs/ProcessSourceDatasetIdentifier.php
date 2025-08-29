<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SourceDatasetIdentifier;
use App\Jobs\SourceDatasetIdentifierProcessors\ProcessDataciteJsonRetrieval;
use App\Jobs\SourceDatasetIdentifierProcessors\ProcessDataciteXmlRetrieval;
use App\Jobs\SourceDatasetIdentifierProcessors\ProcessFileRetrieval;
use App\Jobs\SourceDatasetIdentifierProcessors\ProcessOaiRetrieval;
use App\Jobs\SourceDatasetIdentifierProcessors\ProcessOaiToDataciteJsonRetrieval;

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

        switch($importer->options['identifierProcessor']['type']) {
            case 'oaiRetrieval':
                $processor = ProcessOaiRetrieval::class;
                break;

            case 'dataciteJsonRetrieval':
                $processor = ProcessDataciteJsonRetrieval::class;
                break;

            case 'dataciteXmlRetrieval':
                $processor = ProcessDataciteXmlRetrieval::class;
                break;

            case 'fileRetrieval':
                $processor = ProcessFileRetrieval::class;
                break;

            case 'oaiToDataciteRetrieval':
                $processor = ProcessOaiToDataciteJsonRetrieval::class;
                break;

            default:
                throw new \Exception('Invalid identifierProcessor definined in importer config.');
        }

        if($processor::process($this->sourceDatasetIdentifier)) {
            $this->sourceDatasetIdentifier->response_code = 200;
            $this->sourceDatasetIdentifier->save();
        } else {
            $this->sourceDatasetIdentifier->response_code = 404;
            $this->sourceDatasetIdentifier->save();
        }
    }
}
