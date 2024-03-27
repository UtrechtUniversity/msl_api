<?php

namespace App\Mappers\Helpers;

class GeoCoding
{
    /**
     * Find best (for now is first with score==100) found candidate from arcgis response
     *
     * @param string $location Location to search for
     *
     * @return array $result Response holds:
     *      foundMatch
     *      spatialCoordinates: xmin=1, ymin=2, xmax=3, ymax=4 Complete extent from arcgis response
     *      additionalDescription: to be filled with whatever developer finds helpful
     */
    public static function findBestCoordinatesAndDescription(string $location)
    {
        $result = ["foundMatch" => false, "spatialCoordinates" => [], "additionalDescription" => ""];

        $response = self::findLocations($location);

        if (array_key_exists('candidates', $response)) {
            $candidates = $response['candidates'];

            // First order by the candidates by score to ensure highest received scores are the first to be found
            self::_arraySortByColumn($candidates, 'score', SORT_DESC);

            // Create an array of the highest found score with relevant data.
            // Add extra column with a weight indication for certain candidate types
            $typeWeightedData = [];
            // will hold the highest found score within the list of candidates and will serve as a reference for comparison whether
            // candidates still must be included.
            $hiscore = -1;
            foreach ($candidates as $item) {
                if ($hiscore == -1) {
                    $hiscore = $item['score'];
                }
                if ($item['score'] == $hiscore) {
                    $typeWeightedData[] = ['type_weight' => self::_getTypeWeight($item['attributes']['Type']),
                        'type' => $item['attributes']['Type'],
                        'Match_addr' => $item['attributes']['Match_addr'],
                        'extent' => $item['extent']];
                } else {
                    // As we have ordered on score we can safely stop collecting data now.
                    break;
                }
            }

            // Order the highest scored data on the weight of the candidate type to ensure that the most interesting types are at the top of the list.
            // Order the subselection of highest scored items that
            self::_arraySortByColumn($typeWeightedData, 'type_weight', SORT_ASC);

            if (sizeof($typeWeightedData)) {
                // Pick the first as is the most 'interesting'
                echo '<hr>';
                print_r($typeWeightedData[0]);
                $result["foundMatch"] = true;
                $result["spatialCoordinates"] = $typeWeightedData[0]['extent']; // Keep the arcgis way xmin, ymin, xmax, ymax
                // take some interesting descriptive attributes
                /** Choose from [attributes]:
                 * [Match_addr] => Groningen
                 * [LongLabel] => Groningen, NLD
                 * [ShortLabel] => Groningen
                 * [Addr_type] => Locality
                 * [Type] => City
                 * [PlaceName] => Groningen
                 * [Place_addr] => Groningen
                 */
                $result["additionalDescription"] = $typeWeightedData[0]['Match_addr'];
            }
        }
        return $result;
    }

    /**
     * Find coordinates of given location
     *
     * @param string $location Location to search for
     * @param string $type which geocoder to be user. Defaults to arcgis
     *
     * @return array $result
     */
    public static function findLocations(string $location)
    {
        $client = new \GuzzleHttp\Client();

        $token = config('arcgis.arcgis_api_token');

        // It is possible to limit the returned fields like:
        // https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?address=alps&outFields=PlaceName,Type,Place_Addr,City,Region,Country&maxLocations=100&forStorage=false&f=pjson
        // HdR Max length of string = 100 !
        $url = config('arcgis.arcgis_api_url') . "?address=" . urlencode(substr($location, 0, 100)) . "&outFields=*&f=json&token=" . $token;

        $response = $client->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/vnd.api+json',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            $jsonResponse = $response->getBody();
            return json_decode($jsonResponse, true);
        }
        // empty array as indication that nothing was found or erroneous situation
        return [];
    }

    /**
     * Sort an array by given column name
     *
     * @param array $arr Array to be sorted as a receive variable
     * @param string $col Column on which array will be sorted
     * @param int  sorting direction (SORT_ASC, SORT_DESC and more)
     *
     * @return Nothing
     */
    private static function _arraySortByColumn(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    /**
     * Get a weight indication for given candidate type to be able to distinguish importance of returned candidates
     *
     * @param string $type Type of the candidate (type indications as defined by ARCGIS/ESRI)
     * Populated places:
     * Block, Sector, Neighborhood, District, City, Metro Area, Subregion, Region, Territory, Country, Zone
     * Land features:
     * Atoll, Basin, Butte, Canyon, Cape, Cave, Cliff, Continent, Desert, Dune, Flat, Forest, Glacier, Grassland, Hill, Island
     * Isthmus, Lava, Marsh, Meadow, Mesa, Mountain, Mountain Range, Oasis, Other Land Feature
     * Peninsula, Plain, Plateau, Point, Ravine, Ridge, Rock, Scrubland, Swamp, Valley, Volcano, Wetland
     *
     * @return int (the higher the LESS significant)
     */
    private static function _getTypeWeight($type) {
        // most important types declared first
        $type_weights = ['Island', 'City', 'Volcano'];
//        $type_weights = ['City', 'Volcano'];

        $key = array_search($type, $type_weights);
        if ($key === false) {
            return 99999;
        }
        return $key;
    }
}
