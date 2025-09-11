<?php

namespace App\Console\Commands;

use App\Models\Keyword;
use App\Models\Vocabulary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateEditorExport extends Command
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
    public function handle()
    {
        $vocabularies = Vocabulary::where('version', $this->argument('version'))->get();
        $this->line($vocabularies->count() . " vocabularies found.");
        
        $path = 'editor_' . $this->versionFileName($this->argument('version')) . '.json';
        Storage::disk('public')->put($path, $this->export($vocabularies));
        
        $this->line("export generated");
        return 0;
    }
    
        
    private function export($vocabularies)
    {
        $tree = [];
        foreach ($vocabularies as $vocabulary) {
            $element = [
                'text' => $vocabulary->display_name,
                'extra' => [
                    'uri' => $vocabulary->uri,
                    'vocab_uri' => $vocabulary->uri
                ],
                'children' => $this->getTopNodes($vocabulary)
            ];
            
            $tree[] = $element;
        }
                                
        return (json_encode($tree, JSON_PRETTY_PRINT));
    }
    
    private function getTopNodes(Vocabulary $vocabulary) {
        $topKeywords = $vocabulary->keywords->where('level', 1);        
        $tree = [];
                        
        foreach ($topKeywords as $topKeyword) {
            $element = [
                'text' => $topKeyword->label,
                'extra' => [
                    'uri' => $topKeyword->uri,
                    'vocab_uri' => $topKeyword->vocabulary->uri
                ],
                'children' => $this->getChildren($topKeyword)
            ];
            
            $tree[] = $element;
        }
        
        return $tree;
    }
    
    private function getChildren(Keyword $keyword)
    {
        $children = $keyword->getChildren();
        $tree = [];
        
        foreach ($children as $child) {
            $childTree = [
                'text' => $child->label,
                'extra' => [
                    'uri' => $child->uri,
                    'vocab_uri' => $child->vocabulary->uri
                ],
                'children' => $this->getChildren($child)                
            ];
            
            $tree[] = $childTree;
        }
        
        return $tree;
    }
    
    private function versionFileName($version) {
        return str_replace('.', '-', $version);
    }
}
