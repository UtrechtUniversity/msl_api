<?php
namespace App\Datasets;

class BaseDataset
{

    public $title;

    public $msl_title_annotated;

    public $type = 'data-publication';

    public $msl_subdomains = [];

    public $msl_subdomains_original = [];

    public $msl_subdomains_interpreted = [];

    public $msl_source;

    public $name;

    public $private = false;

    public $owner_org;

    public $notes;

    public $msl_notes_annotated;

    public $msl_technical_description;

    public $msl_doi;

    public $msl_handle;

    public $msl_publication_day;

    public $msl_publication_month;

    public $msl_publication_year;

    public $msl_publication_date;

    public $msl_authors = [];

    public $msl_contributors = [];

    public $msl_references = [];

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
    
    public function addTag($tagString, $uris = []) {
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
    
    public function addUriToTag($tagString, $uri) {
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
                throw new \Exception('attempt to add invalid subdomain');
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
                    throw new \Exception('invalid keyword type added');
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
                    throw new \Exception('invalid keyword type added');
            }
        }
    }

    public function addLab($lab)
    {
        $this->msl_laboratories[] = $lab;
        $this->msl_has_lab = true;
    }
}

