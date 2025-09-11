<?php
namespace App\Mappers;

use App\Models\SourceDataset;
use App\Models\MappingLog;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Datasets\BaseDataset;
use App\Mappers\Helpers\KeywordHelper;
use SimpleXMLElement;

class CsicMapper
{
    protected $client;
    
    protected $dataciteHelper;
    
    protected $keywordHelper;
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->dataciteHelper = new DataciteCitationHelper();
        $this->keywordHelper = new KeywordHelper();
    }
            
    private function createDatasetNameFromDoi($doiString) 
    {        
        return md5($doiString);
    }
    
    
    private function log($severity, $text, $sourceDataset)
    {
        $levels = ['ERROR', 'WARNING'];
        if(in_array($severity, $levels)) {
            MappingLog::create([
                'type' => $severity,
                'message' => $text,
                'source_dataset_id' => $sourceDataset->id,
                'import_id' => $sourceDataset->source_dataset_identifier->import->id
            ]);
        } else {
            throw new \Exception('invalid log type');
        }
    }
    
    private function cleanDoiReference($doi) {
        if(str_contains($doi, 'https://doi.org/')) {
            return str_replace('https://doi.org/', '', $doi);
        }
        return $doi;
    }
        
    private function extractExtension($filename)
    {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }
    
    private function extractFilename($filename)
    {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['filename'])) {
            return $fileInfo['filename'];
        }
        
        return '';
    }    
    
    private function cleanKeyword($string)
    {
        $keyword = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
        if(strlen($keyword) >= 100) {
            $keyword = substr($keyword, 0, 95);
            $keyword = $keyword . "...";
        }
        
        return trim($keyword);
    }
            
    public function map(SourceDataset $sourceDataset)
    {
        //load xml file
        $xmlDocument = simplexml_load_string($sourceDataset->source_dataset, SimpleXMLElement::class, LIBXML_NOCDATA);
        
        //dd($xmlDocument->getNamespaces(true));
        
        $nameSpaces = $xmlDocument->getNamespaces(true);
        
        if(isset($nameSpaces[""])) {
            $mainNamespace = $nameSpaces[""];
        } else {
            $mainNamespace = "http://datacite.org/schema/kernel-4";
        }
        
        //declare xpath namespaces        
        $xmlDocument->registerXPathNamespace('dc', $mainNamespace);
        $xmlDocument->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlDocument->registerXPathNamespace('xml', 'http://www.w3.org/XML/1998/namespace');
                                        
        $dataset = new BaseDataset();                                
        
        // set subdomains
        //$dataset->addSubDomain('geochemistry');
        
        //extract title
        $result = $xmlDocument->xpath('/dc:resource/dc:titles[1]/dc:title[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->title = (string)$result[0];
        }                
        
        //extract name
        $result = $xmlDocument->xpath("/dc:resource/dc:identifier[@identifierType='DOI']/node()");
        if(isset($result[0])) {
            $dataset->name = $this->createDatasetNameFromDoi((string)$result[0]);
        } else {
            throw new \Exception('No DOI found.');
        }
        
        //extract doi
        if(isset($result[0])) {
            $dataset->msl_doi = (string)$result[0];
        }                        
                        
        //extract source
        if(isset($result[0])) {
            $dataset->msl_source = "http://dx.doi.org/" . (string)$result[0];
        }
                        
        //set citation
        $citationString = $this->dataciteHelper->getCitationString((string)$result[0]);
        if(strlen($citationString > 0)) {
            $dataset->msl_citation = $citationString;
        }
        
        //extract handle
        $result = $xmlDocument->xpath("/dc:resource/dc:alternateIdentifiers/dc:alternateIdentifier[@alternateIdentifierType='Handle']/node()");
        if(isset($result[0])) {
            $dataset->msl_handle = (string)$result[0];
        }
                        
        //extract date
        $result = $xmlDocument->xpath('/dc:resource[1]/dc:date[@dateType="Issued"]/node()[1]');
        if(isset($result[0])) {
            $parts = explode('-', $result[0]);
            if(isset($parts[0])) {
                $dataset->msl_publication_year = $parts[0];
            }
            if(isset($parts[1])) {
                $dataset->msl_publication_month = $parts[1];
            }
            if(isset($parts[2])) {
                $dataset->msl_publication_day = $parts[2];
            }
            
            $date = new \DateTime($result[0]);
            $dataset->msl_publication_date = $date->format('Y-m-d');            
        }
                        
        //extract authors
        $authorsResult = $xmlDocument->xpath("/dc:resource[1]/dc:creators/dc:creator");
        if(count($authorsResult) > 0) {
            foreach ($authorsResult as $authorResult) {
                $author = [
                    'msl_author_name' => '',
                    'msl_author_orcid' => '',
                    'msl_author_scopus' => '',
                    'msl_author_affiliation' => ''
                ];
                
                $authorResult->registerXPathNamespace('dc', $mainNamespace);
                
                $nameNode = $authorResult->xpath(".//dc:creatorName[1]/node()[1]");                                
                $identifierNode =  $authorResult->xpath(".//dc:nameIdentifier[1]/node()[1]");
                $identifierType = $authorResult->xpath(".//dc:nameIdentifier[1]/@nameIdentifierScheme");                                
                $affiliationNodes = $authorResult->xpath(".//dc:affiliation/node()");
                
                                
                if(isset($nameNode[0])) {
                    $author['msl_author_name'] = (string)$nameNode[0];
                }
                if(isset($identifierNode[0])) {
                    $author['msl_author_identifier'] = (string)$identifierNode[0];
                }
                if(isset($identifierType[0])) {
                    $author['msl_author_identifier_type'] = (string)$identifierType[0];
                }
                if(count($affiliationNodes) > 0) {
                    $affilitionString = '';
                    foreach ($affiliationNodes as $affiliationNode) {
                        if($affilitionString !== '') {
                            $affilitionString = $affilitionString . ' ';
                        }
                        $affilitionString = $affilitionString . (string)$affiliationNode . ';';
                    }
                    $author['msl_author_affiliation'] = $affilitionString;
                }
                                
                $dataset->msl_authors[] = $author;
            }
        }
                        
        //extract contributors
        $contributorsResult = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor");
        if(count($contributorsResult) > 0) {
            foreach ($contributorsResult as $contributorResult) {
                $contributor = [
                    'msl_contributor_name' => '',
                    'msl_contributor_role' => '',
                    'msl_contributor_orcid' => '',
                    'msl_contributor_scopus' => '',
                    'msl_contributor_affiliation' => ''
                ];
                
                $contributorResult->registerXPathNamespace('dc', $mainNamespace);
                
                if($mainNamespace === "http://datacite.org/schema/kernel-3") {
                    $nameNode = $contributorResult->xpath(".//dc:contributorName[1]//node()[1]");
                } else {
                    $nameNode = $contributorResult->xpath(".//node()[1]");
                }                
                $roleNode = $contributorResult->xpath(".//@contributorType");
                $identifierNode =  $contributorResult->xpath(".//dc:nameIdentifier[1]/node()[1]");
                $identifierType = $contributorResult->xpath(".//dc:nameIdentifier[1]/@nameIdentifierScheme");
                $affiliationNodes = $contributorResult->xpath(".//dc:affiliation/node()");
                
                if(isset($nameNode[0])) {
                    $contributor['msl_contributor_name'] = (string)$nameNode[0];
                }
                if(isset($roleNode[0])) {
                    $contributor['msl_contributor_role'] = (string)$roleNode[0];
                }                
                                
                $dataset->msl_contributors[] = $contributor;
            }
        }
        
        //extract references
        $referencesResult = $xmlDocument->xpath("/dc:resource[1]/dc:relatedIdentifiers/dc:relatedIdentifier");
        if(count($referencesResult) > 0) {
            foreach ($referencesResult as $referenceResult) {
                $reference = [
                    'msl_reference_doi' => '',
                    'msl_reference_handle' => '',
                    'msl_reference_title' => '',
                    'msl_reference_type' => ''
                ];
                
                $referenceResult->registerXPathNamespace('dc', $mainNamespace);
                
                $titleNode = $referenceResult->xpath(".//node()[1]");
                $referenceTypeNode = $referenceResult->xpath(".//@relatedIdentifierType");
                
                if(isset($titleNode[0])) {
                    $reference['msl_reference_title'] = (string)$titleNode[0];
                }
                if(isset($referenceTypeNode[0])) {
                    $reference['msl_reference_type'] = (string)$referenceTypeNode[0];
                }                           
                
                $dataset->msl_references[] = $reference;
            }
        }
                        
        //extract notes
        $result = $xmlDocument->xpath('/dc:resource/dc:descriptions/dc:description[@descriptionType="Abstract"]/node()');
        if(isset($result[0])) {
            $dataset->notes = (string)$result[0];
        }
        
        //extract technical description
        $result = $xmlDocument->xpath('/dc:resource/dc:descriptions/dc:description[@descriptionType="Description"]/node()');
        if(isset($result[0])) {
            $dataset->msl_technical_description = (string)$result[0];
        }
                        
        //set owner_org
        $dataset->owner_org = $sourceDataset->source_dataset_identifier->import->importer->data_repository->ckan_name;
                        
        //set publisher
        $result = $xmlDocument->xpath('/dc:resource/dc:publisher[1]/node()');
        if(isset($result[0])) {
            $dataset->msl_publisher = (string)$result[0];
        }
                
        //extract labs
        $labResults = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor[@contributorType=\"hostingInstitution\"]");        //
        if(count($labResults) > 0) {
            foreach ($labResults as $labResult) {
                $lab = [
                    'msl_lab_name' => '',
                    'msl_lab_id' => ''
                ];
                
                $nameNode = $labResult->xpath(".//node()");
                if(isset($nameNode[0])) {
                    $lab['msl_lab_name'] = (string)$nameNode[0];
                }
                
                $this->log('WARNING', "Lab with name: \"" . $lab['msl_lab_name'] . "\" has no id.", $sourceDataset);
                               
                $dataset->addLab($lab);               
            }
        }
        
        //extract spatial coordinates
        $spatialResults = $xmlDocument->xpath("/dc:resource/dc:geoLocations/dc:geoLocation/dc:geoLocationBox");
        if(count($spatialResults) > 0) {
            foreach ($spatialResults as $spatialResult) {
                $spatial = [
                    'msl_elong' => '',
                    'msl_nLat' => '',
                    'msl_sLat' => '',
                    'msl_wLong' => ''
                ];
                
                $spatialResult->registerXPathNamespace('dc', $mainNamespace);
                
                $elongNode = $spatialResult->xpath(".//dc:eastBoundLongitude/node()");
                $nlatNode = $spatialResult->xpath(".//dc:northBoundLatitude/node()");
                $slatNode = $spatialResult->xpath(".//dc:southBoundLatitude/node()");
                $wlongNode = $spatialResult->xpath(".//dc:westBoundLongitude/node()");
                
                if(isset($elongNode[0])) {
                    $spatial['msl_elong'] = (string)$elongNode[0];
                }
                if(isset($nlatNode[0])) {
                    $spatial['msl_nLat'] = (string)$nlatNode[0];
                }
                if(isset($slatNode[0])) {
                    $spatial['msl_sLat'] = (string)$slatNode[0];
                }
                if(isset($wlongNode[0])) {
                    $spatial['msl_wLong'] = (string)$wlongNode[0];
                }
                
                $dataset->msl_spatial_coordinates[] = $spatial;
            }
        }
                                                
        //extract geo locations
        $locationsResult = $xmlDocument->xpath("/dc:resource/dc:geoLocations/dc:geoLocation/dc:geoLocationPlace");
        if(count($locationsResult) > 0) {
            $geoLocations = [];
            foreach ($locationsResult as $locationResult) {
                $geoLocation = ['msl_geolocation_place' => (string)$locationResult[0]];
                $geoLocations[] = $geoLocation;
            }
            
            $dataset->msl_geolocations = $geoLocations;
        }
                        
        //extract license id
        $result = $xmlDocument->xpath('/dc:resource/dc:rightsList/dc:rights/@rightsURI');
        if(isset($result[0])) {
            $dataset->license_id = (string)$result[0];
        }
                        
        //extract point of contact
        $contactResults = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor[@contributorType=\"contactPerson\"]");
        if(count($contactResults) > 0) {
            foreach ($contactResults as $contactResult) {
                $contact = [
                    'msl_contact_name' => '',
                    'msl_contact_organisation' => '',
                    'msl_contact_electronic_address' => ''
                ];
                
                $contactResult->registerXPathNamespace('dc', $mainNamespace);
                
                $contact['msl_contact_name'] = (string)$contactResult[0];
                                                                                
                $dataset->msl_points_of_contact[] = $contact;
            }
        }
                
        //extract tags/keywords
        $results = $xmlDocument->xpath('/dc:resource/dc:subjects/dc:subject');
        //dd($results);
        if(count($results) > 0) {
            $keywords = [];
            foreach ($results as $result) {
                $keywords[] = (string)$result[0];
            }
                        
            $dataset = $this->keywordHelper->mapKeywords($dataset, $keywords);            
        }

        //attempt to map keywords from abstract and title
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->title, 'title');
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->notes, 'notes');
                    
        return $dataset;
    }
}

