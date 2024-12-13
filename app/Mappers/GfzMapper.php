<?php
namespace App\Mappers;

use App\Models\SourceDataset;
use App\Models\MappingLog;
use App\Ckan\Request\PackageSearch;
use App\Ckan\Response\PackageSearchResponse;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Datasets\BaseDataset;
use App\Mappers\Helpers\GeoJSON;
use App\Mappers\Helpers\KeywordHelper;
use App\Mappers\Helpers\GfzDownloadHelper;

class GfzMapper
{
    protected $client;
    
    protected $dataciteHelper;
    
    protected $keywordHelper;
    
    protected $gfzDownloadHelper;
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->dataciteHelper = new DataciteCitationHelper();
        $this->keywordHelper = new KeywordHelper();
        $this->gfzDownloadHelper = new GfzDownloadHelper();
    }
    
    private function createDatasetNameFromDoi($doiString) 
    {        
        return md5($doiString);
    }
        
    
    private function getSubDomains(BaseDataset $dataset, $xml, $sourceDataset) {
        $xmlResults = $xml->xpath("//*/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:descriptiveKeywords/gmd:MD_Keywords/gmd:keyword/gco:CharacterString[(./node()='analogue models of geologic processes') or (./node()='rock and melt physical properties') or (./node()='paleomagnetic and magnetic data' )]/node()");
        $results = 0;
        
        foreach ($xmlResults as $xmlResult) {
            switch ((string)$xmlResult) {
                case 'analogue models of geologic processes':
                    $dataset->addSubDomain('analogue modelling of geologic processes');
                    $results++;
                    break;
                    
                case 'rock and melt physical properties':
                    $dataset->addSubDomain('rock and melt physics');
                    $results++;
                    break;
                    
                case 'paleomagnetic and magnetic data':
                    $dataset->addSubDomain('paleomagnetism');
                    $results++;
                    break;
            }            
        }
             
        if($results == 0) {
            $this->log('WARNING', 'No keyword found to set subdomain', $sourceDataset);
        }
        
        return $dataset;
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
    
    private function getLabNames()
    {
        $searchRequest = new PackageSearch();
        
        $searchRequest->rows = 1000;
        $searchRequest->query = 'type: lab';
        try {
            $response = $this->client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            
        }
        
        $packageSearchResponse = new PackageSearchResponse(json_decode($response->getBody(), true), $response->getStatusCode());
        
        return $packageSearchResponse->getNameList();
    }
    
    private function getYear($date)
    {
        $datetime = new \DateTime($date);
        $result = $datetime->format('Y');
        
        if($result) {
            return $result;
        }
        return '';
    }
    
    private function getMonth($date)
    {
        $datetime = new \DateTime($date);
        $result = $datetime->format('m');
        
        if($result) {
            return $result;
        }
        return '';
    }
    
    private function getDay($date)
    {
        $datetime = new \DateTime($date);
        $result = $datetime->format('d');
        
        if($result) {
            return $result;
        }
        return '';
    }
    
    private function formatDate($date)
    {
        $datetime = new \DateTime($date);
        $result = $datetime->format('Y-m-d');
        
        if($result) {
            return $result;
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
    
    private function cleanDoi($string)
    {
        if(str_contains($string, 'doi:')) {
            $string = str_replace('doi:', '', $string);
        }
        
        if(str_contains($string, 'https://doi.org/')) {
            $string = str_replace('https://doi.org/:', '', $string);
        }
        
        if(str_contains($string, 'http://doi.org/')) {
            $string = str_replace('http://doi.org/:', '', $string);
        }
        
        return $string;
    }
    
    private function cleanOrcid($string)
    {
        if(str_contains($string, '/')) {
            return substr($string, strrpos( $string, '/' )+1);
        }
        
        return $string;
    }
    
    public function map(SourceDataset $sourceDataset)
    {
        //load xml file
        $xmlDocument = simplexml_load_string($sourceDataset->source_dataset);
        
        //dd($xmlDocument->getNamespaces(true));
        
        //declare xpath namespaces
        $xmlDocument->registerXPathNamespace('oai', 'http://www.openarchives.org/OAI/2.0/');
        $xmlDocument->registerXPathNamespace('gmd', 'http://www.isotc211.org/2005/gmd');
        $xmlDocument->registerXPathNamespace('gco', 'http://www.isotc211.org/2005/gco');
        $xmlDocument->registerXPathNamespace('xlink', 'http://www.w3.org/1999/xlink');
                                
        $dataset = new BaseDataset();
        
        // set subdomains
        $dataset = $this->getSubDomains($dataset, $xmlDocument, $sourceDataset);
        
        //set owner_org
        $dataset->owner_org = $sourceDataset->source_dataset_identifier->import->importer->data_repository->ckan_name;
        
        //extract publisher
        $result = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:citedResponsibleParty/gmd:CI_ResponsibleParty[./gmd:role/gmd:CI_RoleCode='publisher']/gmd:organisationName/gco:CharacterString/node()");
        if(isset($result[0])) {
            $dataset->msl_publisher = (string)$result[0];
        }
        
        //extract title
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:title/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->title = (string)$result[0];
        }
        
        //extract name
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:fileIdentifier/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->name = $this->createDatasetNameFromDoi((string)$result[0]);
        }
                
        //extract doi
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata/gmd:MD_Metadata/gmd:fileIdentifier/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->msl_doi = $this->cleanDoi((string)$result[0]);
        }
                
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata/gmd:MD_Metadata/gmd:fileIdentifier/gco:CharacterString/node()');
        if(isset($result[0])) {
            $doi = $this->cleanDoi((string)$result[0]);
            
            $citationString = $this->dataciteHelper->getCitationString($doi);
            if(strlen($citationString > 0)) {
                $dataset->msl_citation = $citationString;
            }
        }
        
        //extract msl_publication_date        
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:date/gmd:CI_Date/gmd:date/gco:Date[1]/node()');
        if(isset($result[0])) {
            $dataset->msl_publication_day = $this->getDay((string)$result[0]);
            $dataset->msl_publication_month = $this->getMonth((string)$result[0]);
            $dataset->msl_publication_year = $this->getYear((string)$result[0]);  
            $dataset->msl_publication_date = $this->formatDate((string)$result[0]);
        }        
        
        //extract authors
        $authorsResult = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata[1]/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:citedResponsibleParty[./gmd:CI_ResponsibleParty/gmd:role/gmd:CI_RoleCode='author']");
        if(count($authorsResult) > 0) {                        
            foreach ($authorsResult as $authorResult) {                
                $author = [
                    'msl_author_name' => '',
                    'msl_author_orcid' => '',
                    'msl_author_scopus' => '',
                    'msl_author_affiliation' => ''
                ];
                
                $nameNode = $authorResult->xpath(".//gmd:CI_ResponsibleParty[./gmd:role/gmd:CI_RoleCode='author']/gmd:individualName/gco:CharacterString/node()");
                $identifierNode =  $authorResult->xpath(".//@xlink:href");
                $affiliationNode = $authorResult->xpath(".//gmd:CI_ResponsibleParty[./gmd:role/gmd:CI_RoleCode='author']/gmd:organisationName/gco:CharacterString/node()");
                if(isset($nameNode[0])) {
                    $author['msl_author_name'] = (string)$nameNode[0];
                }
                if(isset($identifierNode[0])) {
                    $author['msl_author_orcid'] = $this->cleanOrcid((string)$identifierNode[0]);
                }
                if(isset($affiliationNode[0])) {
                    $author['msl_author_affiliation'] = (string)$affiliationNode[0];
                }
                $dataset->msl_authors[] = $author;
            }            
        }
        
        //extract references
        $referencesResult = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:aggregationInfo");
        if(count($referencesResult) > 0) {
            foreach ($referencesResult as $referenceResult) {
                $reference = [
                    'msl_reference_doi' => '',
                    'msl_reference_handle' => '',
                    'msl_reference_title' => '',
                    'msl_reference_type' => ''
                ];
                
                $identifierNode = $referenceResult->xpath(".//gmd:MD_AggregateInformation/gmd:aggregateDataSetIdentifier/gmd:RS_Identifier/gmd:code/gco:CharacterString/node()");
                $identifierTypeNode = $referenceResult->xpath(".//gmd:MD_AggregateInformation/gmd:aggregateDataSetIdentifier/gmd:RS_Identifier/gmd:codeSpace/gco:CharacterString/node()");
                $referenceTypeNode = $referenceResult->xpath(".//gmd:MD_AggregateInformation/gmd:associationType/gmd:DS_AssociationTypeCode/node()");
                
                if(isset($referenceTypeNode[0])) {
                    $reference['msl_reference_type'] = (string)$referenceTypeNode[0];
                }
                                
                if(isset($identifierTypeNode[0])) {
                    if((string)$identifierTypeNode[0] == 'DOI') {
                        $reference['msl_reference_doi'] = $this->cleanDoi((string)$identifierNode[0]);
                        
                        $citationString = $this->dataciteHelper->getCitationString($reference['msl_reference_doi']);
                        if(strlen($citationString) == 0) {
                            $this->log('WARNING', "datacite citation returned empty for DOI: " . $reference['msl_reference_doi'], $sourceDataset);
                        } else {
                            $reference['msl_reference_title'] = $citationString;
                        }                                                
                    }                    
                }
                               
                $dataset->msl_references[] = $reference;                
            }
        }       
        
        //extract notes
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:abstract/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->notes = (string)$result[0];
        }
        
        //extract labs
        $labResults = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:citedResponsibleParty[./gmd:CI_ResponsibleParty/gmd:role/gmd:CI_RoleCode/node()='originator']");
        if(count($labResults) > 0) {
            foreach ($labResults as $labResult) {
                $lab = [
                    'msl_lab_name' => '',
                    'msl_lab_id' => ''
                ];
                
                $nameNode = $labResult->xpath(".//gmd:CI_ResponsibleParty/gmd:organisationName/gco:CharacterString[1]/node()");
                if(isset($nameNode[0])) {
                    $lab['msl_lab_name'] = (string)$nameNode[0];                    
                }
                $idNode = $labResult->xpath(".//@uuidref");
                if(isset($idNode[0])) {
                    $lab['msl_lab_id'] = (string)$idNode[0];
                    
                    // check if lab id is present in ckan                    
                    if(!in_array($lab['msl_lab_id'], $this->getLabNames())) {
                        $this->log('WARNING', "LabId: \"" . $lab['msl_lab_id'] . "\" not found in ckan.", $sourceDataset);
                    }                    
                } else {
                    $this->log('WARNING', "Lab with name: \"" . $lab['msl_lab_name'] . "\" has no id.", $sourceDataset);
                }
                                
                $dataset->addLab($lab);
            }
        }
               
        //extract tags/keywords
        $results = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:descriptiveKeywords/gmd:MD_Keywords/gmd:keyword/gco:CharacterString/node()');
        if(count($results) > 0) {
            
            $keywords = [];
            foreach ($results as $result) {
                $keywords[] = (string)$result[0];
            }            
            
            $dataset = $this->keywordHelper->mapKeywords($dataset, $keywords, true, '>');           
        }
        
        //attempt to map keywords from abstract and title
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->title, 'title');
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->notes, 'notes');
        
        $geometriesBox = [];
        $featuresBox = [];
        $featuresPoint = [];
        $areaSize = 0;
        
        //extract spatial coordinates
        $spatialResults = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:extent/gmd:EX_Extent/gmd:geographicElement");
        if(count($spatialResults) > 0) {
            foreach ($spatialResults as $spatialResult) {
                $spatial = [
                    'msl_elong' => '',
                    'msl_nLat' => '',
                    'msl_sLat' => '',
                    'msl_wLong' => ''
                ];
                
                $elongNode = $spatialResult->xpath(".//gmd:EX_GeographicBoundingBox/gmd:eastBoundLongitude/gco:Decimal/node()");
                $nlatNode = $spatialResult->xpath(".//gmd:EX_GeographicBoundingBox/gmd:northBoundLatitude/gco:Decimal/node()");
                $slatNode = $spatialResult->xpath(".//gmd:EX_GeographicBoundingBox/gmd:southBoundLatitude/gco:Decimal/node()");
                $wlongNode = $spatialResult->xpath(".//gmd:EX_GeographicBoundingBox/gmd:westBoundLongitude/gco:Decimal/node()");
                
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
                
                // Geo specific handling for presentation and search (SOLR) purposes.
                $bbox = ['eastBoundLongitude' => (float)$elongNode[0],
                    'northBoundLatitude' => (float)$nlatNode[0],
                    'southBoundLatitude' => (float)$slatNode[0],
                    'westBoundLongitude' => (float)$wlongNode[0]];
                
                if (GeoJSON::isCompleteBoundingBox($bbox)) {
                    if (($feature = GeoJSON::coordsToGeoJSONFeatureBBox($bbox, 'Original coordinates')) && $feature != []) {
                        $featuresBox[] = $feature;                        
                        $featuresPoint[] = GeoJSON::coordsToGeoJSONFeaturePoint($bbox, 'Original coordinates');
                        $areaSize = $areaSize + GeoJSON::bboxSurfaceSize($bbox);
                    }
                    
                    if (($geometry = GeoJSON::coordsToGeoJSONGeometryBBox($bbox)) && $geometry != []) {
                        $geometriesBox[] = $geometry;
                    }
                }
            }
        }
        
        if (sizeof($featuresBox)) {
            // featureCollection is for mapping functionality frontend
            $featureCollectionBoxes = ["type" => "FeatureCollection", "features" => $featuresBox];
            $featureCollectionPoints = ["type" => "FeatureCollection", "features" => $featuresPoint];
            
            $dataset->msl_geojson_featurecollection = json_encode($featureCollectionBoxes);
            $dataset->msl_geojson_featurecollection_points = json_encode($featureCollectionPoints);
            
            $dataset->msl_surface_area = (int)$areaSize;
        }
        
        if (sizeof($geometriesBox)) {
            // geometryCollection is for SOLR
            $geometryCollectionBoxes = ["type" => "GeometryCollection", "geometries" => $geometriesBox];
            
            $dataset->extras[] = ["key" => "spatial", "value" => json_encode($geometryCollectionBoxes)];
        }
                        
        //extract license id
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata[1]/gmd:identificationInfo[1]/gmd:MD_DataIdentification[1]/gmd:resourceConstraints[1]/gmd:MD_Constraints[1]/gmd:useLimitation[1]/gco:CharacterString[1]/node()[1]');
        if(isset($result[0])) {            
            $dataset->license_id = (string)$result[0];
        }
        
        //extract point of contact
        $contactResults = $xmlDocument->xpath("/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:pointOfContact");
        if(count($contactResults) > 0) {
            foreach ($contactResults as $contactResult) {
                $contact = [
                    'msl_contact_name' => '',
                    'msl_contact_organisation' => '',
                    'msl_contact_electronic_address' => ''
                ];
                
                $nameNode = $contactResult->xpath(".//gmd:CI_ResponsibleParty/gmd:individualName/gco:CharacterString/node()");
                $organisationNode = $contactResult->xpath(".//gmd:CI_ResponsibleParty/gmd:organisationName/gco:CharacterString/node()");
                $electronicAddressNode = $contactResult->xpath(".//gmd:CI_ResponsibleParty/gmd:contactInfo/gmd:CI_Contact/gmd:address/gmd:CI_Address/gmd:electronicMailAddress/gco:CharacterString/node()");
                
                if(isset($nameNode[0])) {
                    $contact['msl_contact_name'] = (string)$nameNode[0];
                }
                if(isset($organisationNode[0])) {
                    $contact['msl_contact_organisation'] = (string)$organisationNode[0];
                }
                if(isset($electronicAddressNode[0])) {
                    $contact['msl_contact_electronic_address'] = (string)$electronicAddressNode[0];
                }
                
                $dataset->msl_points_of_contact[] = $contact;
            }
        }
              
        //extract source
        $result = $xmlDocument->xpath('/oai:OAI-PMH/oai:GetRecord/oai:record/oai:metadata[1]/gmd:MD_Metadata[1]/gmd:distributionInfo[1]/gmd:MD_Distribution[1]/gmd:transferOptions[1]/gmd:MD_DigitalTransferOptions[1]/gmd:onLine[1]/gmd:CI_OnlineResource[1]/gmd:linkage[1]/gmd:URL[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->msl_source = (string)$result[0];
        }
        
        //extract file information
        //$dataset = $this->gfzDownloadHelper->addData($dataset);
              
        return $dataset;
    }
}

