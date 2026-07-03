<?php

namespace App\Http\Controllers\Public;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\PackageSearchRequest;
use App\Clients\CkanClient\Request\PackageShowRequest;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\Laboratory\Laboratory;
use App\Services\LaboratoryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LabController extends Controller
{
    /**
     * Show the lab list page
     */
    public function list(Request $request, LaboratoryService $service)
    {
        $searchResults = $service->search($request);

        return view('public.labs-list', [
            'facets' => $searchResults->getCollection()->searchFacets,
            'totalResultsCount' => $searchResults->total(),
            'laboratories' => $searchResults->items(),
            'paginator' => $searchResults,
            'activeFilters' => $service->getActiveFilters($request),
            'activeFiltersFrontend' => $service->getActiveFiltersFrontend($request),
            'queryParams' => $request->query(),
        ]);
    }

    /**
     * Show the lab map page
     */
    public function map(Request $request, LaboratoryService $service)
    {
        $results = $service->getStaticMapData($request);

        $locations = [];
        foreach ($results as $laboratory) {
            $locations[] = json_decode($laboratory->getGeoJsonFeature());
        }

        return view('public.labs-map', [
            'facets' => $results->searchFacets,
            'locations' => $locations,
            'result' => $results,
            'activeFilters' => $service->getActiveFilters($request)
        ]);
    }

    /**
     * Show the lab detail page
     */
    public function detail($id)
    {
        $laboratory = Laboratory::where('ckan_id', $id)->firstOrFail();

        $labHasMailContact = false;

        $contactPersons = $laboratory->laboratoryContactPersons;
        if ($contactPersons->count() > 0) {
            $labHasMailContact = $contactPersons->first()->hasValidEmail();
        }

        return view('public.lab-detail', [
            'laboratory' => $laboratory,
            'labHasMailContact' => $labHasMailContact
        ]);
    }

    /**
     * Show the lab equipment detail page
     */
    public function detailEquipment($id)
    {
        // @todo first the equipment page has to be redone to use correct id linking

        $laboratory = Laboratory::where('ckan_id', $id)->firstOrFail();

        return view('public.lab-detail-equipment', [
            'laboratory' => $laboratory,
            'ckanLabName' => '???',
            'equipment' => $laboratory->equipment
        ]);

        $client = new Client;
        $SearchRequest = new PackageSearchRequest;
        $SearchRequest->addFilterQuery('type', 'equipment');
        $SearchRequest->addFilterQuery('msl_lab_ckan_name', $id);
        $SearchRequest->rows = 100;

        $result = $client->get($SearchRequest);

        if (! $result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        $equipment = $result->getResults(true);

        // get the name of lab
        $Labrequest = new PackageShowRequest;
        $Labrequest->id = $id;

        $Labresult = $client->get($Labrequest);

        if (! $Labresult->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        return view('public.lab-detail-equipment', [
            'laboratory' => $Labresult->getResult(true),
            'ckanLabName' => $id,
            'equipment' => $equipment
        ]);
    }
}
