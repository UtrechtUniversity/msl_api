<?php
namespace App\Mappers\Datacite;

use PHPUnit\Util\Json;
use PhpParser\JsonDecoder;
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

        dd($dataset->title);

        return $dataset;
    }


    public function mapTitle(array $metadata, DataPublication $dataset)
    {
        // check if present
        // check for multiple occurences
        // etc
        // dd($metadata);
        $titles = $metadata['data']['attributes']['titles'];
        $titleSize = sizeof($titles);

        if( $titleSize > 0) {

            $titlesCandidates = [];

            // main title is wihtout title type
            // multiple without title type must have unique languages
            // one title wihtout title type and no language and one that can have a language
            // language can be empty

            // check if there is more than one title present if not then enter more detailed selection
            if($titleSize > 1 )
            {
                // filter the titles for the ones which dont have 'titleType', this is indicator for main title
                foreach ($titles as $title) {
                    if(!isset($title['titleType']) && isset($title['title']) ){
                        array_push($titlesCandidates, $title);
                    }
                }


                if(sizeof($titlesCandidates) == 1){
                    // if only one is left then take that one
                    $dataset->title = $titlesCandidates[0]['title'];
                    return $dataset;

                } else {


                    // check if no "lang" property is set
                    foreach ($titlesCandidates as $candidate) {


                        if(!isset($candidate['lang'])){
                            // check if there is an entry without a "lang"
                            // if so then take it
                            $dataset->title = $candidate['title'];
                            return $dataset;

                        } elseif ($candidate['lang'] == "") {
                            // "lang" is set but empty
                            // take it
                            $dataset->title = $candidate['title'];
                            return $dataset;

                        }elseif (!str_contains($candidate['lang'], "en")){
                            // lang does not contain "en"
                            // remove it
                            unset($titlesCandidates[array_search($candidate, $titlesCandidates)]);
                        }

                    }


                    // now if only one candidate remains with "en" in "lang" take it
                    if(sizeof($titlesCandidates) == 1){
                        // if only one is left then take that one
                        $dataset->title = $titlesCandidates[0]['title'];
                        return $dataset;
    
                    } else {

                        foreach ($titlesCandidates as $candidate) {
                            if($candidate['lang'] == "en"){

                                $dataset->title = $candidate['title'];
                                return $dataset;

                            } elseif ($candidate['lang'] == "en-GB"){

                                $dataset->title = $candidate['title'];
                                return $dataset;
                            } else {
                                // exception handling? dotn fill out title
                                // then it should be stopped 
                                $dataset->title = "No title found";
                                return $dataset;
                            }
                        }


                    }

                    dd($titlesCandidates);


                }

            }
            else
            {   
                // the only title present
                $dataset->title = $metadata['data']['attributes']['titles'][0]['title'];
                return $dataset;

            }

        } else {
            // exception handling? dotn fill out title
            // then it should be stopped 
            $dataset->title = "No title found";
            return $dataset;

        }

        return $dataset;
    }

}