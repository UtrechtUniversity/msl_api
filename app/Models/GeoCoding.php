<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoCoding extends Model
{
//    public $fillable = [
//        'parent_id',
//        'value',
//        'searchvalue'
//    ];

    public static function findLocations(string $location, string $type)
    {
        $client = new \GuzzleHttp\Client();
        $jsonResponse = '';

        switch ($type) {
            case 'arcgis':
                $key = 'AAPK9d9984c316bc4777933239451da0efef6yRH6v2BOVXPaHeukp44ZwaWwSjgQ8HBhK07qiO6PCWa3L12mcK8PBG2E3_D5DkB';
                // $url = "https://geocode-api.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?address={searchText}&outFields={fieldList}&f=json&token=<ACCESS_TOKEN>"
                $url = "https://geocode-api.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?address=" . urlencode($location) . "&outFields=*&f=json&token=" . $key;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $jsonResponse = curl_exec($ch);

                curl_close($ch);

                break;
            case 'tomtom':
                $keyTomTom = 'Y1Z97QeDtGvxgZIIAG8NvYFdZC8FF4Py';
                $url = "https://api.tomtom.com/search/2/geocode/".urlencode($location).".json?key=" . $keyTomTom;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $jsonResponse = curl_exec($ch);

                curl_close($ch);
                break;

            case 'openstreetmap':
                $location = "Breda";
                $url = "https://nominatim.openstreetmap.org/search.php?q=" . urlencode($location) . "&format=jsonv2";
                echo $url;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $jsonResponse = curl_exec($ch);
                print_r($jsonResponse);
                dd($jsonResponse);

                curl_close($ch);
                break;


//                $response = $client->request('GET', $url, [
//                    'headers' => [
//                        'Accept' => 'application/vnd.api+json',
//                    ],
//                ]);
//                // echo 'openstreetmap';
//                $jsonResponse = $response->getBody();
//                print_r($jsonResponse);
//                dd($response);
//
//                break;

            case 'positionstack':
                $queryString = http_build_query([
                    'access_key' => '12513b3114671a5e4687ce7c24d13522',
                    'query' => $location,
                    'region' => $location,
                    'output' => 'json',
                    'limit' => 1,
                ]);

                $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // echo 'positionstack';
                $jsonResponse = curl_exec($ch);

                curl_close($ch);


                //print_r($jsonResponse);
                ///dd($jsonResponse);

                break;
        }

        return json_decode($jsonResponse, true);

//        $url = "https://nominatim.openstreetmap.org/search.php?q=" . urlencode($location) . "&format=jsonv2";
//        $response = $client->request('GET', $url, [
//            'headers' => [
//                'Accept' => 'application/vnd.api+json',
//            ],
//        ]);
//
//        return json_decode($response->getBody(), true);

        // return $location . '-' . $type;
    }
}
