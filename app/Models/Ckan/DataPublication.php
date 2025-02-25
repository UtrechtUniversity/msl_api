<?php
namespace App\Models\Ckan;

use Exception;

class DataPublication
{

    /**
     * title of the data publication
     * @var string
     */
    public string $title;

    /** 
     * annotated title of the data publication
     * elements used to display matched keywords within the original title are added during keyword detection
     * @var string
     */
    public string $msl_title_annotated;

    /**
     * data type used in CKAN
     * @var string
     */
    public string $type = 'data-publication';

    public $msl_subdomains = [];

    public $msl_subdomains_original = [];

    public $msl_subdomains_interpreted = [];

    /**
     * link to landingpage
     */    
    public string $msl_source;

    public $name;

    /**
     * datapackage visability in CKAN
     * @var bool
     */
    public bool $private = false;

    /**
     * name of organization in CKAN data publication should belong to
     * @var string
     */
    public string $owner_org;

    /**
     * abstract text
     * @var string
     */
    public string $msl_description_abstract;

    /**
     * abstract text annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_abstract_annotated;

    /**
     * methods 
     * @var string
     */
    public string $msl_description_methods;

    /**
     * methods annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_methods_annotated;

    /**
     * series information
     * @var string
     */
    public string $msl_description_series_information;
    
    /**
     * series information annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_series_information_annotated;

    /**
     * table of contents
     * @var string
     */
    public string $msl_description_table_of_contents;

    /**
     * table of contents annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_table_of_contents_annotated;

    /**
     * technical info
     * @var string
     */
    public string $msl_description_technical_info;

    /**
     * technical info annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_technical_info_annotated;

    /**
     * other description
     * @var string
     */
    public string $msl_description_other;

    /**
     * other description annotated, includes elements to display keyword matches
     * @var string
     */
    public string $msl_description_other_annotated;

    /**
     * list of rights / licenses
     * @var array
     */
    public array $msl_rights = [];

    /**
     * doi of the data publication
     */
    public string $msl_doi;

    /**
     * list of alternate identifiers
     */
    public array $msl_alternate_identifiers = [];

    /**
     * list of related identifiers
     */
    public array $msl_related_identifiers = [];

    public $msl_handle;

    public $msl_publication_day;

    public $msl_publication_month;

    /**
     * year of publication
     * @var ?string
     */
    public ?string $msl_publication_year;

    /**
     * References to sources of funding
     */
    public array $msl_funding_references = [];

    /**
     * primary language
     */
    public string $msl_language;

    /**
     * storage for several types of dates
     */
    public array $msl_dates = [];

    public $msl_publication_date;

    public $msl_authors = [];

    public $msl_contributors = [];

    public $tag_string = [];
    
    public $msl_tags = [];

    public $msl_spatial_coordinates = [];
    
    public $msl_geojson_featurecollection;
    
    public $msl_geojson_featurecollection_points;
    
    public $msl_surface_area = 0;

    public $msl_geolocations = [];

    public $license_id;

    public $msl_points_of_contact = [];

    public $msl_laboratories = [];

    public $msl_downloads = [];

    public $msl_publisher;

    public $msl_citation;

    public $msl_collection_period = [];

    // vocabulary/keyword related fields
    public $msl_has_material = false;

    public $msl_has_material_original = false;

    public $msl_has_porefluid = false;

    public $msl_has_porefluid_original = false;

    public $msl_has_rockphysic = false;

    public $msl_has_rockphysic_original = false;

    public $msl_has_analogue = false;

    public $msl_has_analogue_original = false;

    public $msl_has_geologicalage = false;

    public $msl_has_geologicalage_original = false;

    public $msl_has_geologicalsetting = false;

    public $msl_has_geologicalsetting_original = false;

    public $msl_has_paleomagnetism = false;

    public $msl_has_paleomagnetism_original = false;

    public $msl_has_geochemistry = false;

    public $msl_has_geochemistry_original = false;

    public $msl_has_microscopy = false;

    public $msl_has_microscopy_original = false;

    public $msl_has_subsurface = false;

    public $msl_has_subsurface_original = false;

    public $msl_has_geoenergy = false;

    public $msl_has_geoenergy_original = false;

    public $msl_enriched_keywords = [];

    public $msl_original_keywords = [];

    public $msl_has_lab = false;

    public $msl_has_organization = true;
    
    /**
     * Validation rules to be used after mapping stage of importing data. If rules fail processing of this dataset will be stopped.
     * 
     * @var array
     */
    public static $importingRules = [
        'title' => 'required',
        'msl_authors' => 'required'
    ];

    public function addRight($right, $uri = "", $identifier = "", $identifierScheme = "", $schemeUri = ""): void
    {
        $this->msl_rights[] = [
            'msl_right' => $right,
            'msl_right_uri' => $uri,
            'msl_right_identifier' => $identifier,
            'msl_right_identifier_scheme' => $identifierScheme,
            'msl_right_scheme_uri' => $schemeUri
        ];
    }

    public function addAlternateIdentifier($identifier, $type): void
    {
        $this->msl_alternate_identifiers[] = [
            'msl_alternate_identifier' => $identifier,
            'msl_alternate_identifier_type' => $type
        ];
    }

    public function addRelatedIdentifier($identifier, $identifierType, $relationType, $metadataScheme = "", $metadataSchemeUri = "", $metadataSchemeType = "", $resourceType): void
    {
        $this->msl_related_identifiers[] = [
            'msl_related_identifier' => $identifier,
            'msl_related_identifier_type' => $identifierType,
            'msl_related_identifier_relation_type' => $relationType,
            'msl_related_identifier_metadata_scheme' => $metadataScheme,
            'msl_related_identifier_metadata_scheme_uri' => $metadataSchemeUri,
            'msl_related_identifier_metadata_scheme_type' => $metadataSchemeType,
            'msl_related_identifier_resource_type_general' => $resourceType,
        ];
    }

    public function addFundingReference($funderName, $funderIdentifier = "", $funderIdentifierType = "", $schemeUri = "", $awardNumber = "", $awardUri = "", $awardTitle = ""): void
    {
        $this->msl_funding_references[] = [
            'msl_funding_reference_funder_name' => $funderName,
            'msl_funding_reference_funder_identifier' => $funderIdentifier,
            'msl_funding_reference_funder_identifier_type' => $funderIdentifierType,
            'msl_funding_reference_scheme_uri' => $schemeUri,            
            'msl_funding_reference_award_number' => $awardNumber,
            'msl_funding_reference_award_uri' => $awardUri,
            'msl_funding_reference_award_title' => $awardTitle,
        ];
    }

    public function addDate($date, $type, $information = ""): void
    {
        $this->msl_dates[] = [
            'msl_date_date' => $date,
            'msl_date_type' => $type,
            'msl_date_information' => $information
        ];
    }
    
    public function addTag($tagString, $uris = [])
    {
        $exists = false;
        foreach ($this->msl_tags as $tag) {
            if($tag['msl_tag_string'] == $tagString) {
                $exists = true;
                break;
            }
        }
        
        if(!$exists) {
            $this->msl_tags[] = [
                'msl_tag_string' => $tagString,
                'msl_tag_uris' => $uris
            ];
        }
    }
    
    public function addUriToTag($tagString, $uri)
    {
        foreach ($this->msl_tags as &$tag) {
            if($tag['msl_tag_string'] == $tagString) {
                if(!in_array($uri, $tag['msl_tag_uris'])) {
                    $tag['msl_tag_uris'][] = $uri;
                }
            }
        }
    }

    public function addSubDomain($subDomain, $original = true)
    {
        switch ($subDomain) {
            case "rock and melt physics":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'rock and melt physics'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'rock and melt physics'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'rock and melt physics'
                        ];
                    }
                }
                break;

            case "analogue modelling of geologic processes":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'analogue modelling of geologic processes'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'analogue modelling of geologic processes'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'analogue modelling of geologic processes'
                        ];
                    }
                }
                break;

            case "microscopy and tomography":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'microscopy and tomography'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'microscopy and tomography'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'microscopy and tomography'
                        ];
                    }
                }
                break;

            case "paleomagnetism":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'paleomagnetism'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'paleomagnetism'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'paleomagnetism'
                        ];
                    }
                }
                break;

            case "geochemistry":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'geochemistry'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'geochemistry'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'geochemistry'
                        ];
                    }
                }
                break;

            case "geo-energy test beds":
                if (! $this->hasSubDomain($subDomain)) {
                    $this->msl_subdomains[] = [
                        'msl_subdomain' => 'geo-energy test beds'
                    ];
                }
                if ($original) {
                    if (! $this->hasOriginalSubDomain($subDomain)) {
                        $this->msl_subdomains_original[] = [
                            'msl_subdomain_original' => 'geo-energy test beds'
                        ];
                    }
                } else {
                    if (! $this->hasInterpretedSubDomain($subDomain)) {
                        $this->msl_subdomains_interpreted[] = [
                            'msl_subdomain_interpreted' => 'geo-energy test beds'
                        ];
                    }
                }
                break;

            default:
                throw new Exception('attempt to add invalid subdomain');
        }
    }

    public function hasSubDomain($subDomain)
    {
        foreach ($this->msl_subdomains as $key => $value) {
            if ($value['msl_subdomain'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    public function hasOriginalSubDomain($subDomain)
    {
        foreach ($this->msl_subdomains_original as $key => $value) {
            if ($value['msl_subdomain_original'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    public function hasInterpretedSubDomain($subDomain)
    {
        foreach ($this->msl_subdomains_interpreted as $key => $value) {
            if ($value['msl_subdomain_interpreted'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    public function addOriginalKeyword($label, $uri = "", $vocabUri = "")
    {
        if (! $this->hasOriginalKeyword($uri)) {
            $this->msl_original_keywords[] = [
                'msl_original_keyword_label' => $label,
                'msl_original_keyword_uri' => $uri,
                'msl_original_keyword_vocab_uri' => $vocabUri
            ];
            $this->setHasVocabKeyword('original', $vocabUri);
        }
    }

    public function addEnrichedKeyword($label, $uri = "", $vocabUri = "", $associatedSubDomains = [], $matchLocations = [], $matchChildUris = [])
    {
        $exists = false;
        foreach ($this->msl_enriched_keywords as &$keyword) {
            if($keyword['msl_enriched_keyword_uri'] == $uri) {
                //add associated subdomain
                foreach ($associatedSubDomains as $associatedSubDomain) {
                    if(!in_array($associatedSubDomain, $keyword['msl_enriched_keyword_associated_subdomains'])) {
                        $keyword['msl_enriched_keyword_associated_subdomains'][] = $associatedSubDomain;
                    }
                }
                                
                //add matchlocation
                foreach ($matchLocations as $matchLocation) {
                    if(!in_array($matchLocation, $keyword['msl_enriched_keyword_match_locations'])) {
                        $keyword['msl_enriched_keyword_match_locations'][] = $matchLocation;
                    }
                }
                
                //add match child uris
                foreach ($matchChildUris as $matchChildUri) {
                    if(!in_array($matchChildUri, $keyword['msl_enriched_keyword_match_child_uris'])) {
                        $keyword['msl_enriched_keyword_match_child_uris'][] = $matchChildUri;
                    }
                }
                
                $exists = true;
                break;
            }
            
        }
        
        if(!$exists) {
            $enrichedKeyword = [
                'msl_enriched_keyword_label' => $label,
                'msl_enriched_keyword_uri' => $uri,
                'msl_enriched_keyword_vocab_uri' => $vocabUri,
                'msl_enriched_keyword_associated_subdomains' => $associatedSubDomains,
                'msl_enriched_keyword_match_locations' => $matchLocations,
                'msl_enriched_keyword_match_child_uris' => $matchChildUris
            ];
            
            $this->msl_enriched_keywords[] = $enrichedKeyword;
            $this->setHasVocabKeyword('enriched', $vocabUri);            
        }                
    }

    public function hasOriginalKeyword($uri)
    {
        foreach ($this->msl_original_keywords as $keyword) {
            if ($keyword['msl_original_keyword_uri'] == $uri) {
                return true;
            }
        }

        return false;
    }

    public function hasEnrichedKeyword($uri)
    {
        foreach ($this->msl_enriched_keywords as $keyword) {
            if ($keyword['msl_enriched_keyword_uri'] == $uri) {
                return true;
            }
        }

        return false;
    }

    private function setHasVocabKeyword($type, $vocabUri)
    {
        if ($type == 'enriched') {
            switch (true) {
                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/materials'):
                    $this->msl_has_material = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/porefluids'):
                    $this->msl_has_porefluid = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/rockphysics'):
                    $this->msl_has_rockphysic = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/analoguemodelling'):
                    $this->msl_has_analogue = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geologicalage'):
                    $this->msl_has_geologicalage = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geologicalsetting'):
                    $this->msl_has_geologicalsetting = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/paleomagnetism'):
                    $this->msl_has_paleomagnetism = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geochemistry'):
                    $this->msl_has_geochemistry = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/microscopy'):
                    $this->msl_has_microscopy = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/subsurface'):
                    $this->msl_has_subsurface = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/testbeds'):
                    $this->msl_has_geoenergy = true;
                    break;

                default:
                    throw new Exception('invalid keyword type added');
            }
        } elseif ($type == 'original') {
            switch (true) {
                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/materials'):
                    $this->msl_has_material_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/porefluids'):
                    $this->msl_has_porefluid_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/rockphysics'):
                    $this->msl_has_rockphysic_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/analoguemodelling'):
                    $this->msl_has_analogue_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geologicalage'):
                    $this->msl_has_geologicalage_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geologicalsetting'):
                    $this->msl_has_geologicalsetting_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/paleomagnetism'):
                    $this->msl_has_paleomagnetism_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/geochemistry'):
                    $this->msl_has_geochemistry_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/microscopy'):
                    $this->msl_has_microscopy_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/subsurface'):
                    $this->msl_has_subsurface_original = true;
                    break;

                case str_starts_with($vocabUri, 'https://epos-msl.uu.nl/voc/testbeds'):
                    $this->msl_has_geoenergy_original = true;
                    break;

                default:
                    throw new Exception('invalid keyword type added');
            }
        }
    }

    public function addLab($lab)
    {
        $this->msl_laboratories[] = $lab;
        $this->msl_has_lab = true;
    }
}

