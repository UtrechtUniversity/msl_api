<?php
namespace App\Mappers\Datacite;

use App\Exceptions\MappingException;
use App\Models\SourceDataset;
use App\Mappers\MapperInterface;
use App\Models\Ckan\AlternateIdentifier;
use App\Models\Ckan\DataPublication;
use App\Models\Ckan\Date;
use App\Models\Ckan\FundingReference;
use App\Models\Ckan\RelatedIdentifier;
use App\Models\Ckan\Right;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // map all fields
        // start with the identifier to enable usage in logging/exceptions
        $dataset = $this->mapIdentifier($metadata, $dataset);
        $dataset = $this->mapTitle($metadata, $dataset);
        $dataset = $this->mapDescription($metadata, $dataset);
        $dataset = $this->mapRights($metadata, $dataset);
        $dataset = $this->mapPublicationYear($metadata, $dataset);
        $dataset = $this->mapAlternateIdentifier($metadata, $dataset);
        $dataset = $this->mapRelatedIdentifier($metadata, $dataset);
        $dataset = $this->mapUrl($metadata, $dataset);
        $dataset = $this->mapFundingReference($metadata, $dataset);
        $dataset = $this->mapLanguage($metadata, $dataset);
        $dataset = $this->mapDates($metadata, $dataset);
        
        return $dataset;
    }


    /**
     * Takes available entries from 'alternateIdentifiers'
     * and adds those to the dataset
     * this is optional
     */
    public function mapAlternateIdentifier(array $metadata, DataPublication $dataset): DataPublication
    {
        $altIds = $metadata['data']['attributes']['alternateIdentifiers'];

        if($altIds > 0){
            foreach ($altIds as $altIdEntry) {
                $alternateIdentifier = new AlternateIdentifier(
                    (isset($altIdEntry["alternateIdentifier"])       ? $altIdEntry["alternateIdentifier"]     : ""),
                    (isset($altIdEntry["alternateIdentifierType"])   ? $altIdEntry["alternateIdentifierType"] : "")
                );
                
                $dataset->addAlternateIdentifier($alternateIdentifier);
            }
        }

        return $dataset;
    }     

    /**
     * Maps the publicationYear of a datacite entry
     * It is a mandatory entry, failure throws exception
     */
    public function mapPublicationYear(array $metadata, DataPublication $dataset): DataPublication
    {
        $publicationYear = '';

        if(isset($metadata['data']['attributes']['publicationYear'])){
            $publicationYear = $metadata['data']['attributes']['publicationYear'];
        } else {
            throw new MappingException($dataset->msl_doi . ': publicationYear cannot be mapped: does not exist in entry');
        }

        if(strlen($publicationYear) > 0){
            $dataset->msl_publication_year = $publicationYear;
        } else {
            throw new MappingException($dataset->msl_doi . ': publicationYear string empty');
        }

        return $dataset;
    }


    /**
     * Maps the identifier/doi of a datacite entry
     * It is a mandatory entry, failure throws exception
     */
    public function mapIdentifier(array $metadata, DataPublication $dataset): DataPublication
    {
        $identifier = '';

        if(isset($metadata['data']['attributes']['doi'])){
            $identifier = $metadata['data']['attributes']['doi'];
        } else {
            throw new MappingException('Identifier/doi cannot be mapped: does not exist in entry');
        }

        if(strlen($identifier) > 0){
            $dataset->msl_doi = $identifier;
        } else {
            throw new MappingException('Identifier/doi string empty');
        }

        return $dataset;
    }


     /**
     * Maps the rights of a datacite entry
     * It is an optional entry
     */
    public function mapRights(array $metadata, DataPublication $dataset): DataPublication
    {
        $rights = $metadata['data']['attributes']['rightsList'];
        
        if($rights > 0) {
            foreach ($rights as $right) {
                $right = new Right(
                    (isset($right["rights"])                    ? $right["rights"]                  : ""), 
                    (isset($right["rightsUri"])                 ? $right["rightsUri"]               : ""), 
                    (isset($right["rightsIdentifier"])          ? $right["rightsIdentifier"]        : ""), 
                    (isset($right["rightsIdentifierScheme"])    ? $right["rightsIdentifierScheme"]  : ""), 
                    (isset($right["schemeUri"])                 ? $right["schemeUri"]               : ""), 
                );

                $dataset->addRight($right);
            }
        }

        return $dataset;
    }


    /**
     * chooses one description from the datacite entry according to the following priorities
     * if multiple descriptions are available:
     * 
     * 'descriptionType' not set
     * no "lang" property set
     * "lang" property set and empty
     * 'lang' set to 'en'
     * 'lang set to 'en-GB'
     * if none apply take first in list
     * 
     * ASSUMPTIONS FOR PRIORITIES ARE COMMENTED IN THE FUNCTION
     */
    public function mapDescription(array $metadata, DataPublication $dataset): DataPublication
    {
        $descriptions = $metadata['data']['attributes']['descriptions'];

        $dataset->msl_description_abstract          = $this->receiveDescription('Abstract',            $descriptions);
        $dataset->msl_description_methods           = $this->receiveDescription('Methods',             $descriptions);
        $dataset->msl_description_series_information= $this->receiveDescription('SeriesInformation',   $descriptions);
        $dataset->msl_description_table_of_contents = $this->receiveDescription('TableOfContents',     $descriptions);
        $dataset->msl_description_technical_info    = $this->receiveDescription('TechnicalInfo',       $descriptions);
        $dataset->msl_description_other             = $this->receiveDescription('Other',               $descriptions);

        return $dataset;
    }

    private function receiveDescription(string $descriptionType, array $descriptions): string
    {        
        $descriptionString = '';
        $descriptionsCandidates = [];

        /////////////////  additional notes
        // -> description is optional, so it can be empty
        // -> "descriptionType" is always present
        /////////////////

        // filter if descriptions with descriptionType are present and collect candidates
        foreach($descriptions as $description){
            if(isset($description["descriptionType"]) && $description["descriptionType"] == $descriptionType){
                array_push($descriptionsCandidates, $description);
            }
        }

        if(sizeof($descriptionsCandidates) > 0 ) {            
            return $this->getEntryFilterByLang($descriptionsCandidates)['description'];
        } 

        return $descriptionString;
    }
        

    /**
     * chooses one title from the datacite entry according to the following priorities
     * if multiple titles are available:
     * 
     * 'titleType' not set
     * no "lang" property set
     * "lang" property set and empty
     * 'lang' set to 'en'
     * 'lang set to 'en-GB'
     * if none apply take first in list
     * 
     * ASSUMPTIONS FOR PRIORITIES ARE COMMENTED IN THE FUNCTION
     */
    public function mapTitle(array $metadata, DataPublication $dataset): DataPublication
    {
        $titles = $metadata['data']['attributes']['titles'];
        $titleSize = sizeof($titles);

        // there MUST be at least one title, otherwise it is an exeption
        if( $titleSize > 0) {

            $titlesCandidates = [];

            /////////////////  additional notes
            // -> multiple 'title' entries without 'titleType' must have unique languages
            // -> 'lang' can be empty 
            /////////////////

            if($titleSize > 1 ) {
                // filter the titles for the ones which dont have 'titleType', this is the indicator for main title
                foreach ($titles as $title) {
                    if(!isset($title['titleType']) && isset($title['title']) ) {
                        array_push($titlesCandidates, $title);
                    }
                }

                $dataset->title = $this->getEntryFilterByLang($titlesCandidates)['title'];

                return $dataset;

            } else {   
                // the only title present
                $dataset->title = $metadata['data']['attributes']['titles'][0]['title'];
                return $dataset;
            }

        } else {
            throw new MappingException($dataset->msl_doi . ': No title mapped');
        }

        return $dataset;
    }


    /**
     * This function filters an array based on its "lang" entry
     */
    private function getEntryFilterByLang(array $allCandidates): array
    {
        // check if no "lang" property is set
        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITHOUT TITLE
        foreach ($allCandidates as $candidate) {
            if(!isset($candidate['lang'])){                            
                return $candidate;
            } 
        }

        // "lang" is set but empty
        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH AN EMPTY LANG
        foreach ($allCandidates as $candidate) {
            if ($candidate['lang'] == "") {
                return $candidate;
            }
        }

        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en'
        foreach ($allCandidates as $candidate) {
            if($candidate['lang'] == "en"){
                return $candidate;
            }
        }

        // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en-GB'
        foreach ($allCandidates as $candidate) {
            if ($candidate['lang'] == "en-GB"){
                return $candidate;
            }
        }

        // nothing left. Just take the first one
        return $allCandidates[0];
    }


    /**
     * stores the related identifiers to the dataset
     */
    public function mapRelatedIdentifier(array $metadata, DataPublication $dataset): DataPublication
    {
        $relatedIdentifiers = $metadata['data']['attributes']['relatedIdentifiers'];
        if(sizeof($relatedIdentifiers) > 0) {
            foreach ($relatedIdentifiers as $relId) {
                $relatedIdentifier = new RelatedIdentifier(
                    (isset($relId["relatedIdentifier"])     ? $relId["relatedIdentifier"]                       : ""), 
                    (isset($relId["relatedIdentifierType"]) ? $relId["relatedIdentifierType"]                   : ""), 
                    (isset($relId["relationType"])          ? $relId["relationType"]                            : ""), 
                    (isset($relId["relatedMetadataScheme"]) ? $relId["relatedMetadataScheme"]                   : ""), 
                    (isset($relId["schemeURI"])             ? $relId["schemeURI"]                               : ""), 
                    (isset($relId["schemeType"])            ? $relId["schemeType"]                              : ""), 
                    (isset($relId["resourceTypeGeneral"])   ? $relId["resourceTypeGeneral"]                     : ""), 
                );

                $dataset->addRelatedIdentifier($relatedIdentifier);
            }
        }
        return $dataset;
    }


     /**
     * stores the url to the dataset
     * 
     */
    public function mapUrl(array $metadata, DataPublication $dataset): DataPublication
    {
        $dataset->msl_source = (isset($metadata['data']['attributes']["url"])   ? $metadata['data']['attributes']["url"] : throw new MappingException($dataset->msl_doi . ': No url mapped'));

        return $dataset;
    }

    /**
     * maps the available funding references
     * fundername is madatory
     */
    public function mapFundingReference(array $metadata, DataPublication $dataset): DataPublication
    {
        $funRefs = $metadata['data']['attributes']['fundingReferences'];

        if($funRefs > 0){
            foreach ($funRefs as $funRef) {
                $fundingReference = new FundingReference(
                    (isset($funRef["funderName"])           ? $funRef["funderName"]             : ""),
                    (isset($funRef["funderIdentifier"])     ? $funRef["funderIdentifier"]       : ""),
                    (isset($funRef["funderIdentifierType"]) ? $funRef["funderIdentifierType"]   : ""),
                    (isset($funRef["schemeURI"])            ? $funRef["schemeURI"]              : ""),
                    (isset($funRef["awardNumber"])          ? $funRef["awardNumber"]            : ""),
                    (isset($funRef["awardUri"])             ? $funRef["awardUri"]               : ""),
                    (isset($funRef["awardTitle"])           ? $funRef["awardTitle"]             : ""),
                 );

                 $dataset->addFundingReference($fundingReference);
            }
        }
        return $dataset;
    }
  
    /*
     * stores the language to the dataset
     */
    public function mapLanguage(array $metadata, DataPublication $dataset): DataPublication
    {

        $lang = '';

        if(isset($metadata['data']['attributes']['language'])){
            $lang = $metadata['data']['attributes']['language'];
        } 

        $dataset->msl_language = $lang;
        
        return $dataset;
    }
      
    
     /**
     * stores the related identifiers to the dataset
     */
    public function mapDates(array $metadata, DataPublication $dataset): DataPublication
    {
        $allDates = $metadata['data']['attributes']['dates'];
        
        if(sizeof($allDates) > 0){
            foreach ($allDates as $date) {
                $date = new Date(                
                    (isset($date["date"])              ? $date["date"]            : ""), 
                    (isset($date["dateType"])          ? $date["dateType"]        : ""), 
                    (isset($date["dateInformation"])   ? $date["dateInformation"] : ""), 
                );

                $dataset->addDate($date);
            }
        }

        return $dataset;
    }

         /**
     * stores the related identifiers to the dataset
     */
    public function mapPublisher(array $metadata, DataPublication $dataset): DataPublication
    {
        $allDates = $metadata['data']['attributes']['dates'];
        
        if(sizeof($allDates) > 0){
            foreach ($allDates as $date) {
                $date = new Date(                
                    (isset($date["date"])              ? $date["date"]            : ""), 
                    (isset($date["dateType"])          ? $date["dateType"]        : ""), 
                    (isset($date["dateInformation"])   ? $date["dateInformation"] : ""), 
                );

                $dataset->addDate($date);
            }
        }

        return $dataset;
    }
}