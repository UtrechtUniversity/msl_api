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
        
        $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);

        $counter = 0;
        foreach ($dataset->msl_related_identifiers as $entry) {
            if(isset($entry['msl_related_identifier'])){
                ($entry['msl_related_identifier'] != "" ? $counter++ : false);
            }
        }

        $this->assertEquals(sizeof($dataset->msl_related_identifiers), $counter);

        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_relation_type"            ],    "IsCitedBy");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier"                          ],    "ark:/13030/tqb3kh97gh8w");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_resource_type_general"    ],    "Audiovisual");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_type"                     ],    "ARK");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_relation_type"            ],    "Cites");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier"                          ],    "arXiv:0706.0001");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_resource_type_general"    ],    "Book");
        $this->assertEquals($dataset->msl_related_identifiers [1]["msl_related_identifier_type"                     ],    "arXiv");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_relation_type"            ],    "IsSupplementedBy");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier"                          ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_resource_type_general"    ],    "Collection");
        $this->assertEquals($dataset->msl_related_identifiers [2]["msl_related_identifier_type"                     ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_relation_type"            ],    "IsContinuedBy");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier"                          ],    "9783468111242");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_resource_type_general"    ],    "ComputationalNotebook");
        $this->assertEquals($dataset->msl_related_identifiers [3]["msl_related_identifier_type"                     ],    "EAN13");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_relation_type"            ],    "Continues");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier"                          ],    "1562-6865");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_resource_type_general"    ],    "ConferencePaper");
        $this->assertEquals($dataset->msl_related_identifiers [4]["msl_related_identifier_type"                     ],    "EISSN");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_relation_type"            ],    "Describes");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier"                          ],    "10013/epic.10033");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_resource_type_general"    ],    "ConferenceProceeding");
        $this->assertEquals($dataset->msl_related_identifiers [5]["msl_related_identifier_type"                     ],    "Handle");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_relation_type"            ],    "IsDescribedBy");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier"                          ],    "IECUR0097");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_resource_type_general"    ],    "DataPaper");
        $this->assertEquals($dataset->msl_related_identifiers [6]["msl_related_identifier_type"                     ],    "IGSN");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_relation_type"            ],    "HasMetadata");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier"                          ],    "978-3-905673-82-1");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_resource_type_general"    ],    "Dataset");
        $this->assertEquals($dataset->msl_related_identifiers [7]["msl_related_identifier_type"                     ],    "ISBN");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_relation_type"            ],    "IsMetadataFor");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier"                          ],    "0077-5606");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_resource_type_general"    ],    "Dissertation");
        $this->assertEquals($dataset->msl_related_identifiers [8]["msl_related_identifier_type"                     ],    "ISSN");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_relation_type"            ],    "IsNewVersionOf");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier"                          ],    "urn:lsid:ubio.org:namebank:11815");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_resource_type_general"    ],    "InteractiveResource");
        $this->assertEquals($dataset->msl_related_identifiers [9]["msl_related_identifier_type"                     ],    "LSID");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_relation_type"           ],    "IsPreviousVersionOf");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier"                         ],    "12082125");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_resource_type_general"   ],    "Journal");
        $this->assertEquals($dataset->msl_related_identifiers [10]["msl_related_identifier_type"                    ],    "PMID");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_relation_type"           ],    "IsPartOf");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier"                         ],    "http://purl.oclc.org/foo/bar");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_resource_type_general"   ],    "JournalArticle");
        $this->assertEquals($dataset->msl_related_identifiers [11]["msl_related_identifier_type"                    ],    "PURL");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_relation_type"           ],    "HasPart");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier"                         ],    "123456789999");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_resource_type_general"   ],    "Model");
        $this->assertEquals($dataset->msl_related_identifiers [12]["msl_related_identifier_type"                    ],    "UPC");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_relation_type"           ],    "IsPublishedIn");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier"                         ],    "http://www.heatflow.und.edu/index2.html");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_resource_type_general"   ],    "OutputManagementPlan");
        $this->assertEquals($dataset->msl_related_identifiers [13]["msl_related_identifier_type"                    ],    "URL");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_relation_type"           ],    "IsReferencedBy");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier"                         ],    "urn:nbn:de:101:1-201102033592");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_resource_type_general"   ],    "PeerReview");
        $this->assertEquals($dataset->msl_related_identifiers [14]["msl_related_identifier_type"                    ],    "URN"    );
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_relation_type"           ],    "IsDerivedFrom");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [15]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_relation_type"           ],    "IsSourceOf");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [16]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_relation_type"           ],    "IsRequiredBy");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [17]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_relation_type"           ],    "Requires");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [18]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_relation_type"           ],    "Obsoletes");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [19]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_relation_type"           ],    "IsObsoletedBy");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [20]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_relation_type"           ],    "Collects");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [21]["msl_related_identifier_type"                    ],    "DOI");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_relation_type"           ],    "IsCollectedBy");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier"                         ],    "10.1016/j.epsl.2011.11.037");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_resource_type_general"   ],    "Other");
        $this->assertEquals($dataset->msl_related_identifiers [22]["msl_related_identifier_type"                    ],    "DOI");


        // new test
        $sourceData = new SourceDataset();

        $sourceData->source_dataset = '
            {
                "data": {
                    "id": "10.1594/pangaea.937090",
                    "type": "dois",
                    "attributes": {
                        "relatedIdentifiers": [
                            {
                                "relationType": "IsSupplementTo",
                                "relatedIdentifier": "10.1007/s10346-021-01787-2",
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
        
        $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);

        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_relation_type"           ],    "IsSupplementTo");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier"                         ],    "10.1007/s10346-021-01787-2");
        $this->assertEquals($dataset->msl_related_identifiers [0]["msl_related_identifier_type"                    ],    "DOI");


                // new test
                $sourceData = new SourceDataset();

                $sourceData->source_dataset = '
                    {
                        "data": {
                            "id": "10.1594/pangaea.937090",
                            "type": "dois",
                            "attributes": {
                                "relatedIdentifiers": [
                                ]
                            }
                        }
                    }';
        
                $dataciteMapper = new Datacite4Mapper();
        
                // create empty data publication
                $dataset = new DataPublication;
        
                // read json text
                $metadata = json_decode($sourceData->source_dataset, true);
                
                $dataset = $dataciteMapper->mapRelatedIdentifier($metadata, $dataset);
        
                $this->assertEquals($dataset->msl_related_identifiers,    []);
                
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
