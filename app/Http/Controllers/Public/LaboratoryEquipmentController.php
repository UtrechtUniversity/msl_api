<?php

namespace App\Http\Controllers\Public;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\PackageSearchRequest;
use App\Clients\CkanClient\Request\PackageShowRequest;
use App\Http\Controllers\Controller;
use App\Models\Keyword;
use App\Models\Laboratory\Laboratory;
use App\Services\LaboratoryEquipmentService;
use App\Services\LaboratoryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class LaboratoryEquipmentController extends Controller
{
    /**
     * Show the equipment list page
     */
    public function list(Request $request, LaboratoryEquipmentService $service)
    {
        $searchResults = $service->search($request);

        return view('public.equipment-list', [
            'facets' => $searchResults->getCollection()->searchFacets,
            'totalResultsCount' => $searchResults->total(),
            'result' => $searchResults->items(),
            'paginator' => $searchResults,
            'activeFilters' => $service->getActiveFilters($request),
            'activeFiltersFrontend' => $service->getActiveFiltersFrontend($request),
            'queryParams' => $request->query(),
        ]);
    }

    /**
     * Show the equipment map page
     */
    public function map(Request $request, LaboratoryEquipmentService $service)
    {
        $results = $service->getStaticMapData($request);

        $locations = [];
        foreach ($results as $equipment) {
            $locations[] = json_decode($equipment->getGeoJsonFeature());
        }

        return view('public.equipment-map', [
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

        return view('public.lab-detail-equipment', [
            'laboratory' => $laboratory,
            'equipment' => $laboratory->laboratoryEquipment
        ]);
    }
}
