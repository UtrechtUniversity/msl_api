<?php

namespace App\Http\Controllers;

use App\Exports\epos\RegistryExport;
use App\Exports\LabExport;
use App\Jobs\ProcessFastVocabularyUpdate;
use App\Jobs\ProcessLaboratoryKeywordUpdateGroup;
use App\Jobs\ProcessLaboratoryOrganizationUpdateGroupRor;
use App\Jobs\ProcessLaboratoryUpdateGroupFast;
use App\Models\Laboratory;
use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryOrganizationUpdateGroupRor;
use App\Models\LaboratoryUpdateGroupFast;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LabController extends Controller
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

    public function importLabData()
    {
        return view('admin.import-labdata');
    }

    public function viewLabData()
    {
        $laboratories = Laboratory::get();

        return view('admin.laboratories', ['laboratories' => $laboratories]);
    }

    public function downloadLabData()
    {
        return Excel::download(new LabExport, 'labs.xlsx');
    }

    public function updateFastData(Request $request)
    {
        // Dispatch job to update fast vocabulary
        ProcessFastVocabularyUpdate::dispatch();

        $laboratoryUpdateGroup = LaboratoryUpdateGroupFast::create();
        ProcessLaboratoryUpdateGroupFast::dispatch($laboratoryUpdateGroup);

        $request->session()->flash('status', 'Updating using Fast started');

        return redirect()->route('importers');
    }

    public function updateLaboratoryOrganizationsByROR(Request $request)
    {
        $laboratoryOrganizationUpdateGroupRor = LaboratoryOrganizationUpdateGroupRor::create();
        ProcessLaboratoryOrganizationUpdateGroupRor::dispatch($laboratoryOrganizationUpdateGroupRor);

        $request->session()->flash('status', 'Updating organizations using ROR api');

        return redirect()->route('importers');
    }

    public function updateLaboratoryKeywords(Request $request)
    {
        ProcessLaboratoryKeywordUpdateGroup::dispatch();

        $request->session()->flash('status', 'Updating laboratory keywords using vocabularies');

        return redirect()->route('importers');
    }

    public function registryTurtle(Request $request)
    {
        $organizations = LaboratoryOrganization::where('ror_country_code', '<>', '')->get();
        $exporter = new RegistryExport($organizations);

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->export();
        }, 'msl-labs.ttl');
    }
}
