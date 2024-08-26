<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Ckan\Request\PackageSearch;
use App\Ckan\Request\OrganizationList;
use App\Ckan\Response\OrganizationListResponse;
use App\Converters\MaterialsConverter;
use App\Converters\RockPhysicsConverter;
use App\Converters\ExcelToJsonConverter;
use App\Exports\FilterTreeExport;
use App\Converters\PorefluidsConverter;
use App\Converters\AnalogueModellingConverter;
use App\Converters\GeologicalAgeConverter;
use App\Converters\GeologicalSettingConverter;
use App\Converters\PaleomagnetismConverter;
use App\Converters\GeochemistryConverter;
use App\Exports\UnmatchedKeywordsExport;
use App\Exports\AbstractMatchingExport;
use App\Converters\MicroscopyConverter;
use App\Models\Vocabulary;
use App\Exports\UriLabelExport;
use App\Models\Keyword;

class ToolsController extends Controller
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
        
    public function convertKeywords()
    {        
        return view('convert-keywords');
    }
    
    public function processMaterialsFile(Request $request)
    {
        $request->validate([
            'materials-file' => 'required'
        ]);
                        
        if($request->hasFile('materials-file')) {
            $converter = new MaterialsConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('materials-file'));
            }, 'materials.json');
                        
        }
        
        return back()
            ->with('status','Error');
    }
    
    public function processPoreFluidsFile(Request $request)
    {
        $request->validate([
            'porefluids-file' => 'required'
        ]);
        
        if($request->hasFile('porefluids-file')) {
            $converter = new PorefluidsConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('porefluids-file'));
            }, 'porefluids.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    
    public function processRockPhysicsFile(Request $request)
    {        
        $request->validate([
            'rockphysics-file' => 'required'
        ]);
        
        if($request->hasFile('rockphysics-file')) {
            $converter = new RockPhysicsConverter();            
                        
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('rockphysics-file'));
            }, 'rockphysics.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processAnalogueModellingFile(Request $request)
    {
        $request->validate([
            'analogue-file' => 'required'
        ]);
        
        if($request->hasFile('analogue-file')) {
            $converter = new AnalogueModellingConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('analogue-file'));
            }, 'analogue.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processGeologicalAgeFile(Request $request)
    {
        $request->validate([
            'geological-age-file' => 'required'
        ]);
        
        if($request->hasFile('geological-age-file')) {
            $converter = new GeologicalAgeConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('geological-age-file'));
            }, 'geological-age.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processGeologicalSettingFile(Request $request)
    {
        $request->validate([
            'geological-setting-file' => 'required'
        ]);
        
        if($request->hasFile('geological-setting-file')) {
            $converter = new GeologicalSettingConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('geological-setting-file'));
            }, 'geological-setting.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processPaleomagnetismFile(Request $request)
    {
        $request->validate([
            'paleomagnetism-file' => 'required'
        ]);
        
        if($request->hasFile('paleomagnetism-file')) {
            $converter = new PaleomagnetismConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('paleomagnetism-file'));
            }, 'paleomagnetism.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processGeochemistryFile(Request $request)
    {
        $request->validate([
            'geochemistry-file' => 'required'
        ]);
        
        if($request->hasFile('geochemistry-file')) {
            $converter = new GeochemistryConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('geochemistry-file'));
            }, 'geochemistry.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function processMiscroscopyFile(Request $request)
    {
        $request->validate([
            'microscopy-file' => 'required'
        ]);
        
        if($request->hasFile('microscopy-file')) {
            $converter = new MicroscopyConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('microscopy-file'));
            }, 'microscopy.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function convertExcel()
    {
        return view('convert-excel');
    }
    
    public function processExcelToJson(Request $request)
    {
        $request->validate([
            'excel-file' => 'required'
        ]);
        
        if($request->hasFile('excel-file')) {
            $converter = new ExcelToJsonConverter();
            
            return response()->streamDownload(function () use($converter, $request) {
                echo $converter->ExcelToJson($request->file('excel-file'));
            }, 'converted.json');
                
        }
        
        return back()
        ->with('status','Error');
    }
    
    public function uriLabels()
    {
        return view('uri-labels');
    }
    
    public function uriLabelsDownload()
    {
        $exporter = new UriLabelExport();
        
        return response()->streamDownload(function () use($exporter) {
            echo $exporter->export();
        }, 'uri-labels.json');
    }
    
    public function filterTree()
    {
        return view('filter-tree');
    }            
    
    public function filterTreeDownload()
    {
        $exporter = new FilterTreeExport();
                
        return response()->streamDownload(function () use($exporter) {
            echo $exporter->exportInterpreted();
        }, 'interpreted.json');
    }
    
    public function filterTreeDownloadOriginal()
    {
        $exporter = new FilterTreeExport();
        
        return response()->streamDownload(function () use($exporter) {
            echo $exporter->exportOriginal();
        }, 'original.json');
    }
    
    public function viewUnmatchedKeywords()
    {
        $client = new \GuzzleHttp\Client();
        
        $searchRequest = new PackageSearch();
        $searchRequest->rows = 1000;
        $searchRequest->query = 'type: data-publication';
        
        try {
            $response = $client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
        } catch (\Exception $e) {
            
        }
        
        $content = json_decode($response->getBody(), true);
        $results = $content['result']['results'];                
                                
        $keywords = [];
        foreach ($results as $result) {
            if(count($result['tags']) > 0) {
                foreach ($result['tags'] as $tag) {
                    if(!array_key_exists($tag['name'], $keywords)) {
                        $keywords[$tag['name']] = 1;
                    } else {
                        $keywords[$tag['name']] = $keywords[$tag['name']] + 1;
                    }
                }
            }
        }
        
        uasort($keywords, function($a, $b) {
            return $b - $a;
        });
        
        return view('unmatched-keywords', ['keywords' => $keywords]);
    }
    
    public function downloadUnmatchedKeywords()
    {
        return Excel::download(new UnmatchedKeywordsExport(), 'unmatched-keywords.xlsx');
    }
    
    public function abstractMatching(Request $request)
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
        
        $filteredOrganizations = [];
        
        foreach ($organizations as $organization) {
            if($organization['name'] !== 'epos-multi-scale-laboratories-thematic-core-service')
            {
                $filteredOrganizations[] = $organization;
            }
        }
        
        $data = [];
        $selected = '';
        
        if ($request->has('datasetSource')) {
            $datasetSource = $request->query('datasetSource');
            $selected = $datasetSource;
            
            $searchRequest = new PackageSearch();
            $searchRequest->rows = 10;
            $searchRequest->query = 'type:data-publication';
            $searchRequest->filterQuery =  'owner_org:' . $datasetSource;            
            
            try {
                $response = $client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
            } catch (\Exception $e) {
                
            }
            
            $content = json_decode($response->getBody(), true);
            $results = $content['result']['results'];
            
            //dd($content);
            
            $keywordHelper = new KeywordHelper();
                    
            foreach ($results as $result) {
                $item = [];
                $item['identifier'] = $result['msl_doi'];
                $item['title'] = $result['title'];
                $item['abstract'] = $result['notes'];
                $item['keywords'] = [];
                $keywords = $keywordHelper->extractFromText($item['abstract'] . ' ' . $item['title']);
                
                foreach ($keywords as $keyword) {
                    $item['keywords'][] = $keyword->getFullPath('>', true);
                }
                
                $data[] = $item;
            }
        }
                                
        
        return view('abstract-matching', ['data' => $data, 'organizations' => $filteredOrganizations, 'selected' => $selected]);
    }
    
    public function abstractMatchingDownload($dataRepo) 
    {
        return Excel::download(new AbstractMatchingExport($dataRepo), 'abstract-matching.xlsx');
    }
    
    public function doiExport(Request $request)
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
        
        if ($request->has('organization')) {
            $OrganizationId = $request->query('organization');
            
            $client = new \GuzzleHttp\Client();
            
            $searchRequest = new PackageSearch();
            $searchRequest->query = 'owner_org:' . $OrganizationId;
            $searchRequest->rows = 200;
            try {
                $response = $client->request($searchRequest->method, $searchRequest->endPoint, $searchRequest->getAsQueryArray());
            } catch (\Exception $e) {
                
            }
            
            $content = json_decode($response->getBody(), true);
            $results = $content['result']['results'];
            
            $dois = [];
            
            foreach ($results as $result) {
                $dois[] = '"' . strtolower($result['msl_doi']) . '"';
            }
            
            dd(implode(', ', $dois));
        }
        
        return view('export-dois', ['organizations' => $organizations]);
    }
    
    public function queryGenerator()
    {        
        $terms = [];        
        
        /*
         //* Produce and display query for testing with datacite
         * Results of query should contain one word from two large groups:
         * GROUP #1
         *  - Materials
         *  - Geological setting
         * GROUP #2
         *  - Analogue modelling -> Apparatus
         *  - Analogue modelling -> Measured property
         *  - Geochemistry -> Technique
         *  - Microscopy -> Apparatus
         *  - Microscopy -> Technique
         *  - Microscopy -> Analyzed feature
         *  - Microscopy -> Inferred behavior
         *  - Paleomagnetism -> Apparatus
         *  - Paleomagnetism -> Measured property
         *  - Paleomagnetism -> Inferred behavior
         *  - Rock physics -> Apparatus
         *  - Rock physics -> Measured property
         *  - Rock physics -> Inferred deformation behavior
         */
        
        //Keyword identifiers to be ignored while gathering terms (based on vocab 1.2)
        $skipKeywords = [250, 251, 252, 253, 2321, 254, 2343, 255, 2353, 256, 2382, 257, 3396, 260, 2399, 261, 2403, 262, 263, 264, 2457, 266, 2555, 267, 2588, 268, 269, 2679, 270, 271, 272, 2704, 273, 2718, 274, 2749, 275, 2750, 276, 2830, 864, 1522, 895, 1542, 896, 1543, 897, 1544, 898, 1545, 899, 1546, 900, 1547, 918, 919, 920, 921, 922, 563, 564, 489, 565, 566, 567, 905, 1552, 932, 1572, 556, 24, 658, 915, 1553, 916, 1554, 917, 1555, 3064, 933, 1573, 3065, 362, 81];
        $skipSearchKeywords = [2653, 1447, 606, 822, 2863];
        
        
        $materialVocab = Vocabulary::where('name', 'materials')->where('version', '1.2')->first();
        $materialTerms = $materialVocab->search_keywords;
        $terms = array();
        $query = "";
        $total = 0;
                        
        foreach ($materialTerms as $materialTerm) {
            if(in_array($materialTerm->keyword_id, $skipKeywords)) {
                continue;
            }
            if(in_array($materialTerm->id, $skipSearchKeywords)) {
                continue;
            }
            
            $terms[] = $this->createKeywordSearchRegex($materialTerm->search_value);
        }
                
        $geologicalSettingsVocab = Vocabulary::where('name', 'geologicalsetting')->where('version', '1.2')->first();
        $geologicalSettingsTerms = $geologicalSettingsVocab->search_keywords;
               
        
        foreach ($geologicalSettingsTerms as $geologicalSettingsTerm) {
            if(in_array($geologicalSettingsTerm->keyword_id, $skipKeywords)) {
                continue;
            }
            if(in_array($geologicalSettingsTerm->id, $skipSearchKeywords)) {
                continue;
            }
            
            if(str_starts_with($geologicalSettingsTerm->keyword->uri, 'https://epos-msl.uu.nl/voc/geologicalsetting/1.2/antropogenic_setting-civil_engineered_setting')) {
                continue;
            }
            
            if(str_starts_with($geologicalSettingsTerm->keyword->uri, 'https://epos-msl.uu.nl/voc/geologicalsetting/1.2/surface_morphological_setting')) {
                continue;
            }
                                    
            $terms[] = $this->createKeywordSearchRegex($geologicalSettingsTerm->search_value);
        }
        
        
        $query .= implode(',', array_unique($terms));
        //dd($query);
        
        
        $total += count($terms);
        
        $terms = [];
        $query = "";
                
        //analogue modeling apparatus        
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/analoguemodelling/1.2/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //analogue modeling measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/analoguemodelling/1.2/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        
        //geochemistry technique
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/geochemistry/1.2/technique-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
                
        //microscopy apparatus        
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.2/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //microscopy technique
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.2/technique-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //microscopy analyzed feature
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.2/analyzed_feature-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //microscopy inferred behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.2/inferred_parameter-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {                
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }         
        
        //paleomagnetism apparatus        
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.2/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //paleomagnetism measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.2/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //paleomagnetism inferred behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.2/inferred_behavior-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //rockphysics apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.2/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //rockphysics measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.2/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        
        //rockphysics inferred deformation behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.2/inferred_deformation_behavior-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if(in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if(in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }
                
                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }
        //dd(count($terms), count(array_unique($terms)));
        
        $terms = array_unique($terms);
        
        $query .= implode(',', $terms);
        dd($query);
                        
        return view('query-generator', ['query' => $query]);
    }
    
    private function createKeywordSearchRegex($searchValue) {
        $term = $searchValue;
        
        $term = str_replace('(', '\\\\(', $term);
        $term = str_replace(')', '\\\\)', $term);
        $term = str_replace('.', '\\\\.', $term);
        $term = str_replace('*', '\\\\*', $term);                        
        
        if(str_ends_with($searchValue, ',')) {
            return "\"\\\\b" . $term . "\"";;
        }
        return "\"\\\\b" . $term . "\\\\b\"";
    }
    
    private function extractSynonyms($string)
    {
        $synonyms = [];
        if(str_contains($string, '#')) {
            $parts = explode('#', $string);
            array_shift($parts);
            foreach ($parts as $part) {
                $synonyms[] = trim($part);
            }
        }
        
        return $synonyms;
    }
  
}
