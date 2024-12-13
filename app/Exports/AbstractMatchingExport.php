<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Mappers\Helpers\KeywordHelper;

class AbstractMatchingExport implements FromCollection, WithHeadings, WithMapping
{   
    public $repositoryId;
    
    public function __construct($repositoryId)
    {
        $this->repositoryId = $repositoryId;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $datasetSource = $this->repositoryId;

        $client = new Client();
        $searchRequest = new PackageSearchRequest();
        $searchRequest->query = 'type:data-publication';
        $searchRequest->addFilterQuery('owner_org', $datasetSource);
        $searchRequest->rows = 10;

        $result = $client->get($searchRequest);
        $results = $result->getResults();
        
        $keywordHelper = new KeywordHelper();
        
        $data = [];
        foreach ($results as $result) {
            $item = [];
            $item['identifier'] = $result['msl_doi'];
            $item['abstract'] = $result['notes'];    
            $item['title'] = $result['title'];
            $keywords = $keywordHelper->extractFromText($item['abstract'] . ' ' . $item['title']);
            $items = [];
            
            foreach ($keywords as $keyword) {
                $items[] = $keyword->getFullPath('>', true);
            }
            
            $item['keywords'] = implode(PHP_EOL, $items);
            
            $data[] = $item;
        }
        
        $collection = collect($data);
        
        return $collection;
    }
    
    public function headings(): array
    {
        return [
            'identifier',
            'title',
            'abstract',
            'keywords'
        ];
    }
    
    public function map($keywords): array
    {
        return [
            $keywords['identifier'],
            $keywords['title'],
            $keywords['abstract'],
            $keywords['keywords']
        ];
    }

}
