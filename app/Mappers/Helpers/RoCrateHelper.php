<?php
namespace App\Mappers\Helpers;

class RoCrateHelper
{

    /**
     * extract and return files defined within ro crate
     */
    public function getFiles(array $roCrate): array
    {
        if(array_key_exists('@graph', $roCrate)) {
            $graph = $roCrate['@graph'];

            $files = [];
            
            foreach($graph as $graphElement){
                if($graphElement['@type'] === 'File') {
                    $files[] = $graphElement;
                }
            }
        }

        return $files;
    }
}