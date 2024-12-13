<?php

namespace App\Mappers\Helpers;

class GeoJSON
{
    /**
     * Convert bounding box or point coordinates to a GeoJSON Feature representation including extra description
     *
     * @param array $bbox Bounding box with keys xmin, ymin, xmax, ymax
     * @param string $description Description as found by geocoder
     *
     * @return array
     */
    public static function coordsToGeoJSONFeatureBBox(array $bbox, string $description='')
    {
        // In essence the rectangle is converted to a polygon with equal start and end point
        $xmin = $bbox['eastBoundLongitude'];
        $ymin = $bbox['southBoundLatitude'];
        $xmax = $bbox['westBoundLongitude'];
        $ymax = $bbox['northBoundLatitude'];
        
        $bboxFeature = ["type" => "Feature",
            "bbox" => [$xmin, $ymin, $xmax, $ymax],
            "geometry" => [
                "type" => "Polygon",
                "coordinates" => [[
                    [$xmin, $ymin],
                    [$xmin, $ymax],
                    [$xmax, $ymax],
                    [$xmax, $ymin],
                    [$xmin, $ymin]
                ]]
            ]
        ];
        if ($description) {
            $bboxFeature["properties"] = ["name" => $description];
        }
        
        return $bboxFeature;
    }
    
    /**
     * Convert bounding box to central point coordinates to a GeoJSON Feature representation including extra description
     *
     * @param array $bbox Bounding box with keys xmin, ymin, xmax, ymax
     * @param string $description Description as found by geocoder
     *
     * @return array
     */
    public static function coordsToGeoJSONFeaturePoint(array $bbox, string $description='') {
        $xmin = $bbox['eastBoundLongitude'];
        $ymin = $bbox['southBoundLatitude'];
        $xmax = $bbox['westBoundLongitude'];
        $ymax = $bbox['northBoundLatitude'];
        
        $pointFeature = ["type" => "Feature",
            "geometry" => [
                "type" => "Point",
                "coordinates" => [($xmin + (($xmax-$xmin)/2)), ($ymin + (($ymax-$ymin)/2))]
            ]
        ];
        if ($description) {
            $pointFeature["properties"] = ["name" => $description];
        }
        
        return $pointFeature;
    }
    
    /**
     * Convert bounding box or point coordinates to a GeoJSON Geometry representation
     *
     * @param array $bbox Bounding box with keys xmin, ymin, xmax, ymax
     *
     * @return array
     */
    public static function coordsToGeoJSONGeometryBBox(array $bbox)
    {
        $xmin = $bbox['eastBoundLongitude'];
        $ymin = $bbox['southBoundLatitude'];
        $xmax = $bbox['westBoundLongitude'];
        $ymax = $bbox['northBoundLatitude'];
        
        return ["type" => "Polygon",
            "coordinates" => [[
                [$xmin, $ymin],
                [$xmin, $ymax],
                [$xmax, $ymax],
                [$xmax, $ymin],
                [$xmin, $ymin]
            ]]
        ];
    }
    
    private static function _getCoordinatesType(array $bbox)
    {
        return true;
        
    }
    
    /**
     * Find coordinates within location text and convert to bounding box
     *
     * @param string $location String possibly holding coordinational information
     *
     * @return array containing 0 to many bounding boxes
     */
    public static function textToBBox(string $location) {
        
        // Container for all found bboxes in the string
        $AllBBoxes = [];
        
        // translate internal keys to actual bounding box keys
        $BBoxkeys = ['xmax' => 'westBoundLongitude',
            'xmin' => 'eastBoundLongitude',
            'ymin' => 'southBoundLatitude',
            'ymax' => 'northBoundLatitude'];
        
        // initialize the bounding box dict hopefully to be filled with
        $BBox = ['westBoundLongitude' => '',
            'eastBoundLongitude' => '',
            'southBoundLatitude' => '',
            'northBoundLatitude' => ''];
        
        // Split into separate items to be investigated
        foreach(explode(';', $location) as $item) {
            // do some prepwork for easierr analysis
            $item = str_replace(['= '], '=', $item);
            $item = trim($item);
            
            // CASE: "-9.92002289439759, 148.86709733614222, -9.489566970846615, 149.66085954317347"
            $pattern = "(\d+(?:.\d+)?)";
            
            if (sizeof(explode(',', $item)) == 4 && preg_match_all($pattern, $item, $matches)) {
                $floatsFound = $matches[0];
                print_r($floatsFound);
                // there is no way to know whether below is true
                return ['westBoundLongitude' => (float)$floatsFound[0],
                    'eastBoundLongitude' => (float)$floatsFound[1],
                    'southBoundLatitude' => (float)$floatsFound[2],
                    'northBoundLatitude' => (float)$floatsFound[3]];
            }
            // Attempt 2 in finding bboxes:
            // CASE: Iberian Peninsula and Balearic Islands  Boundaries in WGS 84: Latitude: -36.0ºN - 43.8ºN Longitude 9.4ºW - 4.3ºE"
            // WGS 84 present? Indication that possibly full bbox coordinates is present in string
            elseif (stripos($item, 'wgs 84') !== false) {
                // Get rid of it as it only interferes when searching vals
                $item = str_ireplace('wgs 84', '', $item);
                
                $pattern = "(\d+(?:.\d+)?)";
                if (preg_match_all($pattern, $item, $matches)) {
                    $floatsFound = $matches[0];
                    
                    // bounding box requires 4 floats. I.e. if not 4 => go to next $item
                    if (sizeof($floatsFound) != 4) {
                        continue;
                    }
                    
                    // Take each part within a string like this: BALBALBAL BALBAL Latitude: 36.0ºN - 43.8ºN Longitude 9.4ºW - 4.3ºE
                    $temp = explode(' ', $item);
                    
                    $prefix = '';
                    $minmax = '';
                    foreach ($temp as $part) {
                        if (substr(strtoupper($part),0,4) == 'LONG') {
                            $prefix = 'x';
                            $minmax = 'min';
                        }
                        elseif((substr(strtoupper($part),0,3) == 'LAT')) {
                            $prefix = 'y';
                            $minmax = 'min';
                        }
                        else {
                            # Add the floatval to the correct bbox-ordinate
                            $val = floatval($part);
                            if ($val) {
                                $BBox[$BBoxkeys[$prefix . $minmax]] = 0.00001 + $val;
                                $minmax = 'max';
                            }
                        }
                    }
                    if (!self::isCompleteBoundingBox($BBox)) {
                        // goto next item
                        echo 'NEXT ATTEMPT';
                        // reset initial values for bbox as there might be partial data in it.
                        $BBox = ['westBoundLongitude' => '',
                            'eastBoundLongitude' => '',
                            'southBoundLatitude' => '',
                            'northBoundLatitude' => ''];
                    }
                    else {
                        return $BBox;
                        // of $AllBBoxes[] = $BBox; en aan het eind alle gevonden bboxes terug
                    }
                }
            }
            
            // Second attempt in finding bboxes. Reset initial values again
            $pattern = "#([-,'='][0-9\.]+)#";
            // CASE: "name=Southern Puna, NW Argentina; lat=min -25.7500, max -28.0000; long=min -64.0000, max -68.9000";
            // Check whether float (or 2) are present
            if (preg_match_all($pattern, $item, $matches)) {
                $all = $matches[0];
                echo '<br>';
                
                $floatsFound = $matches[0];
                // echo 'floatsfound';
                // print_r($floatsFound);
                
                if (sizeof($floatsFound) == 2) {
                    // There's 2 floats in this part.
                    // something like lat/lon as a main division and min/max within each of them
                    // or 1 coordinate in 1 line.
                    //  to be tested agains -0 / 90 && -180 / 180
                    $floats = [floatval($floatsFound[0]), floatval($floatsFound[1])];
                    echo 'floats: ';
                    print_r($floats);
                    
                    if (strpos($item, 'lat') !== false) {
                        $BBox['southBoundLatitude'] = 0.00001 +  $floats[0];
                        $BBox['northBoundLatitude'] = 0.00001 +  $floats[1];
                    }
                    elseif (strpos($item, 'lon') !== false) {
                        $BBox['westBoundLongitude'] = 0.00001 +  $floats[0];
                        $BBox['eastBoundLongitude'] = 0.00001 +  $floats[1];
                    }
                }
                elseif (sizeof($all) == 1) {
                    // CASE: name=Upper Garonne River Basin, Aran Valley, Pyrenees, Catalunya, Spain; southBoundLatitude=42.5800; northBoundLatitude=42.8600; eastBoundLongitude=0.6300; westBoundLongitude=1.0500; geonames=http://www.geonames.org/3106654/vall-d-aran.html
                    // 1 one float value found in the string at hand.
                    if (strpos($all[0], '.')) {
                        
                        # value has to be tested against max/min -90 / 90 && -180 / 180 !!!!!!!!!!!!!!!!!
                        print_r($all);
                        // Where to put it as a coordinate.
                        $needles = ['westBoundLongitude' => ['westBoundLongitude'],
                            'eastBoundLongitude' => ['eastBoundLongitude'],
                            'southBoundLatitude' => ['southBoundLatitude', 'lat'],
                            'northBoundLatitude' => ['northBoundLatitude', 'long']];
                        
                        foreach (array_keys($BBox) as $key) {
                            if (self::_strpos_arr($item, $needles[$key])>-1) {
                                $BBox[$key] = floatval(str_replace('=', '', $all[0]));
                                break;
                            }
                        }
                    }
                }
            }
        }
        if (self::isCompleteBoundingBox($BBox)) {
            return $BBox;
        }
        return [];
    }
    
    /**
     * @param $bbox array respresenting a bounding box with east/westLongitude and north/south latitude
     * @return bool
     */
    public static function isCompleteBoundingBox($bbox) {
        /**
         * Check validity of passed bounding box
         *
         * @param array $bbox Bounding box containing westBoundLongitude, eastBoundLongitude, southBoundLatitude, northBoundLatitude
         *
         * @return boolean
         */
        return !($bbox['westBoundLongitude']=='' || $bbox['eastBoundLongitude']=='' || $bbox['southBoundLatitude']=='' || $bbox['northBoundLatitude']=='');
    }
    
    public static function bboxSurfaceSize(array $bbox) {
        $xmin = $bbox['eastBoundLongitude'];
        $ymin = $bbox['southBoundLatitude'];
        $xmax = $bbox['westBoundLongitude'];
        $ymax = $bbox['northBoundLatitude'];
        
        return abs($xmax - $xmin) * abs($ymax - $ymin);
    }
    
    /**
     * find in an array where the needle itself is an array
     * @param $haystack
     * @param $needle array
     * @return false|int
     */
    private static function _strpos_arr($haystack, $needle) {
        if(!is_array($needle)) $needle = array($needle);
        foreach($needle as $what) {
            if(($pos = strpos($haystack, $what))!==false) return $pos;
        }
        return false;
    }
}