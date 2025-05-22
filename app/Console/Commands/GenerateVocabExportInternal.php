<?php

namespace App\Console\Commands;

use App\Exports\Vocabs\ExcelExportInternal;
use App\Models\Vocabulary;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class GenerateVocabExportInternal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabs:export-internal {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and stores all available vocabulary export formats on disk based on versionnumber.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vocabularies = Vocabulary::where('version', $this->argument('version'))->get();
        $this->line($vocabularies->count().' vocabularies found.');

        foreach ($vocabularies as $vocabulary) {
            $this->line('processing '.$vocabulary->name.' exports...');
            $basePath = 'vocabularies/internal/'.$vocabulary->version.'/';

            // //store Excel export
            $path = $basePath.$vocabulary->name.'_'.$this->versionFileName($vocabulary->version).'.xlsx';
            Excel::store(new ExcelExportInternal($vocabulary), $path, 'local');
        }

        $this->line('Finished exporting vocabularies.');

        return 0;
    }

    private function versionFileName($version)
    {
        return str_replace('.', '-', $version);
    }
}
