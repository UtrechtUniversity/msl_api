<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataAccessController extends Controller
{
    public function index(Request $request): View
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

        return view('frontend.dp-map', ['locations' => $locations, 'result' => $result, 'activeFilters' => $activeFilters]);
    }
}
