<?php

namespace App\Models\Ckan;

use Exception;

class DataPublication
{
    /**
     * title of the data publication
     */
    public string $title = '';

    /**
     * annotated title of the data publication
     * elements used to display matched keywords within the original title are added during keyword detection
     */
    public string $msl_title_annotated = '';

    /**
     * data type used in CKAN
     */
    public string $type = 'data-publication';

    /**
     * link to landing page
     */
    public string $msl_source = '';

    /**
     * unique name of the data publication
     */
    public string $name = '';

    /**
     * A description of the resource.
     */
    public string $msl_resource_type = '';

    /**
     * The general type of resource
     */
    public string $msl_resource_type_general = '';

    /**
     * data package visibility in CKAN
     */
    public bool $private = false;

    /**
     * name of organization in CKAN data publication belongs to
     */
    public string $owner_org = '';

    /**
     * abstract text
     */
    public string $msl_description_abstract = '';

    /**
     * abstract text annotated, includes elements to display keyword matches
     */
    public string $msl_description_abstract_annotated = '';

    /**
     * methods
     */
    public string $msl_description_methods = '';

    /**
     * methods annotated, includes elements to display keyword matches
     */
    public string $msl_description_methods_annotated = '';

    /**
     * series information
     */
    public string $msl_description_series_information = '';

    /**
     * series information annotated, includes elements to display keyword matches
     */
    public string $msl_description_series_information_annotated = '';

    /**
     * table of contents
     */
    public string $msl_description_table_of_contents = '';

    /**
     * table of contents annotated, includes elements to display keyword matches
     */
    public string $msl_description_table_of_contents_annotated = '';

    /**
     * technical info
     */
    public string $msl_description_technical_info = '';

    /**
     * technical info annotated, includes elements to display keyword matches
     */
    public string $msl_description_technical_info_annotated = '';

    /**
     * other description
     */
    public string $msl_description_other = '';

    /**
     * other description annotated, includes elements to display keyword matches
     */
    public string $msl_description_other_annotated = '';

    /**
     * list of rights / licenses
     */
    public array $msl_rights = [];

    /**
     * doi of the data publication
     */
    public string $msl_doi = '';

    /**
     * list of alternate identifiers
     */
    public array $msl_alternate_identifiers = [];

    /**
     * list of related identifiers
     */
    public array $msl_related_identifiers = [];

    /**
     * year of publication
     */
    public string $msl_publication_year = '';

    /**
     * References to sources of funding
     */
    public array $msl_funding_references = [];

    /**
     * primary language
     */
    public string $msl_language = '';

    /**
     * storage for several types of dates
     */
    public array $msl_dates = [];

    /**
     * The main researchers involved in producing the data, or the authors of the publication, in priority order.
     * Maybe a corporate/institutional or personal name.
     */
    public array $msl_creators = [];

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
    public string $msl_datacite_version = '';

    public $msl_laboratories = [];

    /**
     * Files in data publication
     */
    public array $msl_files = [];

    /**
     * The name of the entity that holds, archives, publishes, prints, distributes, releases, issues, or produces the resource.
     * This property will be used to formulate the citation, so consider the prominence of the role.
     */
    public string $msl_publisher = '';

    /**
     * Citation string for data publication
     */
    public string $msl_citation = '';

    /**
     * Location related fields
     */

    /**
     * Spatial coordinates
     */
    public array $msl_spatial_coordinates = [];

    /**
     * Geojson feature collection string containing spatial features
     */
    public $msl_geojson_featurecollection = '';

    /**
     * Geojson feature collection string containing spatial features converted to points
     */
    public $msl_geojson_featurecollection_points = '';

    /**
     * Surface area of feature collection
     * Area calculated is just a rough proximation as projection is not taken into account
     */
    public $msl_surface_area = 0;

    /**
     * Textual described location
     */
    public array $msl_geolocations = [];

    /**
     * Extras field only used for SOLR processing of location data.
     * Not to be used by anything else
     */
    public array $extras = [];

    /**
     * keyword related fields
     */

    /**
     * CKAN original keyword/tags field
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
     * enriched keywords
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
        'msl_creators' => 'required',
    ];

    /**
     * Add Right object to msl_rights
     */
    public function addRight(Right $right): void
    {
        $this->msl_rights[] = $right;
    }

    /**
     * Add AlternateIdentifier object to msl_alternate_identifiers
     */
    public function addAlternateIdentifier(AlternateIdentifier $alternateIdentifier): void
    {
        $this->msl_alternate_identifiers[] = $alternateIdentifier;
    }

    /**
     * Add RelatedIdentifier object to msl_related_identifiers
     */
    public function addRelatedIdentifier(RelatedIdentifier $relatedIdentifier): void
    {
        $this->msl_related_identifiers[] = $relatedIdentifier;
    }

    /**
     * Add FundingReference to msl_funding_references
     */
    public function addFundingReference(FundingReference $fundingReference): void
    {
        $this->msl_funding_references[] = $fundingReference;
    }

    /**
     * Add Date to msl_dates
     */
    public function addDate(Date $date): void
    {
        $this->msl_dates[] = $date;
    }

    /**
     * Add Creator to msl_creators
     */
    public function addCreator(Creator $creator): void
    {
        $this->msl_creators[] = $creator;
    }

    /**
     * Add Contributor to msl_contributors
     */
    public function addContributor(Contributor $contributor): void
    {
        $this->msl_contributors[] = $contributor;
    }

    /**
     * Add size to msl_sizes
     */
    public function addSize(string $size): void
    {
        $this->msl_sizes[] = ['msl_size' => $size];
    }

    /**
     * Add format to msl_formats
     */
    public function addFormat(string $format): void
    {
        $this->msl_formats[] = ['msl_format' => $format];
    }

    /**
     * Add geolocation to msl_geolocations
     */
    public function addGeolocation(string $location): void
    {
        $this->msl_geolocations[] = ['msl_geolocation' => $location];
    }

    /**
     * Add geojson object to specific extras field for processing by spatial plugin
     */
    public function addLocationToExtras(string $location): void
    {
        $this->extras[] = ['key' => 'spatial', 'value' => $location];
    }

    /**
     * Add Tag to msl_tags if no existing tag with same msl_tag_string exists
     */
    public function addTag(Tag $tag): void
    {
        $exists = false;
        foreach ($this->msl_tags as $existingTag) {
            if ($existingTag->msl_tag_string == $tag->msl_tag_string) {
                $exists = true;
                break;
            }
        }

        if (! $exists) {
            $this->msl_tags[] = $tag;
        }
    }

    /**
     * Add msl uri to Tag by tag string
     */
    public function addUriToTag(string $tagString, string $uri): void
    {
        foreach ($this->msl_tags as &$tag) {
            if ($tag->msl_tag_string == $tagString) {
                if (! in_array($uri, $tag->msl_tag_msl_uris)) {
                    $tag->msl_tag_msl_uris[] = $uri;
                }
            }
        }
    }

    /**
     * Add sub domain to data publication. msl_subdomain will also contain the new value, original/interpreted is indicated by parameter.
     *
     * @param  string  $subdomain
     * @param  bool  $orginal
     */
    public function addSubDomain(string $subDomain, bool $original = true): void
    {
        // add sub domain if it is valid
        if (in_array($subDomain, config('subdomains.full_names'))) {
            if (! $this->hasSubDomain($subDomain)) {
                $this->msl_subdomains[] = [
                    'msl_subdomain' => $subDomain,
                ];
            }
            if ($original) {
                if (! $this->hasOriginalSubDomain($subDomain)) {
                    $this->msl_subdomains_original[] = [
                        'msl_subdomain_original' => $subDomain,
                    ];
                }
            } else {
                if (! $this->hasInterpretedSubDomain($subDomain)) {
                    $this->msl_subdomains_interpreted[] = [
                        'msl_subdomain_interpreted' => $subDomain,
                    ];
                }
            }
        } else {
            throw new Exception('attempt to add invalid subdomain');
        }
    }

    /**
     * Check if sub domain is included in data publication
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
     */
    public function addEnrichedKeyword(EnrichedKeyword $keyword): void
    {
        $exists = false;
        foreach ($this->msl_enriched_keywords as &$existingKeyword) {
            if ($existingKeyword->msl_enriched_keyword_uri == $keyword->msl_enriched_keyword_uri) {
                // add associated subdomain(s)
                foreach ($keyword->msl_enriched_keyword_associated_subdomains as $associatedSubDomain) {
                    if (! in_array($associatedSubDomain, $existingKeyword->msl_enriched_keyword_associated_subdomains)) {
                        $existingKeyword->msl_enriched_keyword_associated_subdomains[] = $associatedSubDomain;
                    }
                }

                // add matchlocation(s)
                foreach ($keyword->msl_enriched_keyword_match_locations as $matchLocation) {
                    if (! in_array($matchLocation, $existingKeyword->msl_enriched_keyword_match_locations)) {
                        $existingKeyword->msl_enriched_keyword_match_locations[] = $matchLocation;
                    }
                }

                // add match child uri(s)
                foreach ($keyword->msl_enriched_keyword_match_child_uris as $matchChildUri) {
                    if (! in_array($matchChildUri, $existingKeyword->msl_enriched_keyword_match_child_uris)) {
                        $existingKeyword->msl_enriched_keyword_match_child_uris[] = $matchChildUri;
                    }
                }

                $exists = true;
                break;
            }
        }

        if (! $exists) {
            $this->msl_enriched_keywords[] = $keyword;
            $this->setHasVocabKeyword('enriched', $keyword->msl_enriched_keyword_vocab_uri);
        }
    }

    /**
     * Add file object
     */
    public function addFile(File $file): void
    {
        $this->msl_files[] = $file;
    }

    /**
     * Check if original keyword exists by uri
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
     *
     * @param  string  $uri
     */
    public function hasEnrichedKeyword($uri): bool
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
     */
    public function toCkanArray(): array
    {
        $arr = [];

        foreach ($this as $key => $value) {
            if (is_array($value)) {
                $subArr = [];
                foreach ($value as $subValue) {
                    if (is_object($subValue)) {
                        if (class_implements($subValue, CkanArrayInterface::class)) {
                            $subArr[] = $subValue->toCkanArray();
                        } else {
                            $subArr[] = (array) $subValue;
                        }
                    } else {
                        $subArr[] = $subValue;
                    }
                }
                $arr[$key] = $subArr;
            } elseif (is_object($value)) {

            } else {
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

    public static function fromCkanArray(array $data): self
    {
        $dataPublication = new self;

        foreach ($data as $key => $value) {
            // CKAN sometimes adds the string '{}' for empty repeating fields.
            if ($value === '{}') {
                continue;
            }

            if ($value !== '') {
                if (! is_array($value)) {
                    if (property_exists($dataPublication, $key)) {
                        switch (gettype($dataPublication->{$key})) {
                            case 'integer':
                                $dataPublication->{$key} = (int) $value;
                                break;
                            case 'boolean':
                                if ($value === 'true') {
                                    $dataPublication->{$key} = true;
                                } else {
                                    $dataPublication->{$key} = false;
                                }
                                break;
                            default:
                                $dataPublication->{$key} = $value;
                                break;
                        }
                    }
                } else {
                    switch ($key) {
                        case 'msl_rights':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_rights[] = new Right(
                                    $subValue['msl_right'],
                                    $subValue['msl_right_uri'],
                                    $subValue['msl_right_identifier'],
                                    $subValue['msl_right_identifier_scheme'],
                                    $subValue['msl_right_scheme_uri'],
                                );
                            }
                            break;

                        case 'msl_alternate_identifiers':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_alternate_identifiers[] = new AlternateIdentifier(
                                    $subValue['msl_alternate_identifier'],
                                    $subValue['msl_alternate_identifier_type'],
                                );
                            }
                            break;

                        case 'msl_related_identifiers':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_related_identifiers[] = new RelatedIdentifier(
                                    $subValue['msl_related_identifier'],
                                    $subValue['msl_related_identifier_type'],
                                    $subValue['msl_related_identifier_relation_type'],
                                    $subValue['msl_related_identifier_metadata_scheme'],
                                    $subValue['msl_related_identifier_metadata_scheme_uri'],
                                    $subValue['msl_related_identifier_metadata_scheme_type'],
                                    $subValue['msl_related_identifier_resource_type_general'],
                                );
                            }
                            break;

                        case 'msl_funding_references':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_funding_references[] = new FundingReference(
                                    $subValue['msl_funding_reference_funder_name'],
                                    $subValue['msl_funding_reference_funder_identifier'],
                                    $subValue['msl_funding_reference_funder_identifier_type'],
                                    $subValue['msl_funding_reference_scheme_uri'],
                                    $subValue['msl_funding_reference_award_number'],
                                    $subValue['msl_funding_reference_award_uri'],
                                    $subValue['msl_funding_reference_award_title'],
                                );
                            }
                            break;

                        case 'msl_dates':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_dates[] = new Date(
                                    $subValue['msl_date_date'],
                                    $subValue['msl_date_type'],
                                    $subValue['msl_date_information'],
                                );
                            }
                            break;

                        case 'msl_creators':
                            foreach ($value as $subValue) {
                                $creator = new Creator(
                                    $subValue['msl_creator_name'],
                                    $subValue['msl_creator_given_name'],
                                    $subValue['msl_creator_family_name'],
                                    $subValue['msl_creator_name_type'],
                                );

                                for ($i = 0; $i < count($subValue['msl_creator_name_identifiers']); $i++) {
                                    $creator->addNameIdentifier(
                                        new NameIdentifier(
                                            $subValue['msl_creator_name_identifiers'][$i],
                                            $subValue['msl_creator_name_identifiers_schemes'][$i],
                                            $subValue['msl_creator_name_identifiers_uris'][$i],
                                        )
                                    );
                                }

                                for ($i = 0; $i < count($subValue['msl_creator_affiliations_names']); $i++) {
                                    $creator->addAffiliation(
                                        new Affiliation(
                                            $subValue['msl_creator_affiliations_names'][$i],
                                            $subValue['msl_creator_affiliation_identifiers'][$i],
                                            $subValue['msl_creator_affiliation_identifier_schemes'][$i],
                                            $subValue['msl_creator_affiliation_scheme_uris'][$i],
                                        )
                                    );
                                }
                                $dataPublication->msl_creators[] = $creator;
                            }
                            break;

                        case 'msl_contributors':
                            foreach ($value as $subValue) {
                                $contributor = new Contributor(
                                    $subValue['msl_contributor_name'],
                                    $subValue['msl_contributor_type'],
                                    $subValue['msl_contributor_given_name'],
                                    $subValue['msl_contributor_family_name'],
                                    $subValue['msl_contributor_name_type'],
                                );

                                for ($i = 0; $i < count($subValue['msl_contributor_name_identifiers']); $i++) {
                                    $contributor->addNameIdentifier(
                                        new NameIdentifier(
                                            $subValue['msl_contributor_name_identifiers'][$i],
                                            $subValue['msl_contributor_name_identifiers_schemes'][$i],
                                            $subValue['msl_contributor_name_identifiers_uris'][$i],
                                        )
                                    );
                                }

                                for ($i = 0; $i < count($subValue['msl_contributor_affiliations_names']); $i++) {
                                    $contributor->addAffiliation(
                                        new Affiliation(
                                            $subValue['msl_contributor_affiliations_names'][$i],
                                            $subValue['msl_contributor_affiliation_identifiers'][$i],
                                            $subValue['msl_contributor_affiliation_identifier_schemes'][$i],
                                            $subValue['msl_contributor_affiliation_scheme_uris'][$i],
                                        )
                                    );
                                }
                                $dataPublication->msl_contributors[] = $contributor;
                            }
                            break;

                        case 'msl_sizes':
                            $dataPublication->msl_sizes = $value;
                            break;

                        case 'msl_formats':
                            $dataPublication->msl_formats = $value;
                            break;

                        case 'msl_laboratories':

                            break;

                        case 'msl_files':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_files[] = new File(
                                    $subValue['msl_file_name'],
                                    $subValue['msl_download_link'],
                                    $subValue['msl_extension'],
                                    $subValue['msl_is_folder'],
                                    $subValue['msl_timestamp'],
                                );
                            }
                            break;

                        case 'msl_spatial_coordinates':

                            break;

                        case 'msl_geolocations':
                            $dataPublication->msl_geolocations = $value;
                            break;

                        case 'extras':
                            $dataPublication->extras = $value;
                            break;

                        case 'tag_string':

                            break;

                        case 'msl_tags':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_tags[] = new Tag(
                                    $subValue['msl_tag_string'],
                                    $subValue['msl_tag_scheme_uri'],
                                    $subValue['msl_tag_value_uri'],
                                    $subValue['msl_tag_subject_scheme'],
                                    $subValue['msl_tag_classification_code'],
                                    $subValue['msl_tag_msl_uris'],
                                );
                            }
                            break;

                        case 'msl_subdomains':
                            $dataPublication->msl_subdomains = $value;
                            break;

                        case 'msl_subdomains_original':
                            $dataPublication->msl_subdomains_original = $value;
                            break;

                        case 'msl_subdomains_interpreted':
                            $dataPublication->msl_subdomains_interpreted = $value;
                            break;

                        case 'msl_enriched_keywords':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_enriched_keywords[] = new EnrichedKeyword(
                                    $subValue['msl_enriched_keyword_label'],
                                    $subValue['msl_enriched_keyword_uri'],
                                    $subValue['msl_enriched_keyword_vocab_uri'],
                                    $subValue['msl_enriched_keyword_associated_subdomains'],
                                    $subValue['msl_enriched_keyword_match_locations'],
                                    $subValue['msl_enriched_keyword_match_child_uris'],
                                );
                            }
                            break;

                        case 'msl_original_keywords':
                            foreach ($value as $subValue) {
                                $dataPublication->msl_original_keywords[] = new OriginalKeyword(
                                    $subValue['msl_original_keyword_label'],
                                    $subValue['msl_original_keyword_uri'],
                                    $subValue['msl_original_keyword_vocab_uri'],
                                );
                            }
                            break;
                    }
                }
            }
        }

        return $dataPublication;
    }
}
