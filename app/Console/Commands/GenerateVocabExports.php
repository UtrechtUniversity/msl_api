<?php

namespace App\Console\Commands;

use App\Exports\Vocabs\EposRdfExport;
use App\Exports\Vocabs\ExcelExport;
use App\Exports\Vocabs\JsonExport;
use App\Exports\Vocabs\RdfExport;
use App\Models\Vocabulary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateVocabExports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabs:export {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and stores all available vocabulary export formats on disk based on versionnumber.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vocabularies = Vocabulary::where('version', $this->argument('version'))->get();
        $this->line($vocabularies->count().' vocabularies found.');

        foreach ($vocabularies as $vocabulary) {
            $this->line('processing '.$vocabulary->name.' exports...');
            $basePath = 'vocabs/'.$vocabulary->name.'/'.$vocabulary->version.'/';

            // store Excel export
            $path = $basePath.$vocabulary->name.'_'.$this->versionFileName($vocabulary->version).'.xlsx';
            Excel::store(new ExcelExport($vocabulary), $path, 'public');

            // store json export
            $JsonExporter = new JsonExport($vocabulary);
            $path = $basePath.$vocabulary->name.'_'.$this->versionFileName($vocabulary->version).'.json';
            Storage::disk('public')->put($path, $JsonExporter->export());

            // store turtle export
            $RdfExporter = new RdfExport($vocabulary);
            $path = $basePath.$vocabulary->name.'_'.$this->versionFileName($vocabulary->version).'.ttl';
            Storage::disk('public')->put($path, $RdfExporter->export('turtle'));

            // store rdfxml export
            $path = $basePath.$vocabulary->name.'_'.$this->versionFileName($vocabulary->version).'.xml';
            Storage::disk('public')->put($path, $RdfExporter->export('rdfxml'));

            // store EPOS specific exports
            $EposRdfExporter = new EposRdfExport($vocabulary);
            $basePath = 'vocabs/epos/'.$vocabulary->version.'/';

            // store turtle export
            $path = $basePath.$vocabulary->name.'.ttl';
            Storage::disk('public')->put($path, $EposRdfExporter->export('turtle'));

            // store rdfxml export
            $path = $basePath.$vocabulary->name.'.xml';
            Storage::disk('public')->put($path, $EposRdfExporter->export('rdfxml'));
        }
        $this->line('Finished exporting vocabularies.');

        return 0;
    }

    private function versionFileName($version)
    {
        return str_replace('.', '-', $version);
    }
}
