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

        // map title
        $this->mapTitle($metadata, $dataset);

        $this->mapTitle($metadata, $dataset);
        
        return $dataset;
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

                if(sizeof($titlesCandidates) == 1) {
                    $dataset->title = $titlesCandidates[0]['title'];
                    return $dataset;

                } else {

                    // check if no "lang" property is set
                    // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITHOUT TITLE
                    foreach ($titlesCandidates as $candidate) {
                        if(!isset($candidate['lang'])){                            
                            $dataset->title = $candidate['title'];
                            return $dataset;
                        } 
                    }

                    // "lang" is set but empty
                    // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH AN EMPTY LANG
                    foreach ($titlesCandidates as $candidate) {
                            if ($candidate['lang'] == "") {
                            $dataset->title = $candidate['title'];
                            return $dataset;
                        }
                    }

                    // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en'
                    foreach ($titlesCandidates as $candidate) {
                        if($candidate['lang'] == "en"){
                            $dataset->title = $candidate['title'];
                            return $dataset;
                        }
                    }

                    // THIS ASSUMES THAT THERE CAN BE ONLY ONE ENTRY WITH 'en-GB'
                    foreach ($titlesCandidates as $candidate) {
                        if ($candidate['lang'] == "en-GB"){
                            $dataset->title = $candidate['title'];
                            return $dataset;
                        }
                    }

                    // nothing left. Just take the first one
                    $dataset->title = $titlesCandidates[0]['title'];
                    return $dataset;

                }

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
}