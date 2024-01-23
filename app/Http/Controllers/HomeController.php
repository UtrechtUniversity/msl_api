<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Phpoaipmh\Endpoint;
use App\Ckan\Request\PackageSearch;
use App\Models\DatasetDelete;
use App\Jobs\ProcessDatasetDelete;
use App\Models\DataRepository;
use App\Models\Importer;
use App\Models\Import;
use App\Models\KeywordSearch;
use App\Jobs\ProcessImport;
use App\Models\SourceDatasetIdentifier;
use App\Models\SourceDataset;
use App\Mappers\GfzMapper;
use App\Models\DatasetCreate;
use App\Ckan\Request\PackageCreate;
use App\Ckan\Request\PackageShow;
use App\Ckan\Response\PackageShowResponse;
use App\Ckan\Request\PackageUpdate;
use App\Ckan\Request\OrganizationList;
use App\Ckan\Response\OrganizationListResponse;
use App\Models\MappingLog;
use App\Ckan\Response\PackageSearchResponse;
use App\Mappers\Helpers\DataciteCitationHelper;
use App\Converters\MaterialsConverter;
use App\Exports\MappingLogsExport;
use App\Models\MaterialKeyword;
use App\Converters\RockPhysicsConverter;
use App\Datacite\Datacite;
use App\Mappers\YodaMapper;
use App\Models\Keyword;
use App\Exports\FilterTreeExport;
use App\Datasets\BaseDataset;
use App\Mappers\CsicMapper;
use EasyRdf;
use App\Models\GeoCoding;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        return view('home');
    }

    public function removeDataset()
    {
        $client = new \GuzzleHttp\Client();
        $OrganizationListrequest = new OrganizationList();

        try {
            $response = $client->request($OrganizationListrequest->method,
                $OrganizationListrequest->endPoint,
                $OrganizationListrequest->getPayloadAsArray());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        $organizationListResponse = new OrganizationListResponse(json_decode($response->getBody(), true), $response->getStatusCode());
        $organizations = $organizationListResponse->getOrganizations();

        return view('remove-dataset', ['organizations' => $organizations]);
    }

    public function removeDatasetConfirm(Request $request)
    {
        $results = [];

        if($request->has('datasetId')) {
            $datasetId = $request->query('datasetId');
            if($datasetId) {
                $client = new \GuzzleHttp\Client();

                $searchRequest = new PackageSearch();
                $searchRequest->query = 'name:' . $datasetId;
                try {
                    $response = $client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
                } catch (\Exception $e) {

                }

                $content = json_decode($response->getBody(), true);
                $results = $content['result']['results'];

                return view('remove-dataset-confirm', ['results' => $results]);
            } else {

            }
        } elseif ($request->has('datasetSource')) {
            $datasetSource = $request->query('datasetSource');

            if($datasetSource) {
                $client = new \GuzzleHttp\Client();

                $searchRequest = new PackageSearch();
                $searchRequest->rows = 1000;
                $searchRequest->query = 'owner_org:' . $datasetSource;
                try {
                    $response = $client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
                } catch (\Exception $e) {

                }

                $content = json_decode($response->getBody(), true);
                $results = $content['result']['results'];

                return view('remove-dataset-confirm', ['results' => $results]);
            }
        } else {

        }

        return view('remove-dataset-confirm', ['results' => $results]);
    }

    public function removeDatasetConfirmed(Request $request)
    {
        if($request->has('names')) {
            $names = $request->input('names');

            foreach ($names as $name) {
                $datasetDelete = DatasetDelete::create([
                    'ckan_id' => $name
                ]);

                ProcessDatasetDelete::dispatch($datasetDelete);
            }

            $request->session()->flash('status', 'Task was successful!');
        }
        return redirect()->route('home');
    }

    public function queues()
    {
        $deletes = DatasetDelete::where('response_code', null)->get();

        return view('queues', ['deletes' => $deletes]);
    }

    public function deleteActions()
    {
        $deletes = DatasetDelete::paginate(50);

        return view('deletes', ['deletes' => $deletes]);
    }

    public function importers()
    {
        $importers = Importer::all();
        // HdR

        echo "Importers";

        $geoCoding = "openstreetmap";
//        $geoCoding = "postionstack";
//        $geoCoding = "tomtom";
//        $geoCoding = "arcgis";

//        $key = '12513b3114671a5e4687ce7c24d13522';
        // $keyTomTom = 'Y1Z97QeDtGvxgZIIAG8NvYFdZC8FF4Py';

//        $queryString = http_build_query([
//            'access_key' => $key,
//            'query' => '1600 Pennsylvania Ave NW',
//            'region' => 'Washington',
//            'output' => 'json',
//            'limit' => 1,
//        ]);

//        $queryString = http_build_query([
//            'access_key' => $key,
//            'query' => 'Groningen',
//            'region' => 'Groningen',
//            'output' => 'json',
//            'limit' => 1,
//        ]);
//
//        $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $json = curl_exec($ch);
//
//        curl_close($ch);
//
//        $apiResult = json_decode($json, true);
//
//        print_r($apiResult);

        $client = new \GuzzleHttp\Client();
//        $client2 = new \GuzzleHttp\Client();
        $result =  new \stdClass();

        try {
            $response = $client->request('GET', "http://msl_api.test/api/all", [
                'headers' => [
                    'Accept' => 'application/vnd.api+json',
                ],
            ]);
            $json = json_decode($response->getBody(), true);

            // Settings
            $coders = ['tomtom', 'arcgis', 'positionstack']; // openstreetmap
            $packages_of_interest = '*'; //'*'; // determine which packages to show
            $locations_of_interest = '4.1'; // '4.1,4.2'; //'2.1'; //'2.2'; //'5.0'; //'5.1';
            $dataResponseLevel = 3;

            $responseDataLabel = [];
            $responseDataLabel['tomtom'] = 'results';
            $responseDataLabel['arcgis'] = 'candidates';
            $responseDataLabel['positionstack'] = 'data';
            $responseDataLabel['openstreetmap'] = 'data';  //???

            echo '<table border="1">';
            foreach ($json['result']['results'] as $index=>$package) {
                if ($packages_of_interest == '*' || in_array($index, explode(',',$packages_of_interest))) {
                    echo '<tr><td>';
                    echo "<hr>";
                    echo "<b>$index: Title: " . $package['title'] . "</b>";
                    echo '</td></tr>';
                    echo "<tr>";
                    echo "<td>";
                    if (count($package['locations']) == 0) { echo "NO LOCATIONS"; }

                    echo "<table>";
                    foreach ($package['locations'] as $indexLocation=>$location) {
                        echo "<tr><td colspan='" . strval(count($coders)) . "'>";
                        echo '<hr>';
                        echo "$index.$indexLocation ---Find location: $location";
                        echo "</td></tr>";

                        if ($locations_of_interest == '*' || in_array("$index.$indexLocation", explode(',',$locations_of_interest))) {
                            echo "<tr>";
                            foreach ($coders as $geoCoder) {
                                echo "<td style='vertical-align: top;'>";
                                echo strtoupper($geoCoder);
                                echo "<pre style='overflow-y: scroll; max-height: 750px; max-width: ". (1900/count($coders)) ."px;'>";

                                if ($dataResponseLevel == 0) { // ALL data
                                    print_r(GeoCoding::findLocations($location, $geoCoder));
                                }
                                elseif ($dataResponseLevel == 1) { // Location data only
                                    print_r(GeoCoding::findLocations($location, $geoCoder)[$responseDataLabel[$geoCoder]]);
                                }
                                elseif ($dataResponseLevel == 2) { // First found data element only
                                    print_r(GeoCoding::findLocations($location, $geoCoder)[$responseDataLabel[$geoCoder]][0]);
                                }
                                elseif (in_array($dataResponseLevel, [3,4])) { // Zoomed (with (4) our without (3) full dataset for exploration purposes)
                                    $data = GeoCoding::findLocations($location, $geoCoder)[$responseDataLabel[$geoCoder]];
                                    if ($geoCoder == 'positionstack') {
                                        print_r(GeoCoding::findLocations($location, $geoCoder)[$responseDataLabel[$geoCoder]][0]);
                                    }
                                    else {
                                        $data = GeoCoding::findLocations($location, $geoCoder)[$responseDataLabel[$geoCoder]];
                                        foreach($data as $loc) {
                                            if ($geoCoder == 'tomtom') {
                                                echo '<strong>score: ' . $loc['matchConfidence']['score']*100 . "</strong>";
                                                echo '<br>';
                                                echo 'type: ' . $loc['type'];
                                                if (array_key_exists('entityType', $loc)) {
                                                    echo ' - ' . $loc['entityType'];
                                                }
                                                echo '<br>';
                                                print_r($loc['position']);
                                                print_r($loc['address']);
                                            }
                                            elseif ($geoCoder == 'arcgis') {
                                                echo '<strong>score: ' . $loc['score'] . "</strong>";
                                                echo '<br>';
                                                echo 'type: ' . $loc['attributes']['Type'];
                                                echo '<br>';
                                                $temp = [];
                                                $temp['lat'] = $loc['location']['y'];
                                                $temp['lon'] = $loc['location']['x'];
                                                print_r($temp);
                                                // print_r($loc['location']);
                                                echo "-- " . $loc['attributes']['Match_addr'];
                                                echo "<br>-- " . $loc['attributes']['LongLabel']; // => Groningen, NLD
                                                echo "<br>-- " . $loc['attributes']['ShortLabel']; // => Groningen
                                                echo '<br>';
                                            }
                                            echo '<br>';
                                        }
                                        if ($dataResponseLevel == 4) {
                                            echo '<hr>';
                                            print_r($data);
                                        }
                                    }

                                }
                                echo "</pre><br>";
                                echo "</td>";
                            }
                            echo "</tr>";
                        }
                    }

                    echo "</table>";

                    echo "</td>";
                    echo '</tr>';
                }
                //if ($index==1) {break;}
            }
            echo '</table>';
        } catch (\Exception $e) {
            // if($retryOnFailure) {
            //    sleep(1);
            //    $this->doisRequest($doi);
            // }

            $result->response_code = $e->getCode();
            $result->response_body = [];
            dd($result);
        }


        return view('importers', ['importers' => $importers]);
    }

    public function importerImports($id)
    {
        $imports = Import::where('importer_id', (int)$id)->get();
        $importer = Importer::where('id', $id)->first();

        return view('importer-imports', ['imports' => $imports, 'importer' => $importer]);
    }

    public function importerImportsFlow($id, $importId)
    {
        $sourceDatasetidentifiers = SourceDatasetIdentifier::where('import_id', $importId)->paginate(50);

        return view('importer-import-flow', ['sourceDatasetIdentifiers' => $sourceDatasetidentifiers, 'importer_id' => $id, 'import_id' => $importId]);
    }

    public function importerImportsLog($id, $importId)
    {
        $logs = MappingLog::where('import_id', $importId)->paginate(50);

        return view('importer-import-log', ['logs' => $logs, 'importer_id' => $id, 'import_id' => $importId]);
    }

    public function exportImportLog($id, $importId)
    {
        return Excel::download(new MappingLogsExport($importId), 'log.xlsx');
    }

    public function importerImportsDetail($importerid, $importId, $sourceDatasetIdentifierId)
    {
        $sourceDatasetIdentifier = SourceDatasetIdentifier::where('id', $sourceDatasetIdentifierId)->first();

        if($sourceDatasetIdentifier) {
            return view('importer-import-detail', ['sourceDatasetIdentifier' => $sourceDatasetIdentifier, 'importer_id' => $importerid, 'import_id' => $importId]);
        }

        abort(404, 'Invalid data requested');
    }

    public function createimport(Request $request)
    {
        if($request->has('importer-id')) {
            $importId = $request->input('importer-id');

            $import = Import::create([
                'importer_id' => $importId
            ]);

            ProcessImport::dispatch($import);

            $request->session()->flash('status', 'Import started');
        }

        return redirect()->route('importers');
    }

    public function imports()
    {
        $imports = Import::paginate(50);

        return view('imports', ['imports' => $imports]);
    }

    public function sourceDatasetIdentifiers()
    {
        $identifiers = SourceDatasetIdentifier::paginate(50);

        return view('source-dataset-identifiers', ['identifiers' => $identifiers]);
    }

    public function sourceDatasets()
    {
        $sourceDatasets = SourceDataset::paginate(50);

        return view('source-datasets', ['sourceDatasets' => $sourceDatasets]);
    }

    public function sourceDataset($id)
    {
        $sourceDatasetid = (int)$id;

        $sourceDataset = SourceDataset::where('id', $sourceDatasetid)->first();

        if($sourceDataset) {
            return view('source-dataset', ['sourceDataset' => $sourceDataset]);
        }

        abort(404, 'SourceDataset not found');
    }

    public function createActions()
    {
        $createActions = DatasetCreate::paginate(50);

        return view('creates', ['createActions' => $createActions]);
    }

    public function createAction($id)
    {
        $createActionId = (int)$id;

        $datasetCreate = DatasetCreate::where('id', $createActionId)->first();

        if($datasetCreate) {
            return view('create', ['datasetCreate' => $datasetCreate]);
        }

        abort(404, 'DatasetCreate not found');
    }

    public function test()
    {
        dd('test');
    }

}
