<?php
namespace App\Mappers\Datacite;

use App\Exceptions\MappingException;
use App\Models\SourceDataset;
use App\Mappers\MapperInterface;
use App\Models\Ckan\DataPublication;

class Datacite4Mapper implements MapperInterface
{

    public function map(SourceDataset $sourceDataset): DataPublication
    {
        // create empty data publication
        $dataset = new DataPublication;

        // read json text
        $metadata = json_decode($sourceDataset->source_dataset, true);

        // map things
        $this->mapTitle($metadata, $dataset);
        $this->mapDescription($metadata, $dataset);
        $this->mapRights($metadata, $dataset);
        $this->mapIdentifier($metadata, $dataset);
        
        return $dataset;
    }


    /**
     * Takes available entries from 'alternateIdentifiers'
     * and adds those to the dataset
     * this is optional
     */
    public function mapAlternateIdentifier(array $metadata, DataPublication $dataset){
        $altIds = $metadata['data']['attributes']['alternateIdentifiers'];

        if($altIds > 0){
            foreach ($altIds as $altIdEntry) {
                $dataset->addAlternateIdentifier(
                    (isset($altIdEntry["alternateIdentifier"])       ? $altIdEntry["alternateIdentifier"]     : ""),
                    (isset($altIdEntry["alternateIdentifierType"])   ? $altIdEntry["alternateIdentifierType"] : "")
                );
            }

         /**
     * Maps the publicationYear of a datacite entry
     * It is a mandatory entry, failure throws exception
     */
    public function mapPublicationYear(array $metadata, DataPublication $dataset){

        $publicationYear = '';

        if(isset($metadata['data']['attributes']['publicationYear'])){
            $publicationYear = $metadata['data']['attributes']['publicationYear'];
        } else {
            throw new MappingException('publicationYear cannot be mapped: does not exist in entry');
        }

        if(strlen($publicationYear) > 0){
            $dataset->msl_publication_year = $publicationYear;
        } else {
            throw new MappingException('publicationYear string empty');


         /**
     * Maps the identifier/doi of a datacite entry
     * It is a mandatory entry, failure throws exception
     */
    public function mapIdentifier(array $metadata, DataPublication $dataset){

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
    public function mapRights(array $metadata, DataPublication $dataset){
        $rights = $metadata['data']['attributes']['rightsList'];
        if($rights >0){
            foreach ($rights as $right) {
                $dataset->addRight(
                    (isset($right["rights"])                    ? $right["rights"]                  : ""), 
                    (isset($right["rightsUri"])                 ? $right["rightsUri"]               : ""), 
                    (isset($right["rightsIdentifier"])          ? $right["rightsIdentifier"]        : ""), 
                    (isset($right["rightsIdentifierScheme"])    ? $right["rightsIdentifierScheme"]  : ""), 
                    (isset($right["schemeUri"])                 ? $right["schemeUri"]               : ""), 
                );
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
    public function mapDescription(array $metadata, DataPublication $dataset){
        $descriptions = $metadata['data']['attributes']['descriptions'];

        $dataset->msl_description_abstract          = $this->receiveDescription('Abstract',            $descriptions);
        $dataset->msl_description_methods           = $this->receiveDescription('Methods',             $descriptions);
        $dataset->msl_description_series_information= $this->receiveDescription('SeriesInformation',   $descriptions);
        $dataset->msl_description_table_of_contents = $this->receiveDescription('TableOfContents',     $descriptions);
        $dataset->msl_description_technical_info    = $this->receiveDescription('TechnicalInfo',       $descriptions);
        $dataset->msl_description_other             = $this->receiveDescription('Other',               $descriptions);

        return $dataset;
    }

    private function receiveDescription(string $descriptionType, array $descriptions): string{
        
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
    public function mapTitle(array $metadata, DataPublication $dataset)
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
            throw new MappingException('No title mapped');
        }

        return $dataset;
    }


    /**
     * This function filters an array based on its "lang" entry
     */
    private function getEntryFilterByLang(array $allCandidates){
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
}