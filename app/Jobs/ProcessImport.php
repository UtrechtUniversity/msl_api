<?php

namespace App\Jobs;

use App\Jobs\ImportProcessors\ProcessDirectoryListing;
use App\Jobs\ImportProcessors\ProcessJsonListing;
use App\Jobs\ImportProcessors\ProcessOaiListing;
use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImport implements ShouldQueue
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
        $importer = $this->import->importer;

        switch ($importer->options['importProcessor']['type']) {
            case 'oaiListing':
                $processor = ProcessOaiListing::class;
                break;

            case 'jsonListing':
                $processor = ProcessJsonListing::class;
                break;

            case 'directoryListing':
                $processor = ProcessDirectoryListing::class;
                break;

            default:
                throw new \Exception('Invalid importProcessor definined in importer config.');
        }

        if ($processor::process($this->import)) {
            $this->import->response_code = 200;
            $this->import->save();
        } else {
            $this->import->response_code = 404;
            $this->import->save();
        }
    }
}
