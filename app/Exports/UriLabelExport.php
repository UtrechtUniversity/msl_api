<?php
namespace App\Exports;

use App\Models\Keyword;
use App\Models\Vocabulary;

class UriLabelExport
{
    
    
    public function export() {
        
        $vocabularies = Vocabulary::where('version', config('vocabularies.vocabularies_current_version'))->get();
        
        $uriLabels = [];
        
        foreach ($vocabularies as $vocabulary) {
            foreach ($vocabulary->keywords as $keyword) {
                $uriLabels[$keyword->uri] = $keyword->label;
            }
        }
                        
        return (json_encode($uriLabels, JSON_PRETTY_PRINT));
    }
    
    
    
    
}