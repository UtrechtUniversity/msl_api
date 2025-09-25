<?php
namespace App\Console\Commands;

use App\Exports\Vocabs\LaboratoriesJsonExport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateLaboratoryExport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vocabs:laboratories';

    /**
     * The console command description.
     */
    protected $description = 'Generates and stores the laboraties vocabulary in JSON';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle(): int
    {
        $exporter = new LaboratoriesJsonExport();
        $basePath = 'vocabs/laboratories/';

        $path = $basePath . 'laboratories.json';
        Storage::disk('public')->put($path, $exporter->export());        

        $this->line("Finished exporting laboratories.");
        return 0;
    }
}