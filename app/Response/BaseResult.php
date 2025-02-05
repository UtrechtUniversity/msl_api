<?php

namespace App\Response;

use App\Response\Elements\CollectionPeriod;
use App\Response\Elements\Contributor;
use App\Response\Elements\CoveredPeriod;
use App\Response\Elements\Author;
use App\Response\Elements\Download;
use App\Response\Elements\Pid;
use App\Response\Elements\Reference;
use App\Response\Elements\Spatial;

class BaseResult
{
    public $title = "";
    
    public $name = "";
    
    public $portalLink = "";
    
    public $doi = "";
    
    public $handle = "";

    public $license = "";

    public $version = "";

    public $source = "";

    public $publisher = "";

    public $subdomain = [];

    public $description = "";

    public $publicationDate = "";

    public $citation = "";

    public $creators = [];

    public $contributors = [];

    public $references = [];

    public $laboratories = [];

    public $materials = [];   

    public $spatial = [];

    public $locations = [];

    public $coveredPeriods = [];

    public $collectionPeriods = [];

    public $maintainer = "";

    public $downloads = [];
    
    public $researchAspects = [];
    


    public function __construct($data, $context) {

        if(isset($data['title'])) {
            $this->title = $data['title'];
        }
        
        if(isset($data['name'])) {
            $this->name = $data['name'];
            $this->portalLink = config('app.url') . '/data-publication/' . $data['name'];
        }

        if(isset($data['license_id'])) {
            $this->license = $data['license_id'];
        }

        if(isset($data['msl_version'])) {
            $this->version = $data['msl_version'];
        }

        if(isset($data['msl_source'])) {
            $this->source = $data['msl_source'];
        }
        
        if(isset($data['msl_doi'])) {
            $this->doi = $data['msl_doi'];
        }
        
        if(isset($data['msl_handle'])) {
            $this->handle = $data['msl_handle'];
        }

        if(isset($data['owner_org'])) {
            $this->publisher = $data['owner_org'];
        }

        if(isset($data['msl_subdomains'])) {
            if(count($data['msl_subdomains']) > 0) {
                foreach ($data['msl_subdomains'] as $subDomainDataValue) {
                    $this->subdomain[] = $subDomainDataValue['msl_subdomain'];
                }
            }
        }        
        
        if(isset($data['notes'])) {
            $this->description = $data['notes'];
        }

        if(isset($data['msl_publication_date'])) {
            $this->publicationDate = $data['msl_publication_date'];
        }

        if(isset($data['msl_citation'])) {
            $this->citation = $data['msl_citation'];
        }

        if(isset($data['msl_authors'])) {
            if(count($data['msl_authors']) > 0) {
                foreach ($data['msl_authors'] as $authorData) {
                    $this->creators[] = new Author($authorData);
                }
            }
        }

        if(isset($data['msl_contributors'])) {
            if(count($data['msl_contributors']) > 0) {
                foreach ($data['msl_contributors'] as $contributorData) {
                    $this->contributors[] = new Contributor($contributorData);
                }
            }
        }

        if(isset($data['msl_references'])) {
            if(count($data['msl_references']) > 0) {
                foreach ($data['msl_references'] as $referenceData) {
                    $this->references[] = new Reference($referenceData);
                }
            }
        }

        if(isset($data['msl_laboratories'])) {
            if(count($data['msl_laboratories']) > 0) {
                foreach ($data['msl_laboratories'] as $laboratoryData) {
                    if(isset($laboratoryData['msl_lab_name'])) {
                        $this->laboratories[] = $laboratoryData['msl_lab_name'];
                    }
                }
            }
        }
        
        $materials = [];
        $materials = $this->getMaterialKeyword($data);
        $this->materials = array_values(array_unique($materials));
                       
        if(isset($data['msl_spatial_coordinates'])) {
            if(count($data['msl_spatial_coordinates']) > 0) {
                foreach ($data['msl_spatial_coordinates'] as $spatialData) {
                    $this->spatial[] = new Spatial($spatialData);
                }
            }
        }

        if(isset($data['msl_geolocations'])) {
            if(count($data['msl_geolocations']) > 0) {
                foreach ($data['msl_geolocations'] as $geoLocationData) {
                    if(isset($geoLocationData['msl_geolocation_place'])) {
                        $this->locations[] = $geoLocationData['msl_geolocation_place'];
                    }
                }
            }
        }

        if(isset($data['msl_covered_period'])) {
            if(count($data['msl_covered_period']) > 0) {
                foreach ($data['msl_covered_period'] as $coveredPeriodData) {
                    $this->coveredPeriods[] = new CoveredPeriod($coveredPeriodData);
                }
            }
        }

        if(isset($data['msl_collection_period'])) {
            if(count($data['msl_collection_period']) > 0) {
                foreach ($data['msl_collection_period'] as $collectionPeriodData) {
                    $this->collectionPeriods[] = new CollectionPeriod($collectionPeriodData);
                }
            }
        }

        if(isset($data['maintainer'])) {
            $this->maintainer = $data['maintainer'];
        }

        if(isset($data['msl_downloads'])) {
            if(count($data['msl_downloads']) > 0) {
                foreach ($data['msl_downloads'] as $downloadData) {
                    $this->downloads[] = new Download($downloadData);
                }
            }
        }
        
        //set researchaspects based on context(calling api function)
        switch ($context) {
            case 'rockPhysics':                
                $keywords = [];
                $keywords = array_merge($keywords, $this->getRockPhysicsKeywords($data));
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;                                
                break;            
            case 'analogue':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getAnalogueKeywords($data));
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;
                break;
            case 'paleo':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getPaleomagneticKeywords($data));
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;
                break;
            case 'microscopy':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;
                break;
            case 'geochemistry':
                $keywords = [];
                $keywords = array_merge($keywords, $this->getGeochemistryKeywords($data));
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;
                break;
            case 'all':
                $keywords = [];
                
                $keywords = array_merge($keywords, $this->getRockPhysicsKeywords($data));
                $keywords = array_merge($keywords, $this->getAnalogueKeywords($data));
                $keywords = array_merge($keywords, $this->getPaleomagneticKeywords($data));
                $keywords = array_merge($keywords, $this->getGeochemistryKeywords($data));
                $keywords = array_merge($keywords, $this->getGeologicalSettingKeywords($data));
                $keywords = array_values(array_unique($keywords));
                
                $this->researchAspects = $keywords;                                
                break;
        }        

    }
    
    private function getMaterialKeyword($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/materials/' . config('vocabularies.vocabularies_current_version') . '/'
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }
            }
        }
        
        return $keywords;
    }
    
    private function getRockPhysicsKeywords($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/rockphysics/' . config('vocabularies.vocabularies_current_version') . '/measured_property-', 
            'https://epos-msl.uu.nl/voc/rockphysics/' . config('vocabularies.vocabularies_current_version') . '/inferred_deformation_behavior-'            
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }                
            }
        }
        
        return $keywords;
    }
    
    private function getAnalogueKeywords($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/modeled_structure-',
            'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/modeled_geomorphological_feature-',
            'https://epos-msl.uu.nl/voc/analoguemodelling/' . config('vocabularies.vocabularies_current_version') . '/measured_property-'
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }
            }
        }
        
        return $keywords;        
    }
    
    private function getGeologicalSettingKeywords($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/geologicalsetting/' . config('vocabularies.vocabularies_current_version') . '/'
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }
            }
        }
        
        return $keywords;
    }
    
    private function getPaleomagneticKeywords($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/paleomagnetism/' . config('vocabularies.vocabularies_current_version') . '/measured_property-',
            'https://epos-msl.uu.nl/voc/paleomagnetism/' . config('vocabularies.vocabularies_current_version') . '/inferred_behavior-'
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }
            }
        }
        
        return $keywords;        
    }
    
    private function getGeochemistryKeywords($data) {
        $uriStarts = [
            'https://epos-msl.uu.nl/voc/geochemistry/' . config('vocabularies.vocabularies_current_version') . '/'
        ];
        $keywords = [];
        
        if(isset($data['msl_enriched_keywords'])) {
            foreach ($data['msl_enriched_keywords'] as $enrichedKeyword) {
                foreach ($uriStarts as $uriStart) {
                    if(str_starts_with($enrichedKeyword['msl_enriched_keyword_uri'], $uriStart)) {
                        $keywords[] = $enrichedKeyword['msl_enriched_keyword_label'];
                    }
                }
            }
        }
        
        return $keywords;        
    }
    
}
