<?php

namespace Tests\Unit;

use App\Mappers\Datacite\Datacite4Mapper;
use App\Models\SourceDataset;
use PHPUnit\Framework\TestCase;

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
                                "lang": "en",
                                "title": "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy"
                            },
                            {
                                "lang": "en",
                                "title": "Example Title"
                            },
                            {
                                "lang": "es",
                                "title": "Example Title spanish"
                            },
                            {   
                                "lang": "es",
                                "title": "Example Title no lang"
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
        

        // $sourceData->source_dataset = '
        // {
        //     "data": {
        //         "id": "10.1594/pangaea.937090",
        //         "type": "dois",
        //         "attributes": {
        //             "titles": [
        //                 {
        //                     "lang": "en",
        //                     "title": "Example Title"
        //                 },
        //                 {
        //                     "lang": "en",
        //                     "title": "Example Subtitle",
        //                     "titleType": "Subtitle"
        //                 },
        //                 {
        //                     "lang": "fr",
        //                     "title": "Example TranslatedTitle",
        //                     "titleType": "TranslatedTitle"
        //                 },
        //                 {
        //                     "lang": "en",
        //                     "title": "Example AlternativeTitle",
        //                     "titleType": "AlternativeTitle"
        //                 }
        //             ]
        //         }
        //     }
        // }';

        $dataciteMapper = new Datacite4Mapper();
        
        $dataset = $dataciteMapper->map($sourceData);

        $this->assertEquals($dataset->title, "Sedimentological and geochemical data of Lago di Vedana, north-eastern Italy");
        
    }
}
