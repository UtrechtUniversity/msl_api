<?php
namespace App\Mappers;

use App\Mappers\Helpers\GeoCoding;
use App\Mappers\Helpers\GeoJSON;
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
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class CsicMapper
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

    public function map(SourceDataset $sourceDataset)
    {
        //load xml file
        $xmlDocument = simplexml_load_string($sourceDataset->source_dataset, SimpleXMLElement::class, LIBXML_NOCDATA);

        //dd($xmlDocument->getNamespaces(true));

        //declare xpath namespaces
        $xmlDocument->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');
        $xmlDocument->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlDocument->registerXPathNamespace('xml', 'http://www.w3.org/XML/1998/namespace');

        $dataset = new BaseDataset();

        // set subdomains
        $dataset->addSubDomain('geochemistry');

        //extract title
        $result = $xmlDocument->xpath('/dc:resource/dc:titles[1]/dc:title[1]/node()[1]');
        if(isset($result[0])) {
            $dataset->title = 'HdR7: ' . (string)$result[0];
        }

        //extract name
        $result = $xmlDocument->xpath("/dc:resource/dc:identifier[@identifierType='DOI']/node()");
        if(isset($result[0])) {
            $dataset->name = $this->createDatasetNameFromDoi((string)$result[0]);
        } else {
            throw new \Exception('No DOI found.');
        }

        //extract doi
        if(isset($result[0])) {
            $dataset->msl_doi = (string)$result[0];
        }

        //extract source
        if(isset($result[0])) {
            $dataset->msl_source = "http://dx.doi.org/" . (string)$result[0];
        }

        //set citation
        $citationString = $this->dataciteHelper->getCitationString((string)$result[0]);
        if(strlen($citationString > 0)) {
            $dataset->msl_citation = $citationString;
        }

        //extract handle
        $result = $xmlDocument->xpath("/dc:resource/dc:alternateIdentifiers/dc:alternateIdentifier[@alternateIdentifierType='Handle']/node()");
        if(isset($result[0])) {
            $dataset->msl_handle = (string)$result[0];
        }

        //extract date
        $result = $xmlDocument->xpath('/dc:resource[1]/dc:date[@dateType="Issued"]/node()[1]');
        if(isset($result[0])) {
            $parts = explode('-', $result[0]);
            if(isset($parts[0])) {
                $dataset->msl_publication_year = $parts[0];
            }
            if(isset($parts[1])) {
                $dataset->msl_publication_month = $parts[1];
            }
            if(isset($parts[2])) {
                $dataset->msl_publication_day = $parts[2];
            }

            $date = new \DateTime($result[0]);
            $dataset->msl_publication_date = $date->format('Y-m-d');
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
                if(isset($identifierNode[0])) {
                    $author['msl_author_identifier'] = (string)$identifierNode[0];
                }
                if(isset($identifierType[0])) {
                    $author['msl_author_identifier_type'] = (string)$identifierType[0];
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

                $nameNode = $contributorResult->xpath(".//node()[1]");
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

                $titleNode = $referenceResult->xpath(".//node()[1]");
                $referenceTypeNode = $referenceResult->xpath(".//@relatedIdentifierType");

                if(isset($titleNode[0])) {
                    $reference['msl_reference_title'] = (string)$titleNode[0];
                }
                if(isset($referenceTypeNode[0])) {
                    $reference['msl_reference_type'] = (string)$referenceTypeNode[0];
                }

                $dataset->msl_references[] = $reference;
            }
        }

        //extract notes
        $result = $xmlDocument->xpath('/dc:resource/dc:descriptions/dc:description[@descriptionType="Abstract"]/node()');
        if(isset($result[0])) {
            $dataset->notes = (string)$result[0];
        }

        //extract technical description
        $result = $xmlDocument->xpath('/dc:resource/dc:descriptions/dc:description[@descriptionType="Description"]/node()');
        if(isset($result[0])) {
            $dataset->msl_technical_description = (string)$result[0];
        }

        //set owner_org
        $dataset->owner_org = $sourceDataset->source_dataset_identifier->import->importer->data_repository->ckan_name;

        //set publisher
        $result = $xmlDocument->xpath('/dc:resource/dc:publisher[1]/node()');
        if(isset($result[0])) {
            $dataset->msl_publisher = (string)$result[0];
        }

        //extract labs
        $labResults = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor[@contributorType=\"hostingInstitution\"]");        //
        if(count($labResults) > 0) {
            foreach ($labResults as $labResult) {
                $lab = [
                    'msl_lab_name' => '',
                    'msl_lab_id' => ''
                ];

                $nameNode = $labResult->xpath(".//node()");
                if(isset($nameNode[0])) {
                    $lab['msl_lab_name'] = (string)$nameNode[0];
                }

                $this->log('WARNING', "Lab with name: \"" . $lab['msl_lab_name'] . "\" has no id.", $sourceDataset);

                $dataset->addLab($lab);
            }
        }
/*
        //extract geo locations
        $locationsResult = $xmlDocument->xpath("/dc:resource/dc:geoLocations/dc:geoLocation/dc:geoLocationPlace");
        if(count($locationsResult) > 0) {
            $geoLocations = [];
            foreach ($locationsResult as $locationResult) {
                $geoLocation = ['msl_geolocation_place' => (string)$locationResult[0]];
                $geoLocations[] = $geoLocation;

                // print((string)$locationResult[0]);
                Log::info((string)$locationResult[0]);

                // Per location find the best coordinates based on Geocoding
                // Hdr
                $result = GeoCoding::findBestCoordinatesAndDescription('Zwolle');
                if ($result['foundMatch']) {
                    $spatial = ['msl_elong' => (string) $result['spatialCoordinates']['xmin'],
                        'msl_nLat' => (string) $result['spatialCoordinates']['ymin'],
                        'msl_sLat' => (string) $result['spatialCoordinates']['xmax'],
                        'msl_wLong' => (string) $result['spatialCoordinates']['ymax']
                    ];
                    $dataset->msl_spatial_coordinates[] = $spatial;

                    $temp = GeoJSON::coordsToGeoJSONFeature($result['spatialCoordinates'], $result['additionalDescription']);
                    if ($temp) {
                        $feature1 = $temp;
                    }


                    // $dataset->extras[] = ["key"=>"spatial", "value" => json_encode($geoJSONBox)];

                    $result = GeoCoding::findBestCoordinatesAndDescription('Breda');
                    if ($result['foundMatch']) {
                        $spatial = ['msl_elong' => (string) $result['spatialCoordinates']['xmin'],
                            'msl_nLat' => (string) $result['spatialCoordinates']['ymin'],
                            'msl_sLat' => (string) $result['spatialCoordinates']['xmax'],
                            'msl_wLong' => (string) $result['spatialCoordinates']['ymax']
                        ];
                        $dataset->msl_spatial_coordinates[] = $spatial;

                        $temp = GeoJSON::coordsToGeoJSONFeature($result['spatialCoordinates'], $result['additionalDescription']);
                        if ($temp) {
                            $feature2 = $temp;
                        }
                        $temp = GeoJSON::coordsToGeoJSONGeometry($result['spatialCoordinates']);
                        if ($temp) {
                            $geometry2 = $temp;
                        }

                        // possibly not per loop but before!!
                        $features = [];
                        $geometries = [];

                        $features[] = $feature1;
                        $features[] = $feature2;

                        // featureCollection is for mapping functionality frontend
                        $featureCollection = ["type" => "FeatureCollection", "features" => $features];
                        // geometryCollection is for SOLR
                        $geometryCollection = ["type" => "GeometryCollection", "geometries" => $geometries];
                        // Dit moet GeoCollection worden!??

                        $dataset->extras[] = ["key"=>"spatial", "value" => json_encode($featureCollection)];
                    }
                }
            }
            // dd($dataset);
            // moet het 1 groot geoJSON opject worden? of n kleinere??
            // featureCollection is for mapping functionality frontend
            $featureCollection = ["type" => "FeatureCollection", "features" => $features];
            // geometryCollection is for SOLR
            $geometryCollection = ["type" => "GeometryCollection", "geometries" => $geometries];

            $dataset->msl_geolocations = $geoLocations;
        }
*/
        //extract geo locations
        // get location info and try to find bboxes in them
        // GEO LOCATION HANDLING
        // GeoJSON containers to become collections of Geometries (only boxes) and Features (split into boxes and points)
        $featuresBox = [];
        $featuresPoint = [];
        $geometriesBox = [];

        $locationsResult = $xmlDocument->xpath("/dc:resource/dc:geoLocations/dc:geoLocation/dc:geoLocationPlace");
        if(count($locationsResult) > 0) {
            $geoLocations = [];
            // GeoJSON entities containers to be added to the corresponding collections after the loop.
            foreach ($locationsResult as $locationResult) {
                $geoLocation = ['msl_geolocation_place' => (string)$locationResult[0]];
                $geoLocations[] = $geoLocation;

                // print((string)$locationResult[0]);
                Log::info((string)$locationResult[0]);

                // Find coordinates within the geloLocation description
                // For now we consider only one bbox coming from location texts. This should be reconsidered and code has to be rewritten.
                $bbox = GeoJSON::textToBBox($geoLocation['msl_geolocation_place']);  //GeoCoding::findBestCoordinatesAndDescription('Zwolle');
                if ($bbox == []) {
                    $result = GeoCoding::findBestCoordinatesAndDescription($geoLocation['msl_geolocation_place']);
                    if ($result['foundMatch']) {
                        $bbox['eastBoundLongitude'] = (float)$result['spatialCoordinates']['xmin'];
                        $bbox['westBoundLongitude'] = (float)$result['spatialCoordinates']['xmax'];
                        $bbox['southBoundLatitude'] = (float)$result['spatialCoordinates']['ymin'];
                        $bbox['northBoundLatitude'] = (float)$result['spatialCoordinates']['ymax'];
                    }
                }
                if ($bbox != []) {
                    $spatial = ['msl_elong' => (string)$bbox['eastBoundLongitude'],
                        'msl_nLat' => (string)$bbox['northBoundLatitude'],
                        'msl_sLat' => (string)$bbox['southBoundLatitude'],
                        'msl_wLong' => (string)$bbox['westBoundLongitude']
                    ];
                    $dataset->msl_spatial_coordinates[] = $spatial;

                    // GeoJSON Feature representation
                    if (($feature = GeoJSON::coordsToGeoJSONFeatureBBox($bbox, 'Interpreted coordinates')) && $feature != []) {
                        $featuresBox[] = $feature;
                        // build the array with central points for presentation purposes in the frontend of CKAN.
                        $featuresPoint[] = GeoJSON::coordsToGeoJSONFeaturePoint($bbox, 'Interpreted coordinates');
                    }

                    // GeoJSON Geometry representation for SOLR search purposes
                    if (($geometry = GeoJSON::coordsToGeoJSONGeometryBBox($bbox)) && $geometry != []) {
                        $geometriesBox[] = $geometry;
                    }
                }
            }
            $dataset->msl_geolocations = $geoLocations;
        }
        // GEO spatial handling: bring into the dataset if applicable
        if (sizeof($featuresBox)) {
            // featureCollection is for mapping functionality frontend
            $featureCollectionBoxes = ["type" => "FeatureCollection", "features" => $featuresBox];

            // For presentation purposes (only show the central points of the bounding boxes)
            $featureCollectionPoints = ["type" => "FeatureCollection", "features" => $featuresPoint];

            $dataset->msl_geojson_feature_points = json_encode($featureCollectionPoints);
            $dataset->msl_geojson_feature_boxes = json_encode($featureCollectionBoxes);
        }
        if (sizeof($geometriesBox)) {
            // geometryCollection is for SOLR
            $geometryCollectionBoxes = ["type" => "GeometryCollection", "geometries" => $geometriesBox];

            $dataset->msl_geojson_geometry_boxes = json_encode($geometryCollectionBoxes);

            $dataset->extras[] = ["key" => "spatial", "value" => json_encode($geometryCollectionBoxes)];
        }

        //extract license id
        $result = $xmlDocument->xpath('/dc:resource/dc:rightsList/dc:rights/@rightsURI');
        if(isset($result[0])) {
            $dataset->license_id = (string)$result[0];
        }

        //extract point of contact
        $contactResults = $xmlDocument->xpath("/dc:resource[1]/dc:contributors/dc:contributor[@contributorType=\"contactPerson\"]");
        if(count($contactResults) > 0) {
            foreach ($contactResults as $contactResult) {
                $contact = [
                    'msl_contact_name' => '',
                    'msl_contact_organisation' => '',
                    'msl_contact_electronic_address' => ''
                ];

                $contactResult->registerXPathNamespace('dc', 'http://datacite.org/schema/kernel-4');

                $contact['msl_contact_name'] = (string)$contactResult[0];

                $dataset->msl_points_of_contact[] = $contact;
            }
        }

        //extract tags/keywords
        $results = $xmlDocument->xpath('/dc:resource/dc:subjects/dc:subject');
        //dd($results);
        if(count($results) > 0) {
            $keywords = [];
            foreach ($results as $result) {
                $keywords[] = (string)$result[0];
            }

            $dataset = $this->keywordHelper->mapKeywords($dataset, $keywords);
        }

        //attempt to map keywords from abstract and title
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->title, 'title');
        $dataset = $this->keywordHelper->mapKeywordsFromText($dataset, $dataset->notes, 'notes');

        return $dataset;
    }
}

