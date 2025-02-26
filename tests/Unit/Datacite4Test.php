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
    public function test_relatedIdentifier_mapping(): void
    {
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "relatedIdentifiers": [
                            {
                                "relationType": "IsCitedBy",
                                "relatedIdentifier": "ark:/13030/tqb3kh97gh8w",
                                "resourceTypeGeneral": "Audiovisual",
                                "relatedIdentifierType": "ARK"
                            },
                            {
                                "relationType": "Cites",
                                "relatedIdentifier": "arXiv:0706.0001",
                                "resourceTypeGeneral": "Book",
                                "relatedIdentifierType": "arXiv"
                            },
                            {
                                "relationType": "IsSupplementedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Collection",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsContinuedBy",
                                "relatedIdentifier": "9783468111242",
                                "resourceTypeGeneral": "ComputationalNotebook",
                                "relatedIdentifierType": "EAN13"
                            },
                            {
                                "relationType": "Continues",
                                "relatedIdentifier": "1562-6865",
                                "resourceTypeGeneral": "ConferencePaper",
                                "relatedIdentifierType": "EISSN"
                            },
                            {
                                "relationType": "Describes",
                                "relatedIdentifier": "10013/epic.10033",
                                "resourceTypeGeneral": "ConferenceProceeding",
                                "relatedIdentifierType": "Handle"
                            },
                            {
                                "relationType": "IsDescribedBy",
                                "relatedIdentifier": "IECUR0097",
                                "resourceTypeGeneral": "DataPaper",
                                "relatedIdentifierType": "IGSN"
                            },
                            {
                                "relationType": "HasMetadata",
                                "relatedIdentifier": "978-3-905673-82-1",
                                "resourceTypeGeneral": "Dataset",
                                "relatedIdentifierType": "ISBN"
                            },
                            {
                                "relationType": "IsMetadataFor",
                                "relatedIdentifier": "0077-5606",
                                "resourceTypeGeneral": "Dissertation",
                                "relatedIdentifierType": "ISSN"
                            },
                            {
                                "relationType": "IsNewVersionOf",
                                "relatedIdentifier": "urn:lsid:ubio.org:namebank:11815",
                                "resourceTypeGeneral": "InteractiveResource",
                                "relatedIdentifierType": "LSID"
                            },
                            {
                                "relationType": "IsPreviousVersionOf",
                                "relatedIdentifier": "12082125",
                                "resourceTypeGeneral": "Journal",
                                "relatedIdentifierType": "PMID"
                            },
                            {
                                "relationType": "IsPartOf",
                                "relatedIdentifier": "http://purl.oclc.org/foo/bar",
                                "resourceTypeGeneral": "JournalArticle",
                                "relatedIdentifierType": "PURL"
                            },
                            {
                                "relationType": "HasPart",
                                "relatedIdentifier": "123456789999",
                                "resourceTypeGeneral": "Model",
                                "relatedIdentifierType": "UPC"
                            },
                            {
                                "relationType": "IsPublishedIn",
                                "relatedIdentifier": "http://www.heatflow.und.edu/index2.html",
                                "resourceTypeGeneral": "OutputManagementPlan",
                                "relatedIdentifierType": "URL"
                            },
                            {
                                "relationType": "IsReferencedBy",
                                "relatedIdentifier": "urn:nbn:de:101:1-201102033592",
                                "resourceTypeGeneral": "PeerReview",
                                "relatedIdentifierType": "URN"
                            },        
                            {
                                "relationType": "IsDerivedFrom",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsSourceOf",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsRequiredBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Requires",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Obsoletes",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsObsoletedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "Collects",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
                            },
                            {
                                "relationType": "IsCollectedBy",
                                "relatedIdentifier": "10.1016/j.epsl.2011.11.037",
                                "resourceTypeGeneral": "Other",
                                "relatedIdentifierType": "DOI"
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
