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

class LaboratoryController extends Controller
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
}
