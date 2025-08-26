<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\CkanClient\Client;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use App\Models\Surveys\Survey;
use App\CkanClient\Request\PackageShowRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use App\CkanClient\Request\PackageSearchRequest;
use App\CkanClient\Request\OrganizationListRequest;

class FrontendController extends Controller
{


    /**
     * Show the Index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "data-publication");
        $datasetCount = $client->get($SearchRequest)->getTotalResultsCount();

        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "lab");
        $labCount = $client->get($SearchRequest)->getTotalResultsCount();

        $request = new OrganizationListRequest();
        $reposArray = $client->get($request)->getResult();
        $reposCount = 0;
        foreach($reposArray as $entry){
            if($entry['hide'] == "false"){
                $reposCount++;
            }
        }


        return view('frontend.index', ['datasetsCount' => $datasetCount, 'labCount' => $labCount, 'reposCount' => $reposCount]);
    }

    /**
     * Show the data-publications/data-access page
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataPublications(Request $request)
    {
        $resultsPerPage = 10;

        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "data-publication");
        $SearchRequest->rows = $resultsPerPage;
        
        $page = $request->page ?? 1;
        $SearchRequest->start = ($page-1) * $resultsPerPage;

        $query = $query = "";
        if($request->query('query')) {
            if(count($request->query('query')) > 0) {
                $query = implode(" ", $request->query('query'));
            }
        }
        $SearchRequest->query = $query;
        
        $sort = $request->query('sort') ?? "";
        $SearchRequest->sortField = $sort;

        $SearchRequest->loadFacetsFromConfig('data-publications');

        // used by js filtertrees
        $activeFilters = [];

        // used to generate active filters in the template
        $activeFiltersFrontend = [];

        foreach($request->query() as $key => $values) {
            if(array_key_exists($key, config('ckan.facets.data-publications')) || $key === "query") {
                foreach($values as $value) {
                    $activeFilters[$key][] = $value;

                    // Attach labels to the filters based upon the type
                    if($value === 'true') {
                        $label = config('ckan.facets.data-publications')[$key];
                    } elseif(str_starts_with($value, 'https://epos-msl.uu.nl/voc/')) {
                        $keyword = Keyword::where('uri', $value)->first();
                        if($keyword) {
                            $label = $keyword->label;
                        } else {
                            $label = '';
                        }
                    } elseif($key === "query") {
                        $label = "Search: " . $value;
                    } else {
                        $label = $value;
                    }

                    // Add links without the filter                    
                    $query = request()->query();
                    
                    // Loop over query parameters and remove current active filter for remove link
                    foreach($query as $param => $paramValues) {
                        if($param == $key) {
                            if(count($query[$param]) > 1) {
                                foreach($paramValues as $paramKey => $paramValue) {
                                    if($paramValue == $value) {
                                        $query[$param][$paramKey] = null;
                                    }
                                }
                            } else {
                                unset($query[$param]);
                            }
                        }
                    }                    

                    $removeUrl = $query ? url()->current() . '?' . http_build_query($query) : url()->current();

                    $activeFiltersFrontend[] = [
                        'value' => $value,
                        'label' => $label,
                        'removeUrl' => $removeUrl
                    ];

                    if($key !== "query") {
                        $SearchRequest->addFilterQuery($key, $value);
                    }
                }
            }
        }

        $result = $client->get($SearchRequest);

        // store current url for linking back to search results from detail pages
        $request->session()->put('data_publication_active_search', $request->fullUrl());        

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        $paginator = $this->getPaginator($request, [], $result->getTotalResultsCount(), $resultsPerPage);

        return view('frontend.data-access', ['result' => $result, 'paginator' => $paginator, 'activeFilters' => $activeFilters, 'activeFiltersFrontend' => $activeFiltersFrontend, 'sort' => $sort, 'queryParams' => $request->query()]);
    }
        
    /**
     * Show the lab map page
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function labsMap(Request $request)
    {
        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "lab");
        $SearchRequest->addFilterQuery("msl_has_spatial_data", "true");
        $SearchRequest->loadFacetsFromConfig('laboratories');
        $SearchRequest->rows = 200;

        $activeFilters = [];

        foreach($request->query() as $key => $values) {
            if(array_key_exists($key, config('ckan.facets.laboratories'))) {
                foreach($values as $value) {
                    $activeFilters[$key][] = $value;
                    $SearchRequest->addFilterQuery($key, $value);
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        return view('frontend.labs-map', ['locations' => $locations, 'result' => $result, 'activeFilters' => $activeFilters]);
    }

    /**
     * Show the lab list page
     * 
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function labsList(Request $request)
    {
        $resultsPerPage = 20;

        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "lab");
        $SearchRequest->rows = $resultsPerPage;
        $SearchRequest->loadFacetsFromConfig('laboratories');

        $page = $request->page ?? 1;
        $SearchRequest->start = ($page-1) * $resultsPerPage;

        $query = $query = "";
        if($request->query('query')) {
            if(count($request->query('query')) > 0) {
                $query = implode(" ", $request->query('query'));
            }
        }
        $SearchRequest->query = $query;

        $SearchRequest->sortField = 'title_string asc';

        // used by js filtertrees
        $activeFilters = [];

        // used to generate active filters in the template
        $activeFiltersFrontend = [];

        foreach($request->query() as $key => $values) {
            if(array_key_exists($key, config('ckan.facets.laboratories')) || $key === "query") {
                foreach($values as $value) {
                    $activeFilters[$key][] = $value;

                    // Attach labels to the filters based upon the type
                    if($value === 'true') {
                        $label = config('ckan.facets.laboratories')[$key];
                    } elseif(str_starts_with($value, 'https://epos-msl.uu.nl/voc/')) {
                        $keyword = Keyword::where('uri', $value)->first();
                        if($keyword) {
                            $label = $keyword->label;
                        } else {
                            $label = '';
                        }
                    } elseif($key === "query") {
                        $label = "Search: " . $value;
                    } else {
                        $label = $value;
                    }

                    // Add links without the filter                    
                    $query = request()->query();
                    
                    // Loop over query parameters and remove current active filter for remove link
                    foreach($query as $param => $paramValues) {
                        if($param == $key) {
                            if(count($query[$param]) > 1) {
                                foreach($paramValues as $paramKey => $paramValue) {
                                    if($paramValue == $value) {
                                        $query[$param][$paramKey] = null;
                                    }
                                }
                            } else {
                                unset($query[$param]);
                            }
                        }
                    }                    

                    $removeUrl = $query ? url()->current() . '?' . http_build_query($query) : url()->current();

                    $activeFiltersFrontend[] = [
                        'value' => $value,
                        'label' => $label,
                        'removeUrl' => $removeUrl
                    ];

                    if($key !== "query") {
                        $SearchRequest->addFilterQuery($key, $value);
                    }
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        $paginator = $this->getPaginator($request, [], $result->getTotalResultsCount(), $resultsPerPage);

        return view('frontend.labs-list', ['result' => $result, 'paginator' => $paginator, 'activeFilters' => $activeFilters, 'activeFiltersFrontend' => $activeFiltersFrontend, 'queryParams' => $request->query()]);
    }

    /**
     * Show the lab detail page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function lab($id)
    {
        $client = new Client();
        $request = new PackageShowRequest();
        $request->id = $id;

        $result = $client->get($request);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        $labData = $result->getResult();
        
        /**
         * All labs should have a contact person defined with an email address however this is 
         * depending on harvested data from FAST so we should check if this is the case. Only 
         * display the contact button when a validated e-mail address is set in view.
         */

        $labHasMailContact = false;
        $labDatabase = Laboratory::where('fast_id', (int)$labData['msl_fast_id'])->first();

        if($labDatabase) {
            $contactPersons = $labDatabase->laboratoryContactPersons;
            if($contactPersons->count() > 0) {
                $labHasMailContact = $contactPersons->first()->hasValidEmail();                
            }
        }

        return view('frontend.lab-detail', ['data' => $labData, 'labHasMailContact' => $labHasMailContact]);
    }

    /**
     * Show the lab equipment detail page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function labEquipment($id)
    {
        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "equipment");
        $SearchRequest->addFilterQuery("msl_lab_ckan_name", $id);
        $SearchRequest->rows = 100;

        $result = $client->get($SearchRequest);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        // group results for display purposes
        $groupedResults = [];
        foreach($result->getResults() as $result) {
            $groupedResults[$result['msl_domain_name']][] = $result;            
        }

        // get the name of lab
        $Labrequest = new PackageShowRequest();
        $Labrequest->id = $id;

        $Labresult = $client->get($Labrequest);

        if(!$Labresult->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        return view('frontend.lab-detail-equipment', ['data' => $groupedResults, 'ckanLabName' => $id, 'data2' => $Labresult->getResult()]);
    }

    /**
     * Show the equipment map page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function equipmentMap(Request $request)
    {
        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("msl_has_spatial_data", "true");
        $SearchRequest->addFilterQuery("type", "equipment");
        $SearchRequest->loadFacetsFromConfig('equipment');
        $SearchRequest->rows = 1000;

        $activeFilters = [];

        foreach($request->query() as $key => $values) {
            if(array_key_exists($key, config('ckan.facets.equipment'))) {
                foreach($values as $value) {
                    $activeFilters[$key][] = $value;
                    $SearchRequest->addFilterQuery($key, $value);
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        return view('frontend.equipment-map', ['locations' => $locations, 'result' => $result, 'activeFilters' => $activeFilters]);
    }

    /**
     * Show the equipment list page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function equipmentList(Request $request)
    {
        $resultsPerPage = 20;

        $client = new Client();
        $SearchRequest = new PackageSearchRequest();
        $SearchRequest->addFilterQuery("type", "equipment");
        $SearchRequest->rows = $resultsPerPage;
        $SearchRequest->loadFacetsFromConfig('equipment');

        $page = $request->page ?? 1;
        $SearchRequest->start = ($page-1) * $resultsPerPage;

        $query = $query = "";
        if($request->query('query')) {
            if(count($request->query('query')) > 0) {
                $query = implode(" ", $request->query('query'));
            }
        }
        $SearchRequest->query = $query;

        $SearchRequest->sortField = 'title_string asc';

        // used by js filtertrees
        $activeFilters = [];

        // used to generate active filters in the template
        $activeFiltersFrontend = [];

        foreach($request->query() as $key => $values) {
            if(array_key_exists($key, config('ckan.facets.equipment')) || $key === "query") {
                foreach($values as $value) {
                    $activeFilters[$key][] = $value;

                    // Attach labels to the filters based upon the type
                    if($value === 'true') {
                        $label = config('ckan.facets.equipment')[$key];
                    } elseif(str_starts_with($value, 'https://epos-msl.uu.nl/voc/')) {
                        $keyword = Keyword::where('uri', $value)->first();
                        if($keyword) {
                            $label = $keyword->label;
                        } else {
                            $label = '';
                        }
                    } elseif($key === "query") {
                        $label = "Search: " . $value;
                    } else {
                        $label = $value;
                    }

                    // Add links without the filter                    
                    $query = request()->query();
                    
                    // Loop over query parameters and remove current active filter for remove link
                    foreach($query as $param => $paramValues) {
                        if($param == $key) {
                            if(count($query[$param]) > 1) {
                                foreach($paramValues as $paramKey => $paramValue) {
                                    if($paramValue == $value) {
                                        $query[$param][$paramKey] = null;
                                    }
                                }
                            } else {
                                unset($query[$param]);
                            }
                        }
                    }                    

                    $removeUrl = $query ? url()->current() . '?' . http_build_query($query) : url()->current();

                    $activeFiltersFrontend[] = [
                        'value' => $value,
                        'label' => $label,
                        'removeUrl' => $removeUrl
                    ];

                    if($key !== "query") {
                        $SearchRequest->addFilterQuery($key, $value);
                    }
                }
            }
        }

        $result = $client->get($SearchRequest);

        $locations = [];
        foreach($result->getResults() as $labData) {
            $locations[] = json_decode($labData['msl_location']);
        }

        $paginator = $this->getPaginator($request, [], $result->getTotalResultsCount(), $resultsPerPage);

        $result = $client->get($SearchRequest);

        return view('frontend.equipment-list', ['result' => $result, 'paginator' => $paginator, 'activeFilters' => $activeFilters, 'activeFiltersFrontend' => $activeFiltersFrontend, 'queryParams' => $request->query()]);
    }


    /**
     * Show the data-repositories page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataRepositories()
    {
        $client = new Client();
        $request = new OrganizationListRequest();

        $request->sortField = "name asc";

        $result = $client->get($request);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }        

        return view('frontend.data-repositories', ['repositories' => $result->getResult()]);
    }

    /**
     * Show the contribute as researcher page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeResearcher()
    {
        return view('frontend.contribute-researcher');
    }

    /**
     * Show the contribute as repository page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeRepository()
    {
        return view('frontend.contribute-repository');
    }

    /**
     * Show the contribute as laboratory page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeLaboratory()
    {
        return view('frontend.contribute-laboratory');
    }

    /**
     * Show the contribute select scenario page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeSelectScenario()
    {
        $allDomainNames = [
            'analogue' => 'Analogue Modelling of Geological Processes',
            'geochemistry' => 'Geochemistry',
            'microtomo' => 'Microscopy and Tomography',
            'paleomag' => 'Magnetism and Paleomagnetism',
            'rockmelt' => 'Rock and Melt Physics',
            'testbeds' => 'Geo-Energy Test Beds'
        ];

        $allDomains = [];

        foreach ($allDomainNames as $key => $value) {
            $survey = Survey::where('name', 'scenarioSurvey-'.$key)->first();
            if($survey->active){
                $allDomains [$survey->id] = $value;
            }
        }


        return view('frontend.contribute-select-scenario', 
        ['allDomains' => $allDomains]);
    }


    /**
     * Show the contribute as laboratory page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contactUs()
    {
        return view('frontend.contact-us');
    }

    /**
     * Show the about page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function about()
    {
        return view('frontend.about');
    }

    /**
     * Show the data-publication page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataPublication($id)
    {
        $client = new Client();
        $request = new PackageShowRequest();
        $request->id = $id;

        $result = $client->get($request);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        return view('frontend.data-publication-detail', ['data' => $result->getResult()]);
    }

    /**
     * Show the data-publication-files page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dataPublicationFiles($id)
    {
        $client = new Client();
        $request = new PackageShowRequest();
        $request->id = $id;

        $result = $client->get($request);

        if(!$result->isSuccess()) {
            abort(404, 'ckan request failed');
        }

        return view('frontend.data-publication-detail-files', ['data' => $result->getResult()]);
    }

    /**
     * Show the keyword selector page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function keywordSelector()
    {
        return view('frontend.keyword-selector');
    }

    /**
     * Create CSV file based on keyword selector input
     * 
     * @return voic
     */
    public function keywordExport(Request $request)
    {
        if ($request->has(['sampleKeywordsText', 'sampleKeywordsUri', 'sampleKeywordsVocabUri'])) {        
            $texts = $request->input('sampleKeywordsText');;
            $uris = $request->input('sampleKeywordsUri');
            $vocabUris = $request->input('sampleKeywordsVocabUri');
            $lines = [];
            
            
            if(count($texts) === count($uris) && count($texts) === count($vocabUris)) {
                for ($x = 0; $x < count($uris); $x++) {
                    $lines[] = [
                        'text' => $texts[$x],
                        'uri' => $uris[$x],
                        'vocabUri' => $vocabUris[$x]
                    ];
                }
            }

            $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=keywords.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
            ];

            array_unshift($lines, array_keys($lines[0]));

            $callback = function() use ($lines) 
            {
                $FH = fopen('php://output', 'w');
                foreach ($lines as $line) { 
                    fputcsv($FH, $line);
                }
                fclose($FH);
            };

            return response()->stream($callback, 200, $headers);
        }
        return back();
    }

    /**
     * Get a paginator object
     * 
     * @return LengthAwarePaginator
     */
    private function getPaginator(Request $request, array $items, int $total, int $resultsPerPage): LengthAwarePaginator
    {
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $resultsPerPage;
        $items = array_slice($items, $offset, $resultsPerPage);

        return new LengthAwarePaginator($items, $total, $resultsPerPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    }

    /**
     * Show theme test page
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function themeTest()
    {
        return view('frontend.themeTest');
    }
    
}

