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

        echo "Importers";

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
