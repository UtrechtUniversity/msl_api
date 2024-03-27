<?php
namespace App\Mappers;

use App\Models\SourceDataset;
use App\Models\MappingLog;
use App\Ckan\Request\PackageSearch;
use App\Ckan\Response\PackageSearchResponse;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Models\MaterialKeyword;
use App\Models\ApparatusKeyword;
use App\Models\AncillaryEquipmentKeyword;
use App\Models\PoreFluidKeyword;
use App\Models\MeasuredPropertyKeyword;
use App\Models\InferredDeformationBehaviorKeyword;
use App\Datasets\BaseDataset;
use App\Mappers\Helpers\KeywordHelper;
use App\Mappers\Helpers\GfzDownloadHelper;
use App\Mappers\Helpers\BgsDownloadHelper;


class BgsMapper
{
    protected $client;

    protected $dataciteHelper;

    protected $keywordHelper;

    protected $fileHelper;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
        $this->dataciteHelper = new DataciteCitationHelper();
        $this->keywordHelper = new KeywordHelper();
        $this->fileHelper = new BgsDownloadHelper();
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

        if(str_contains($string, 'https://dx.doi.org/')) {
            $string = str_replace('https://dx.doi.org/', '', $string);
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
        $xmlDocument->registerXPathNamespace('gmd', 'http://www.isotc211.org/2005/gmd');
        $xmlDocument->registerXPathNamespace('gco', 'http://www.isotc211.org/2005/gco');
        $xmlDocument->registerXPathNamespace('xlink', 'http://www.w3.org/1999/xlink');

        $dataset = new BaseDataset();

        //set owner_org
        $dataset->owner_org = $sourceDataset->source_dataset_identifier->import->importer->data_repository->ckan_name;


        //extract title
        $result = $xmlDocument->xpath('/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:title/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->title = (string)$result[0];
        }

        //set publisher
        $dataset->msl_publisher = 'British Geological Survey';

        //extract name
        //doi is not available for all records at bgs so use bgs identifier instead
        $result = $xmlDocument->xpath('/gmd:MD_Metadata/gmd:fileIdentifier/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->name = $this->createDatasetNameFromDoi((string)$result[0]);
        }

        //extract doi
        $result = $xmlDocument->xpath("/gmd:MD_Metadata/gmd:distributionInfo/gmd:MD_Distribution/gmd:transferOptions/gmd:MD_DigitalTransferOptions/gmd:onLine/gmd:CI_OnlineResource[./gmd:name/gco:CharacterString='Digital Object Identifier (DOI)']/gmd:linkage/gmd:URL[1]/node()[1]");
        if(isset($result[0])) {
            $dataset->msl_doi = (string)$result[0];


            $doi = $this->cleanDoi((string)$result[0]);

            $citationString = $this->dataciteHelper->getCitationString($doi);
            if(strlen($citationString > 0)) {
                $dataset->msl_citation = $citationString;
            }
        }

        //extract msl_publication_date
        $result = $xmlDocument->xpath('/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:citation/gmd:CI_Citation/gmd:date/gmd:CI_Date/gmd:date/gco:Date[1]/node()');
        if(isset($result[0])) {
            $dataset->msl_publication_day = $this->getDay((string)$result[0]);
            $dataset->msl_publication_month = $this->getMonth((string)$result[0]);
            $dataset->msl_publication_year = $this->getYear((string)$result[0]);
            $dataset->msl_publication_date = $this->formatDate((string)$result[0]);
        }

        //extract notes
        $result = $xmlDocument->xpath('/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:abstract/gco:CharacterString/node()');
        if(isset($result[0])) {
            $dataset->notes = (string)$result[0];
        }

        //extract tags/keywords
        $results = $xmlDocument->xpath('/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:descriptiveKeywords/gmd:MD_Keywords/gmd:keyword/gco:CharacterString/node()');
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

        //extract point of contact
        $contactResults = $xmlDocument->xpath("/gmd:MD_Metadata/gmd:identificationInfo/gmd:MD_DataIdentification/gmd:pointOfContact");
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
        $dataset->msl_source = $sourceDataset->source_dataset_identifier->identifier;

        //extract file information
        //extract data link from xml
        $result = $xmlDocument->xpath("/gmd:MD_Metadata/gmd:distributionInfo/gmd:MD_Distribution/gmd:transferOptions/gmd:MD_DigitalTransferOptions/gmd:onLine/gmd:CI_OnlineResource[./gmd:function/gmd:CI_OnLineFunctionCode='download']/gmd:linkage/gmd:URL[1]/node()[1]");
        if(isset($result[0])) {
            //extract identifier from retrieved link
            $linkUrl = $result[0];

            if(str_contains($linkUrl, "http://www.bgs.ac.uk/ukccs/accessions")) {
                $baseUrl = "https://webservices.bgs.ac.uk/ccsAccessions/item";
            } else {
                $baseUrl = "https://webservices.bgs.ac.uk/accessions/item";
            }

            if(str_contains($linkUrl, "https://www.bgs.ac.uk/services/ngdc/accessions/index.html#item")) {
                $identifier = str_replace("https://www.bgs.ac.uk/services/ngdc/accessions/index.html#item", '', $linkUrl);
                $dataset = $this->fileHelper->addData($dataset, $identifier, $baseUrl);
            } elseif (str_contains($linkUrl, "https://www.bgs.ac.uk/services/ngdc/accessions/index.html?#item")) {
                $identifier = str_replace("https://www.bgs.ac.uk/services/ngdc/accessions/index.html?#item", '', $linkUrl);
                $dataset = $this->fileHelper->addData($dataset, $identifier, $baseUrl);
            } elseif (str_contains($linkUrl, "http://www.bgs.ac.uk/ukccs/accessions/index.html#item")) {
                $identifier = str_replace("http://www.bgs.ac.uk/ukccs/accessions/index.html#item", '', $linkUrl);
                $dataset = $this->fileHelper->addData($dataset, $identifier, $baseUrl);
            }
        }


        return $dataset;
    }
}

