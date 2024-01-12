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
use App\Models\Seed;
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
use App\Models\Seeder;
use App\Jobs\ProcessSeed;

class SeederController extends Controller
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
        $seeders = Seeder::all();

        return view('seeders', ['seeders' => $seeders]);
    }

    public function seederSeeds($id)
    {
        $seeder = Seeder::where('id', $id)->first();

        if($seeder) {
            $seeds = $seeder->seeds;
            return view('seeder-seeds', ['seeder' => $seeder, 'seeds' => $seeds]);
        }
        abort(404, 'Invalid data requested');
    }

    public function createseed(Request $request)
    {
        if($request->has('seeder-id')) {
            $seederId = $request->input('seeder-id');

            $seed = Seed::create([
                'seeder_id' => $seederId
            ]);

            ProcessSeed::dispatch($seed);

            //ProcessImport::dispatch($import);

            $request->session()->flash('status', 'Seeder started');
        }

        return redirect()->route('seeders');
    }

    public function seeds($id)
    {
        $seed = Seed::where('id', $id)->first();
        if($seed) {
            $creates = $seed->creates;
            return view('seeds', ['seed' => $seed, 'creates' => $creates]);
        }

        abort(404, 'Invalid data requested');
    }

}
