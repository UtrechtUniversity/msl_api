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

        //dd($searchResults);

        return view('public.labs-list', [
            'facets' => $searchResults->getCollection()->searchFacets,
            'totalResultsCount' => $searchResults->total(),
            'laboratories' => $searchResults->items(),
            'paginator' => $searchResults,
            'activeFilters' => $service->getActiveFilters($request),
            'activeFiltersFrontend' => $service->getActiveFiltersFrontend($request),
            'queryParams' => $request->query(),
        ]);



        $resultsPerPage = 20;

        $client = new Client;
        $SearchRequest = new PackageSearchRequest;
        $SearchRequest->addFilterQuery('type', 'lab');
        $SearchRequest->rows = $resultsPerPage;
        $SearchRequest->loadFacetsFromConfig('laboratories');

        $page = $request->page ?? 1;
        $SearchRequest->start = ($page - 1) * $resultsPerPage;

        $query = $query = '';
        if ($request->query('query')) {
            if (count($request->query('query')) > 0) {
                $query = implode(' ', $request->query('query'));
            }
        }
        $SearchRequest->query = $query;

        $SearchRequest->sortField = 'title_string asc';

        // used by js filtertrees
        $activeFilters = [];

        // used to generate active filters in the template
        $activeFiltersFrontend = [];

        foreach ($request->query() as $key => $values) {
            if (array_key_exists($key, config('ckan.facets.laboratories')) || $key === 'query') {
                foreach ($values as $value) {
                    $activeFilters[$key][] = $value;

                    // Attach labels to the filters based upon the type
                    if ($value === 'true') {
                        $label = config('ckan.facets.laboratories')[$key];
                    } elseif (str_starts_with($value, 'https://epos-msl.uu.nl/voc/')) {
                        $keyword = Keyword::where('uri', $value)->first();
                        if ($keyword) {
                            $label = $keyword->label;
                        } else {
                            $label = '';
                        }
                    } elseif ($key === 'query') {
                        $label = 'Search: '.$value;
                    } else {
                        $label = $value;
                    }

                    // Add links without the filter
                    $query = request()->query();

                    // Loop over query parameters and remove current active filter for remove link
                    foreach ($query as $param => $paramValues) {
                        if ($param == $key) {
                            if (count($query[$param]) > 1) {
                                foreach ($paramValues as $paramKey => $paramValue) {
                                    if ($paramValue == $value) {
                                        $query[$param][$paramKey] = null;
                                    }
                                }
                            } else {
                                unset($query[$param]);
                            }
                        }
                    }

                    $removeUrl = $query ? url()->current().'?'.http_build_query($query) : url()->current();

                    $activeFiltersFrontend[] = [
                        'value' => $value,
                        'label' => $label,
                        'removeUrl' => $removeUrl,
                    ];

                    if ($key !== 'query') {
                        $SearchRequest->addFilterQuery($key, $value);
                    }
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach ($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        $paginator = $this->getPaginator($request, [], $result->getTotalResultsCount(), $resultsPerPage);

        return view('public.labs-list', [
            'facets' => $result->getFacets(),
            'totalResultsCount' => $result->getTotalResultsCount(),
            'laboratories' => $result->getResults(),
            'paginator' => $paginator,
            'activeFilters' => $activeFilters,
            'activeFiltersFrontend' => $activeFiltersFrontend,
            'queryParams' => $request->query()
        ]);
    }

    /**
     * Show the lab map page
     */
    public function map(Request $request)
    {
        $client = new Client;
        $SearchRequest = new PackageSearchRequest;
        $SearchRequest->addFilterQuery('type', 'lab');
        $SearchRequest->addFilterQuery('msl_has_spatial_data', 'true');
        $SearchRequest->loadFacetsFromConfig('laboratories');
        $SearchRequest->rows = 200;

        $activeFilters = [];

        foreach ($request->query() as $key => $values) {
            if (array_key_exists($key, config('ckan.facets.laboratories'))) {
                foreach ($values as $value) {
                    $activeFilters[$key][] = $value;
                    $SearchRequest->addFilterQuery($key, $value);
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach ($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        return view('public.labs-map', [
            'facets' => $result->getFacets(),
            'locations' => $locations,
            'result' => $result,
            'activeFilters' => $activeFilters
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

    /**
     * Get a paginator object
     */
    private function getPaginator(Request $request, array $items, int $total, int $resultsPerPage): LengthAwarePaginator
    {
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $resultsPerPage;
        $items = array_slice($items, $offset, $resultsPerPage);

        return new LengthAwarePaginator($items, $total, $resultsPerPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);
    }
}
