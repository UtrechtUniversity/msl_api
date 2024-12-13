<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DatasetDelete;
use App\Jobs\ProcessDatasetDelete;
use App\Models\Importer;
use App\Models\Import;
use App\Jobs\ProcessImport;
use App\Models\SourceDatasetIdentifier;
use App\Models\SourceDataset;
use App\Models\DatasetCreate;
use App\CkanClient\Client;
use App\CkanClient\Request\OrganizationListRequest;
use App\CkanClient\Request\PackageSearchRequest;
use App\Models\MappingLog;
use App\Exports\MappingLogsExport;
use App\Mappers\BgsMapper;

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
        
        
        return view('admin.home');
    }
    
    public function removeDataset()
    {
        $client = new Client();
        $organizationListRequest = new OrganizationListRequest();

        $result = $client->get($organizationListRequest);
        $organizations = $result->getResult();
                
        return view('admin.remove-dataset', ['organizations' => $organizations]);
    }
    
    public function removeDatasetConfirm(Request $request)
    {
        $results = [];
        
        if($request->has('datasetId')) {
            $datasetId = $request->query('datasetId');
            if($datasetId) {
                $client = new Client();
                $searchRequest = new PackageSearchRequest();
                $searchRequest->query = 'name:' . $datasetId;
                $searchRequest->rows = 1000;

                $result = $client->get($searchRequest);
                $results = $result->getResults();
                                
                return view('admin.remove-dataset-confirm', ['results' => $results]);
            } else {
                
            }
        } elseif ($request->has('datasetSource')) {
            $datasetSource = $request->query('datasetSource');
            
            if($datasetSource) {                
                $client = new Client();
                $searchRequest = new PackageSearchRequest();
                $searchRequest->query = 'owner_org:' . $datasetSource;
                $searchRequest->rows = 1000;

                $result = $client->get($searchRequest);
                $results = $result->getResults();
                
                return view('admin.remove-dataset-confirm', ['results' => $results]);
            }
        } else {
            
        }
        
        return view('admin.remove-dataset-confirm', ['results' => $results]);
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
                
        return view('admin.queues', ['deletes' => $deletes]);
    }
    
    public function deleteActions()
    {
        $deletes = DatasetDelete::paginate(50);
        
        return view('admin.deletes', ['deletes' => $deletes]);
    }
    
    public function importers()
    {
        $importers = Importer::all();
        
        return view('admin.importers', ['importers' => $importers]);
    }
    
    public function importerImports($id)
    {               
        $imports = Import::where('importer_id', (int)$id)->get();
        $importer = Importer::where('id', $id)->first();
     
        return view('admin.importer-imports', ['imports' => $imports, 'importer' => $importer]);        
    }
    
    public function importerImportsFlow($id, $importId)
    {
        $sourceDatasetidentifiers = SourceDatasetIdentifier::where('import_id', $importId)->paginate(50);
        
        return view('admin.importer-import-flow', ['sourceDatasetIdentifiers' => $sourceDatasetidentifiers, 'importer_id' => $id, 'import_id' => $importId]);
    }
    
    public function importerImportsLog($id, $importId)
    {
        $logs = MappingLog::where('import_id', $importId)->paginate(50);
        
        return view('admin.importer-import-log', ['logs' => $logs, 'importer_id' => $id, 'import_id' => $importId]);
    }
    
    public function exportImportLog($id, $importId)
    {
        return Excel::download(new MappingLogsExport($importId), 'log.xlsx');
    }
    
    public function importerImportsDetail($importerid, $importId, $sourceDatasetIdentifierId)
    {
        $sourceDatasetIdentifier = SourceDatasetIdentifier::where('id', $sourceDatasetIdentifierId)->first();
        
        if($sourceDatasetIdentifier) {
            return view('admin.importer-import-detail', ['sourceDatasetIdentifier' => $sourceDatasetIdentifier, 'importer_id' => $importerid, 'import_id' => $importId]);
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
        
        return view('admin.imports', ['imports' => $imports]);
    }
    
    public function sourceDatasetIdentifiers()
    {
        $identifiers = SourceDatasetIdentifier::paginate(50);
        
        return view('admin.source-dataset-identifiers', ['identifiers' => $identifiers]);
    }
    
    public function sourceDatasets()
    {
        $sourceDatasets = SourceDataset::paginate(50);
        
        return view('admin.source-datasets', ['sourceDatasets' => $sourceDatasets]);
    }
    
    public function sourceDataset($id)
    {
        $sourceDatasetid = (int)$id;
        
        $sourceDataset = SourceDataset::where('id', $sourceDatasetid)->first();
        
        if($sourceDataset) {
            return view('admin.source-dataset', ['sourceDataset' => $sourceDataset]);
        }
        
        abort(404, 'SourceDataset not found');
    }
    
    public function createActions()
    {
        $createActions = DatasetCreate::paginate(50);
        
        return view('admin.creates', ['createActions' => $createActions]);
    }
    
    public function createAction($id)
    {
        $createActionId = (int)$id;
        
        $datasetCreate = DatasetCreate::where('id', $createActionId)->first();
        
        if($datasetCreate) {
            return view('admin.create', ['datasetCreate' => $datasetCreate]);
        }
        
        abort(404, 'DatasetCreate not found');
    }
        
    public function test()
    {
        dd('test');
        
        $sourceDataset = SourceDataset::where('id', 6361)->first();        
        
        $mapper = new BgsMapper();
        dd($mapper->map($sourceDataset));
        
        dd($sourceDataset);
        
        dd('test');        
    }
    
}
