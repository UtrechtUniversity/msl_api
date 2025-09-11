<?php
namespace App\Mappers;

use App\Models\SourceDataset;
use App\Models\MappingLog;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Datasets\BaseDataset;
use App\Mappers\Helpers\KeywordHelper;

class YodaMapper
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
    
    private function getSubDomains(BaseDataset $dataset, $sourceDataset)
    {
        $sourceIdentifier = $sourceDataset->source_dataset_identifier;
        
        $extraPayload = $sourceIdentifier->extra_payload;
        
        if(isset($extraPayload['subDomain'])) {
            switch ($extraPayload['subDomain']) {
                case 'Rock physics':
                    $dataset->addSubDomain('rock and melt physics');
                    return $dataset;
                    
                case 'Analogue modelling':
                    $dataset->addSubDomain('analogue modelling of geologic processes');
                    return $dataset;
                    
                case 'Paleomagnetism':
                    $dataset->addSubDomain('paleomagnetism');                    
                    return $dataset;                    
                    
                case 'Microscopy data':
                    $dataset->addSubDomain('microscopy and tomography');
                    return $dataset;                    
                    
                default:
                    $this->log('WARNING', 'Invalid subdomains given', $sourceDataset);
                    return $dataset;
            }
        }
        $this->log('WARNING', 'Invalid subdomains given', $sourceDataset);
        return $dataset;
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
    
    private function cleanDoi($string)
    {
        if(str_contains($string, 'doi:')) {
            $string = str_replace('doi:', '', $string);
        }
        
        if(str_contains($string, 'https://doi.org/')) {
            $string = str_replace('https://doi.org/', '', $string);
        }
        
        if(str_contains($string, 'http://doi.org/')) {
            $string = str_replace('http://doi.org/', '', $string);
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
    
    private function formatDate($date)
    {
        $datetime = \DateTime::createFromFormat('!Y',$date);
        $result = $datetime->format('Y-m-d');
        
        if($result) {
            return $result;
        }
        return '';
    }
            
    public function map(SourceDataset $sourceDataset)
    {        
        //load xml file
        $xmlDocument = simplexml_load_string($sourceDataset->source_dataset);
        
        //dd($xmlDocument->getNamespaces(true));
        
        //declare xpath namespaces        
        $xmlDocument->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
        $xmlDocument->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlDocument->registerXPathNamespace('xml', 'http://www.w3.org/XML/1998/namespace');
                                        
        $dataset = new BaseDataset();                                
        
        // set subdomains
        $dataset = $this->getSubDomains($dataset, $sourceDataset);
        
        //extract title
        $result = $xmlDocument->xpath('/dc:resource/dc:titles[1]/dc:title[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->title = (string)$result[0];
        }
        
        //extract name
        $dataset->name = $this->createDatasetNameFromDoi($sourceDataset->source_dataset_identifier->identifier);
        
        //extract doi
        $dataset->msl_doi = $this->cleanDoi($sourceDataset->source_dataset_identifier->identifier);
        
        //extract source
        $dataset->msl_source = "http://dx.doi.org/" . $sourceDataset->source_dataset_identifier->identifier;
        
        //set citation
        $citationString = $this->dataciteHelper->getCitationString($sourceDataset->source_dataset_identifier->identifier);
        if(strlen($citationString > 0)) {
            $dataset->msl_citation = $citationString;
        }
        
        //extract year
        $result = $xmlDocument->xpath('/dc:resource[1]/dc:publicationYear[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->msl_publication_year = (string)$result[0];
            $dataset->msl_publication_date = $this->formatDate((string)$result[0]);
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
                
                $authorResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
                
                $nameNode = $authorResult->xpath(".//dc:creatorName[1]/node()[1]");                                
                $identifierNode =  $authorResult->xpath(".//dc:nameIdentifier[1]/node()[1]");
                $identifierType = $authorResult->xpath(".//dc:nameIdentifier[1]/@nameIdentifierScheme");                                
                $affiliationNodes = $authorResult->xpath(".//dc:affiliation/node()");
                
                                
                if(isset($nameNode[0])) {
                    $author['msl_author_name'] = (string)$nameNode[0];
                }
                if(isset($identifierType[0])) {
                    if((string)$identifierType[0] == 'ORCID') {
                        $author['msl_author_orcid'] = $this->cleanOrcid((string)$identifierNode[0]);
                    }
                    if((string)$identifierType[0] == 'Author identifier (Scopus)') {
                        $author['msl_author_scopus'] = (string)$identifierNode[0];
                    }                    
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
                
                $contributorResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
                
                $nameNode = $contributorResult->xpath(".//dc:contributorName[1]/node()[1]");
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
                if(isset($identifierType[0])) {
                    if((string)$identifierType[0] == 'ORCID') {
                        $contributor['msl_contributor_orcid'] = $this->cleanOrcid((string)$identifierNode[0]);
                    }
                    if((string)$identifierType[0] == 'Author identifier (Scopus)') {
                        $contributor['msl_contributor_scopus'] = (string)$identifierNode[0];
                    }
                }                                
                
                if(count($affiliationNodes) > 0) {
                    $affilitionString = '';
                    foreach ($affiliationNodes as $affiliationNode) {
                        if($affilitionString !== '') {
                            $affilitionString = $affilitionString . ' ';
                        }
                        $affilitionString = $affilitionString . (string)$affiliationNode . ';';
                    }
                    $contributor['msl_contributor_affiliation'] = $affilitionString;
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
                
                $referenceResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
                
                $identifierNode = $referenceResult->xpath(".//node()[1]");
                $identifierTypeNode = $referenceResult->xpath(".//@relatedIdentifierType");
                $referenceTypeNode = $referenceResult->xpath(".//@relationType");
                
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
                                
                if(isset($referenceTypeNode[0])) {
                    $reference['msl_reference_type'] = (string)$referenceTypeNode[0];
                }                                                         
                
                $dataset->msl_references[] = $reference;
            }
        }
        
        //extract notes
        $result = $xmlDocument->xpath('/dc:resource[1]/dc:descriptions[1]/dc:description[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->notes = (string)$result[0];
        }
        
        //set owner_org
        $dataset->owner_org = $sourceDataset->source_dataset_identifier->import->importer->data_repository->ckan_name;
        
        //set publisher
        $dataset->msl_publisher = 'YoDa Data Repository, Utrecht University, Netherlands';
        
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
                
                $spatialResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
                
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
        $result = $xmlDocument->xpath('/dc:resource/dc:rightsList/dc:rights[count(@*)=0]');      
        if(isset($result[0])) {
            $dataset->license_id = (string)$result[0];
        }
        
        //extract point of contact
        $contactResults = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor[@contributorType='ContactPerson']");
        if(count($contactResults) > 0) {
            foreach ($contactResults as $contactResult) {
                $contact = [
                    'msl_contact_name' => '',
                    'msl_contact_organisation' => '',
                    'msl_contact_electronic_address' => ''
                ];
                
                $contactResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
                
                $nameNode = $contributorResult->xpath(".//dc:contributorName[1]/node()[1]");
                $affiliationNodes = $contributorResult->xpath(".//dc:affiliation/node()");
                                
                
                if(isset($nameNode[0])) {
                    $contact['msl_contact_name'] = (string)$nameNode[0];
                }                
                if(count($affiliationNodes) > 0) {
                    $affilitionString = '';
                    foreach ($affiliationNodes as $affiliationNode) {
                        if($affilitionString !== '') {
                            $affilitionString = $affilitionString . ' ';
                        }
                        $affilitionString = $affilitionString . (string)$affiliationNode . ';';
                    }
                    $contact['msl_contact_organisation'] = $affilitionString;
                }
                                
                $dataset->msl_points_of_contact[] = $contact;
            }
        }
        
        //extract collection period
        $result = $xmlDocument->xpath("/dc:resource/dc:dates/dc:date[@dateType='Collected'][1]");
        if(isset($result[0])) {
            $dateString = (string)$result[0];
            //dd($dateString);
            if(str_contains($dateString, '/') && (strlen($dateString) > 2)) {                
                $parts = explode('/', $dateString);
                if(count($parts) == 2) {
                    $collectionPeriod['msl_collection_start_date'] = $parts[0];
                    $collectionPeriod['msl_collection_end_date'] = $parts[1];
                    
                    $dataset->msl_collection_period[] = $collectionPeriod;
                }
            }
        }
        
        //extract tags/keywords
        $results = $xmlDocument->xpath('/dc:resource/dc:subjects/dc:subject');
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
        
        //add downloadlinks from extra_payload
        $sourceIdentifier = $sourceDataset->source_dataset_identifier;        
        $extraPayload = $sourceIdentifier->extra_payload;
        
        if(isset($extraPayload['dataDocumentation'])) {
            if(strlen($extraPayload['dataDocumentation']) > 1) {
                $file = [
                    'msl_file_name' => $this->extractFilename((string)$extraPayload['dataDocumentation']),
                    'msl_download_link' => (string)$extraPayload['dataDocumentation'],
                    'msl_extension' => $this->extractExtension((string)$extraPayload['dataDocumentation']),
                    'msl_timestamp' => ''
                ];
                
                $dataset->msl_downloads[] = $file;
            }
        }
        
        if(isset($extraPayload['data'])) {
            if(strlen($extraPayload['data']) > 1) {
                $file = [
                    'msl_file_name' => $this->extractFilename((string)$extraPayload['data']),
                    'msl_download_link' => (string)$extraPayload['data'],
                    'msl_extension' => $this->extractExtension((string)$extraPayload['data']),
                    'msl_timestamp' => ''
                ];
                
                $dataset->msl_downloads[] = $file;
            }
        }
        
        //add lab from extra_payload
        $sourceIdentifier = $sourceDataset->source_dataset_identifier;
        $extraPayload = $sourceIdentifier->extra_payload;
        
        if(isset($extraPayload['labIdentifier']) && isset($extraPayload['LabName'])) {
            if((strlen($extraPayload['labIdentifier']) > 1) || (strlen($extraPayload['LabName']) > 1)) {                
                $lab = [
                    'msl_lab_name' => (string)$extraPayload['LabName'],
                    'msl_lab_id' => (string)$extraPayload['labIdentifier']
                ];
                                
                $dataset->addLab($lab);
            }
        }
                
        return $dataset;
    }
}

