<?php

namespace Tests\Unit;

use App\Models\SourceDataset;
use PHPUnit\Framework\TestCase;
use App\Models\Ckan\DataPublication;
use App\Mappers\Datacite\Datacite4Mapper;

class Datacite4Test extends TestCase
{

    
    /**
     * test if title is correctly mapped
     */
    public function test_title_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [                            
                            {
                                "lang": "es",
                                "title": "Example Title spanish"
                            },
                            {   
                                "lang": "",
                                "title": "Example Title no lang"
                            },
                            {
                                "title": "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy"
                            },
                            {   
                                "lang": "en",
                                "title": "Example Title with lang english"
                            },
                            {
                                "lang": "en",
                                "title": "Example Subtitle",
                                "titleType": "Subtitle"
                            },
                            {
                                "lang": "fr",
                                "title": "Example TranslatedTitle",
                                "titleType": "TranslatedTitle"
                            },
                            {
                                "lang": "en",
                                "title": "Example AlternativeTitle",
                                "titleType": "AlternativeTitle"
                            }
                        ]
                    }
                }
            }';


        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy");
        

        // Next test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [
                            {
                                "lang": "en",
                                "title": "Example Title"
                            },
                            {
                                "lang": "en",
                                "title": "Example Subtitle",
                                "titleType": "Subtitle"
                            },
                            {
                                "lang": "fr",
                                "title": "Example TranslatedTitle",
                                "titleType": "TranslatedTitle"
                            },
                            {
                                "lang": "en",
                                "title": "Example AlternativeTitle",
                                "titleType": "AlternativeTitle"
                            }
                        ]
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Example Title");


        // Next test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "titles": [
                            {
                                "title": "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy"
                            }
                        ]
                    }
                }
            }';

        $dataciteMapper = new Datacite4Mapper();

        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceData->source_dataset, true);
        
        $dataset = $dataciteMapper->mapTitle($metadata, $dataset);

        $this->assertEquals($dataset->title, "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy");
    }
}
