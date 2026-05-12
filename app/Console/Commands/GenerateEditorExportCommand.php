<?php

namespace App\Console\Commands;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateEditorExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabs:editor-export {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create json export for editor for specific vocabulary version number';

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
    public function handle(): int
    {
        $vocabularies = Vocabulary::where('version', $this->argument('version'))->get();
        $this->line($vocabularies->count().' vocabularies found.');

        $path = 'editor_'.$this->versionFileName($this->argument('version')).'.json';
        Storage::disk('public')->put($path, $this->export($vocabularies));

        $this->line('export generated');

        return 0;
    }

    private function export($vocabularies): false|string
    {
        $sortPriority = [
            'materials',
            'geologicalage',
            'porefluids',
            'geologicalsetting',
            'subsurface',
            'analogue',
            'fieldscale',
            'geochemistry',
            'microscopy',
            'paleomagnetism',
            'rockphysics',
        ];

        $sortedVocabularies = $vocabularies->sortBy(function ($vocabulary) use ($sortPriority) {
            return array_search($vocabulary->name, $sortPriority);
        });

        $tree = [];
        foreach ($sortedVocabularies as $vocabulary) {
            $element = [
                'text' => $vocabulary->display_name,
                'extra' => [
                    'uri' => $vocabulary->uri,
                    'vocab_uri' => $vocabulary->uri,
                ],
                'children' => $this->getTopNodes($vocabulary),
            ];

            $tree[] = $element;
        }

        return json_encode($tree, JSON_PRETTY_PRINT);
    }

    private function getTopNodes(Vocabulary $vocabulary): array
    {
        $topKeywords = $vocabulary->keywords->where('level', 1);
        $tree = [];

        foreach ($topKeywords as $topKeyword) {
            $element = [
                'text' => $topKeyword->label,
                'extra' => [
                    'uri' => $topKeyword->uri,
                    'vocab_uri' => $topKeyword->vocabulary->uri,
                    'external_uri' => $topKeyword->external_uri,
                    'external_vocab_scheme' => $topKeyword->external_vocab_scheme,
                ],
                'children' => $this->getChildren($topKeyword),
            ];

            $tree[] = $element;
        }

        return $tree;
    }

    private function getChildren(Keyword $keyword): array
    {
        $children = $keyword->getChildren();
        $tree = [];

        foreach ($children as $child) {
            $childTree = [
                'text' => $child->label,
                'extra' => [
                    'uri' => $child->uri,
                    'vocab_uri' => $child->vocabulary->uri,
                    'external_uri' => $child->external_uri,
                    'external_vocab_scheme' => $child->external_vocab_scheme,
                ],
                'children' => $this->getChildren($child),
            ];

            $tree[] = $childTree;
        }

        return $tree;
    }

    private function versionFileName($version): array|string
    {
        return str_replace('.', '-', $version);
    }
}
