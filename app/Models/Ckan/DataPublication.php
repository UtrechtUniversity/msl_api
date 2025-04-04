<?php
namespace App\Models\Ckan;

use Exception;

class DataPublication
{

    /**
     * title of the data publication
     */
    public string $title;

    /** 
     * annotated title of the data publication
     * elements used to display matched keywords within the original title are added during keyword detection
     */
    public string $msl_title_annotated;

    /**
     * data type used in CKAN
     */
    public string $type = 'data-publication';

    /**
     * link to landingpage
     */    
    public string $msl_source;

    /**
     * unique name of the data publication
     */
    public string $name;

    /**
     * A description of the resource.
     */
    public string $msl_resource_type;

    /**
     * The general type of a resource
     */
    public string $msl_resource_type_general;

    /**
     * datapackage visability in CKAN
     */
    public bool $private = false;

    /**
     * name of organization in CKAN data publication belongs to
     */
    public string $owner_org;

    /**
     * abstract text
     */
    public string $msl_description_abstract;

    /**
     * abstract text annotated, includes elements to display keyword matches
     */
    public string $msl_description_abstract_annotated;

    /**
     * methods 
     */
    public string $msl_description_methods;

    /**
     * methods annotated, includes elements to display keyword matches
     */
    public string $msl_description_methods_annotated;

    /**
     * series information
     */
    public string $msl_description_series_information;
    
    /**
     * series information annotated, includes elements to display keyword matches
     */
    public string $msl_description_series_information_annotated;

    /**
     * table of contents
     */
    public string $msl_description_table_of_contents;

    /**
     * table of contents annotated, includes elements to display keyword matches
     */
    public string $msl_description_table_of_contents_annotated;

    /**
     * technical info
     */
    public string $msl_description_technical_info;

    /**
     * technical info annotated, includes elements to display keyword matches
     */
    public string $msl_description_technical_info_annotated;

    /**
     * other description
     */
    public string $msl_description_other;

    /**
     * other description annotated, includes elements to display keyword matches
     */
    public string $msl_description_other_annotated;

    /**
     * list of rights / licenses
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
     */
    public string $msl_publication_year;

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

    /**
     * The main researchers involved in producing the data, or the authors of the publication, in priority order.
     * May be a corporate/institutional or personal name.
     */
    public array $msl_creators = [];

    public $msl_publication_date;

    /**
     * The institution(s) or person(s) responsible for collecting, managing, distributing, or otherwise contributing to the development of the resource.
     */
    public array $msl_contributors = [];

    /**
     * Size (e.g., bytes, pages, inches, etc.) or duration (extent), e.g., hours, minutes, days, etc., of a resource.
     * This information does need to match the file references we find.
     */
    public array $msl_sizes = [];

    /**
     * Technical format of the resource.
     */
    public array $msl_formats = [];

    /**
     * The version number of the resource. Is not definitive information as multiple variants for storing versioning information are available.
     */
    public string $msl_datacite_version = "";

    public $license_id;

    public $msl_points_of_contact = [];

    public $msl_laboratories = [];

    public $msl_downloads = [];

    /**
     * The name of the entity that holds, archives, publishes, prints, distributes, releases, issues, or produces the resource. 
     * This property will be used to formulate the citation, so consider the prominence of the role.
     */
    public string $msl_publisher;

    /**
     * Citation string for data publication
     */
    public string $msl_citation;

    public $msl_collection_period = [];

    /**
     * Location related fields
     */
    public $msl_spatial_coordinates = [];
    
    public $msl_geojson_featurecollection;
    
    public $msl_geojson_featurecollection_points;
    
    public $msl_surface_area = 0;

    public $msl_geolocations = [];

    /**
     * keyword related fields
     */

    public $tag_string = [];
    
    /**
     * Tags/subjects/keywords originally assigned by the author(s)
     */
    public array $msl_tags = [];

    /**
     * sub domains
     */
    public array $msl_subdomains = [];

    /**
     * originally assigned sub domains
     */
    public array $msl_subdomains_original = [];

    /**
     * interpreted sub domains
     */
    public array $msl_subdomains_interpreted = [];

    /**
     * enriched keyword
     */
    public array $msl_enriched_keywords = [];

    /**
     * original keywords translated to msl keywords
     */
    public array $msl_original_keywords = [];    

    /**
     * Fields listed below are used to provide top level filtering in the data-access filtertree navigation     
     */
    public bool $msl_has_material = false;

    public bool $msl_has_material_original = false;

    public bool $msl_has_porefluid = false;

    public bool $msl_has_porefluid_original = false;

    public bool $msl_has_rockphysic = false;

    public bool $msl_has_rockphysic_original = false;

    public bool $msl_has_analogue = false;

    public bool $msl_has_analogue_original = false;

    public bool $msl_has_geologicalage = false;

    public bool $msl_has_geologicalage_original = false;

    public bool $msl_has_geologicalsetting = false;

    public bool $msl_has_geologicalsetting_original = false;

    public bool $msl_has_paleomagnetism = false;

    public bool $msl_has_paleomagnetism_original = false;

    public bool $msl_has_geochemistry = false;

    public bool $msl_has_geochemistry_original = false;

    public bool $msl_has_microscopy = false;

    public bool $msl_has_microscopy_original = false;

    public bool $msl_has_subsurface = false;

    public bool $msl_has_subsurface_original = false;

    public bool $msl_has_geoenergy = false;

    public bool $msl_has_geoenergy_original = false;
    
    public bool $msl_has_lab = false;

    public bool $msl_has_organization = true;
    
    /**
     * Validation rules to be used after mapping stage of importing data. If rules fail processing of this dataset will be stopped.
     */
    public static array $importingRules = [
        'title' => 'required',
        'msl_creators' => 'required'
    ];

    /**
     * Add Right object to msl_rights
     * @param Rigth $right
     */
    public function addRight(Right $right): void
    {
        $this->msl_rights[] = $right;            
    }

    /**
     * Add AlternateIdentifier object to msl_alternate_identifiers
     * @param AlternateIdentifier $alternateIdentifier
     */
    public function addAlternateIdentifier(AlternateIdentifier $alternateIdentifier): void
    {
        $this->msl_alternate_identifiers[] = $alternateIdentifier;
    }

    /**
     * Add RelatedIdentifier object to msl_related_identifiers
     * @param RelatedIdentifier $relatedIdentifier
     */
    public function addRelatedIdentifier(RelatedIdentifier $relatedIdentifier): void
    {
        $this->msl_related_identifiers[] = $relatedIdentifier;
    }

    /**
     * Add FundingReference to msl_funding_references
     * @param FundingReference $fundingReference
     */
    public function addFundingReference(FundingReference $fundingReference): void
    {
        $this->msl_funding_references[] = $fundingReference;
    }

    /**
     * Add Date to msl_dates
     * @param Date $date
     */
    public function addDate(Date $date): void
    {
        $this->msl_dates[] = $date;
    }

    /**
     * Add Creator to msl_creators
     * @param Creator $creator
     */
    public function addCreator(Creator $creator): void
    {
        $this->msl_creators[] = $creator;
    }

    /**
     * Add Contributor to msl_contributors
     * @param Contributor $contributor
     */
    public function addContributor(Contributor $contributor): void
    {
        $this->msl_contributors[] = $contributor;
    }

    /**
     * Add size to msl_sizes
     * @param string $size
     */
    public function addSize(string $size): void
    {
        $this->msl_sizes[] = $size;
    }

    /**
     * Add format to msl_formats
     * @param string $format
     */
    public function addFormat(string $format): void
    {
        $this->msl_formats[] = $format;
    }
        
    /**
     * Add Tag to msl_tags if no existing tag with same msl_tag_string exists
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $exists = false;
        foreach ($this->msl_tags as $existingTag) {
            if($existingTag->msl_tag_string == $tag->msl_tag_string) {
                $exists = true;
                break;
            }
        }
        
        if(!$exists) {
            $this->msl_tags[] = $tag;
        }
    }
    
    /**
     * Add msl uri to Tag by tag string
     * @param string $tagString
     * @param string $uri
     */
    public function addUriToTag(string $tagString, string $uri): void
    {
        foreach ($this->msl_tags as &$tag) {
            if($tag->msl_tag_string == $tagString) {
                if(! in_array($uri, $tag->msl_tag_msl_uris)) {
                    $tag->msl_tag_msl_uris[] = $uri;
                }
            }
        }
    }

    /**
     * Add sub domain to data publication. msl_subdomain will also contain the new value, original/interpreted is indicated by parameter.
     * @param string $subdomain
     * @param bool $orginal
     */
    public function addSubDomain(string $subDomain, bool $original = true): void
    {
        // add sub domain if it is valid
        if(in_array($subDomain, config('subdomains.full_names'))) {
            if (! $this->hasSubDomain($subDomain)) {
                $this->msl_subdomains[] = [
                    'msl_subdomain' => $subDomain
                ];
            }
            if ($original) {
                if (! $this->hasOriginalSubDomain($subDomain)) {
                    $this->msl_subdomains_original[] = [
                        'msl_subdomain_original' => $subDomain
                    ];
                }
            } else {
                if (! $this->hasInterpretedSubDomain($subDomain)) {
                    $this->msl_subdomains_interpreted[] = [
                        'msl_subdomain_interpreted' => $subDomain
                    ];
                }
            }
        } else {
            throw new Exception('attempt to add invalid subdomain');
        }
    }

    /**
     * Check if sub domain is included in data publication
     * @param string $subDomain
     */
    public function hasSubDomain(string $subDomain): bool
    {
        foreach ($this->msl_subdomains as $key => $value) {
            if ($value['msl_subdomain'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if orginal sub domain is included in data publication
     * @param string $subDomain
     */
    public function hasOriginalSubDomain(string $subDomain): bool
    {
        foreach ($this->msl_subdomains_original as $key => $value) {
            if ($value['msl_subdomain_original'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if interpreted sub domain is included in data publication
     * @param string $subDomain
     */
    public function hasInterpretedSubDomain(string $subDomain): bool
    {
        foreach ($this->msl_subdomains_interpreted as $key => $value) {
            if ($value['msl_subdomain_interpreted'] == $subDomain) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add original keyword.
     * @param OriginalKeyword $keyword
     */
    public function addOriginalKeyword(OriginalKeyword $keyword): void
    {
        if (! $this->hasOriginalKeyword($keyword->msl_original_keyword_uri)) {
            $this->msl_original_keywords[] = $keyword;
            $this->setHasVocabKeyword('original', $keyword->msl_original_keyword_vocab_uri);
        }
    }

    /**
     * Add enriched keyword, if enriched keyword with the same uri exists merge associated subdomains, match locations and match child uris
     * @param EnrichedKeyword $keyword
     */
    public function addEnrichedKeyword(EnrichedKeyword $keyword): void
    {
        $exists = false;
        foreach ($this->msl_enriched_keywords as &$existingKeyword) {
            if($existingKeyword->msl_enriched_keyword_uri == $keyword->msl_enriched_keyword_uri) {
                //add associated subdomain(s)
                foreach ($keyword->msl_enriched_keyword_associated_subdomains as $associatedSubDomain) {
                    if(! in_array($associatedSubDomain, $existingKeyword->msl_enriched_keyword_associated_subdomains)) {
                        $existingKeyword->msl_enriched_keyword_associated_subdomains[] = $associatedSubDomain;
                    }
                }
                                
                //add matchlocation(s)
                foreach ($keyword->msl_enriched_keyword_match_locations as $matchLocation) {
                    if(! in_array($matchLocation, $existingKeyword->msl_enriched_keyword_match_locations)) {
                        $existingKeyword->msl_enriched_keyword_match_locations[] = $matchLocation;
                    }
                }
                
                //add match child uri(s)
                foreach ($keyword->msl_enriched_keyword_match_child_uris as $matchChildUri) {
                    if(! in_array($matchChildUri, $existingKeyword->msl_enriched_keyword_match_child_uris)) {
                        $existingKeyword->msl_enriched_keyword_match_child_uris[] = $matchChildUri;
                    }
                }
                
                $exists = true;
                break;
            }            
        }
        
        if(!$exists) {            
            $this->msl_enriched_keywords[] = $keyword;
            $this->setHasVocabKeyword('enriched', $keyword->msl_enriched_keyword_vocab_uri);            
        }
    }

    /**
     * Check if original keyword exists by uri
     * @param string $uri
     */
    public function hasOriginalKeyword(string $uri): bool
    {
        foreach ($this->msl_original_keywords as $keyword) {
            if ($keyword->msl_original_keyword_uri == $uri) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if enriched keyword exists by uri
     * @param string $uri
     */
    public function hasEnrichedKeyword($uri)
    {
        foreach ($this->msl_enriched_keywords as $keyword) {
            if ($keyword->msl_enriched_keyword_uri == $uri) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set facet fields based upon vocabulary uri
     * @param string $type
     * @param string $vocabUri
     */
    private function setHasVocabKeyword(string $type, string $vocabUri): void
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

    /**
     * Convert this objects and its internal objects to an array confirming to the data-publication schema in CKAN.
     * @return array
     */
    public function toCkanArray(): array
    {
        $arr = [];

        foreach($this as $key => $value) {
            if(is_array($value)) {
                $subArr = [];
                foreach($value as $subValue) {
                    if(is_object($subValue)) {
                        if(class_implements($subValue, CkanArrayInterface::class)) {
                            $subArr[] = $subValue->toCkanArray();
                        } else {
                            $subArr[] = (array) $subValue;
                        }
                    } else {
                        $subArr[] = $subValue;
                    }
                }
                $arr[$key] = $subArr;
            } else if(is_object($value)) {

            } else {
                $arr[$key] = $value;
            }            
        }

        return $arr;
    }
}