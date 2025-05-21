<?php

namespace App\Http\Controllers;

use App\CkanClient\Client;
use App\CkanClient\Request\OrganizationListRequest;
use App\CkanClient\Request\PackageSearchRequest;
use App\Converters\ExcelToJsonConverter;
use App\Converters\VocabularyToJsonConverter;
use App\Exports\AbstractMatchingExport;
use App\Exports\FilterTreeExport;
use App\Exports\UnmatchedKeywordsExport;
use App\Exports\UriLabelExport;
use App\Mappers\Helpers\KeywordHelper;
use App\Models\Keyword;
use App\Models\Laboratory;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

        $allVocabs = Vocabulary::where('version', 'LIKE', config('vocabularies.vocabularies_current_version'))->get();
        $displayNames = [];
        foreach ($allVocabs as $entry) {
            $displayNames[] = $entry->display_name;
        }

        return view('admin.convert-keywords', ['displayNames' => $displayNames]);
    }

    public function geoview()
    {
        $client = new Client;
        $searchRequest = new PackageSearchRequest;
        $searchRequest->query = 'type:data-publication msl_surface_area:[0 TO 500]';
        $searchRequest->rows = 1000;

        $result = $client->get($searchRequest);
        $results = $result->getResults();

        $featureArray = [];
        $featureArrayPoints = [];

        foreach ($results as $result) {
            if (isset($result['msl_geojson_featurecollection'])) {
                if (strlen($result['msl_geojson_featurecollection']) > 0) {
                    $featureArray[] = $result['msl_geojson_featurecollection'];
                }
            }

            // include extra data in point features for map testing
            if (isset($result['msl_geojson_featurecollection_points'])) {
                if (strlen($result['msl_geojson_featurecollection_points']) > 0) {
                    $pointFeature = json_decode($result['msl_geojson_featurecollection_points']);

                    foreach ($pointFeature->features as &$subFeature) {
                        $subFeature->properties->name = $result['title'];
                        $subFeature->properties->ckan_id = $result['name'];
                        $subFeature->properties->area_geojson = $result['msl_geojson_featurecollection'];
                    }

                    $pointFeature->features[0]->properties->name = $result['title'];
                    $pointFeature->features[0]->properties->ckan_id = $result['name'];
                    $pointFeature->features[0]->properties->area_geojson = $result['msl_geojson_featurecollection'];

                    $pointFeature = json_encode($pointFeature);

                    $featureArrayPoints[] = $pointFeature;
                }
            }

        }

        return view('admin.geoview', ['features' => json_encode($featureArray), 'featuresPoints' => json_encode($featureArrayPoints)]);
    }

    public function geoviewLabs()
    {
        $labs = Laboratory::where('latitude', '<>', '')->get();

        $featureArray = [];

        foreach ($labs as $lab) {
            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'name' => $lab->name,
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [str_replace(',', '.', $lab->longitude), str_replace(',', '.', $lab->latitude)],
                ],
            ];

            $featureArray[] = $feature;
        }

        return view('admin.geoview-labs', ['features' => json_encode($featureArray)]);
    }

    public function processFile(Request $request)
    {
        $formFields = $request->validate([
            'uploaded-file' => 'required',
            'domain-selection' => 'required',
        ]);

        $filePath = $request->file('uploaded-file');
        $selectedDomainDisplayName = $formFields['domain-selection'];
        $selectedDomainName = 'none';
        // get the selected name
        $allVocabs = Vocabulary::where('version', 'LIKE', config('vocabularies.vocabularies_current_version'))->get();
        foreach ($allVocabs as $entry) {
            if ($entry->display_name == $selectedDomainDisplayName) {
                $selectedDomainName = $entry->name;
                break;
            }
        }

        if (! (str_contains($request->file('uploaded-file')->getClientOriginalName(), $selectedDomainName))) {
            return back()
                ->with('error', 'Ooops, the filename string does not contain the selected domain/field string from the dropdown. Are you sure you selected the right file?');
        }

        if ($request->hasFile('uploaded-file')) {

            $converter = new VocabularyToJsonConverter;
            try {
                $outcomeJson = $converter->excelToJson($filePath, $selectedDomainName);

                return response()->streamDownload(function () use ($outcomeJson) {
                    echo $outcomeJson;
                }, $selectedDomainName.'.json');
            } catch (\Exception $e) {
                return back()
                    ->with('error', $e->getMessage());
            }

        }

        return back()
            ->with('error', 'Error. Something went wrong');
    }

    public function convertExcel()
    {
        return view('admin.convert-excel');
    }

    public function processExcelToJson(Request $request)
    {
        $request->validate([
            'excel-file' => 'required',
        ]);

        if ($request->hasFile('excel-file')) {
            $converter = new ExcelToJsonConverter;

            return response()->streamDownload(function () use ($converter, $request) {
                echo $converter->ExcelToJson($request->file('excel-file'));
            }, 'converted.json');

        }

        return back()
            ->with('status', 'Error');
    }

    public function uriLabels()
    {
        return view('admin.uri-labels');
    }

    public function uriLabelsDownload()
    {
        $exporter = new UriLabelExport;

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->export();
        }, 'uri-labels.json');
    }

    public function filterTree()
    {
        return view('admin.filter-tree');
    }

    public function filterTreeDownload()
    {
        $exporter = new FilterTreeExport;

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->exportInterpreted();
        }, 'interpreted.json');
    }

    public function filterTreeDownloadOriginal()
    {
        $exporter = new FilterTreeExport;

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->exportOriginal();
        }, 'original.json');
    }

    public function filterTreeDownloadEquipment()
    {
        $exporter = new FilterTreeExport;

        return response()->streamDownload(function () use ($exporter) {
            echo $exporter->exportEquipment();
        }, 'equipment.json');
    }

    public function viewUnmatchedKeywords()
    {
        $client = new Client;
        $searchRequest = new PackageSearchRequest;
        $searchRequest->query = 'type:data-publication';
        $searchRequest->rows = 1000;

        $result = $client->get($searchRequest);
        $results = $result->getResults();

        $keywords = [];
        foreach ($results as $result) {
            if (count($result['tags']) > 0) {
                foreach ($result['tags'] as $tag) {
                    if (! array_key_exists($tag['name'], $keywords)) {
                        $keywords[$tag['name']] = 1;
                    } else {
                        $keywords[$tag['name']] = $keywords[$tag['name']] + 1;
                    }
                }
            }
        }

        uasort($keywords, function ($a, $b) {
            return $b - $a;
        });

        return view('admin.unmatched-keywords', ['keywords' => $keywords]);
    }

    public function downloadUnmatchedKeywords()
    {
        return Excel::download(new UnmatchedKeywordsExport, 'unmatched-keywords.xlsx');
    }

    public function abstractMatching(Request $request)
    {
        $client = new Client;
        $organizationListRequest = new OrganizationListRequest;

        $result = $client->get($organizationListRequest);
        $organizations = $result->getResult();

        $filteredOrganizations = [];

        foreach ($organizations as $organization) {
            if ($organization['name'] !== 'epos-multi-scale-laboratories-thematic-core-service') {
                $filteredOrganizations[] = $organization;
            }
        }

        $data = [];
        $selected = '';

        if ($request->has('datasetSource')) {
            $datasetSource = $request->query('datasetSource');
            $selected = $datasetSource;

            $client = new Client;
            $searchRequest = new PackageSearchRequest;
            $searchRequest->query = 'type:data-publication';
            $searchRequest->addFilterQuery('owner_org', $datasetSource);
            $searchRequest->rows = 10;

            $result = $client->get($searchRequest);
            $results = $result->getResults();

            $keywordHelper = new KeywordHelper;

            foreach ($results as $result) {
                $item = [];
                $item['identifier'] = $result['msl_doi'];
                $item['title'] = $result['title'];
                $item['abstract'] = $result['notes'];
                $item['keywords'] = [];
                $keywords = $keywordHelper->extractFromText($item['abstract'].' '.$item['title']);

                foreach ($keywords as $keyword) {
                    $item['keywords'][] = $keyword->getFullPath('>', true);
                }

                $data[] = $item;
            }
        }

        return view('admin.abstract-matching', ['data' => $data, 'organizations' => $filteredOrganizations, 'selected' => $selected]);
    }

    public function abstractMatchingDownload($dataRepo)
    {
        return Excel::download(new AbstractMatchingExport($dataRepo), 'abstract-matching.xlsx');
    }

    public function doiExport(Request $request)
    {
        $client = new Client;
        $organizationListRequest = new OrganizationListRequest;

        $result = $client->get($organizationListRequest);
        $organizations = $result->getResult();

        if ($request->has('organization')) {
            $OrganizationId = $request->query('organization');

            $client = new Client;
            $searchRequest = new PackageSearchRequest;
            $searchRequest->query = 'type:data-publication';
            $searchRequest->addFilterQuery('owner_org', $OrganizationId);
            $searchRequest->rows = 200;

            $result = $client->get($searchRequest);
            $results = $result->getResults();

            $dois = [];

            foreach ($results as $result) {
                $dois[] = '"'.strtolower($result['msl_doi']).'"';
            }

            dd(implode(', ', $dois));
        }

        return view('admin.export-dois', ['organizations' => $organizations]);
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
         *  - (sub)surface utilization setting
         * GROUP #2
         *  - Analogue modelling -> Apparatus
         *  - Analogue modelling -> Measured property
         *  - Geochemistry -> Analysis
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

        // Keyword identifiers to be ignored while gathering terms (based on vocab 1.3)
        $skipKeywords = [
            250, 251, 252, 253, 254, 255, 256, 257, 260, 261, 262, 263, 264, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276,
            864, 1522, 896, 1543, 897, 1544, 898, 1545, 899, 1546, 900, 1547, 919, 920, 921, 922, 563, 564, 489, 565, 566, 567, 905,
            1552, 932, 1572, 556, 24, 658, 362, 81, 916, 1554, 917, 1555, 933, 1573, 2749, 2748, 915, 1553, 918, 895, 1542,
        ];

        $skipSearchKeywords = [
            2652, 1446, 822, 2862, 4622,
        ];

        $materialVocab = Vocabulary::where('name', 'materials')->where('version', '1.3')->first();
        $materialTerms = $materialVocab->search_keywords;
        $terms = [];
        $query = '';
        $total = 0;

        foreach ($materialTerms as $materialTerm) {
            if (in_array($materialTerm->keyword_id, $skipKeywords)) {
                continue;
            }
            if (in_array($materialTerm->id, $skipSearchKeywords)) {
                continue;
            }

            $terms[] = $this->createKeywordSearchRegex($materialTerm->search_value);
        }

        $geologicalSettingsVocab = Vocabulary::where('name', 'geologicalsetting')->where('version', '1.3')->first();
        $geologicalSettingsTerms = $geologicalSettingsVocab->search_keywords;

        foreach ($geologicalSettingsTerms as $geologicalSettingsTerm) {
            if (in_array($geologicalSettingsTerm->keyword_id, $skipKeywords)) {
                continue;
            }

            if (in_array($geologicalSettingsTerm->id, $skipSearchKeywords)) {
                continue;
            }

            if (str_starts_with($geologicalSettingsTerm->keyword->uri, 'https://epos-msl.uu.nl/voc/geologicalsetting/1.3/antropogenic_setting-civil_engineered_setting')) {
                continue;
            }

            if (str_starts_with($geologicalSettingsTerm->keyword->uri, 'https://epos-msl.uu.nl/voc/geologicalsetting/1.3/surface_morphological_setting')) {
                continue;
            }

            $terms[] = $this->createKeywordSearchRegex($geologicalSettingsTerm->search_value);
        }

        $subsurfaceVocab = Vocabulary::where('name', 'subsurface')->where('version', '1.3')->first();
        $subsurfaceSettingsTerms = $subsurfaceVocab->search_keywords;

        foreach ($subsurfaceSettingsTerms as $subsurfaceSettingsTerm) {
            if (in_array($subsurfaceSettingsTerm->keyword_id, $skipKeywords)) {
                continue;
            }

            if (in_array($subsurfaceSettingsTerm->id, $skipSearchKeywords)) {
                continue;
            }

            if (str_starts_with($subsurfaceSettingsTerm->keyword->uri, 'https://epos-msl.uu.nl/voc/subsurface/1.3/civil_engineered_setting')) {
                continue;
            }

            $terms[] = $this->createKeywordSearchRegex($subsurfaceSettingsTerm->search_value);
        }

        $query .= implode(',', array_unique($terms));
        dd($query);

        $total += count($terms);

        $terms = [];
        $query = '';

        // analogue modeling apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // analogue modeling measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/analoguemodelling/1.3/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // geochemistry analysis
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/geochemistry/1.3/analysis-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // microscopy apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.3/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // microscopy technique
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.3/technique-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // microscopy analyzed feature
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.3/analyzed_feature-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // microscopy inferred behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/microscopy/1.3/inferred_parameter-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // paleomagnetism apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.3/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // paleomagnetism measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.3/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // paleomagnetism inferred behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/paleomagnetism/1.3/inferred_behavior-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // rockphysics apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.3/apparatus-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // rockphysics measured property
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.3/measured_property-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // rockphysics inferred deformation behavior
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/rockphysics/1.3/inferred_deformation_behavior-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        // dd(count($terms), count(array_unique($terms)));

        $terms = array_unique($terms);

        $query .= implode(',', $terms);
        // dd($query);

        $terms = [];
        $query = '';

        // analogue modeling apparatus
        $keywords = Keyword::where('uri', 'like', 'https://epos-msl.uu.nl/voc/testbeds/1.3/facility_names-%')->get();
        foreach ($keywords as $keyword) {
            foreach ($keyword->keyword_search as $searchKeyword) {
                if (in_array($searchKeyword->keyword_id, $skipKeywords)) {
                    continue;
                }
                if (in_array($searchKeyword->id, $skipSearchKeywords)) {
                    continue;
                }

                $terms[] = $this->createKeywordSearchRegex($searchKeyword->search_value);
            }
        }

        $terms = array_unique($terms);

        $query .= implode(',', $terms);
        dd($query);

        return view('admin.query-generator', ['query' => $query]);
    }

    private function createKeywordSearchRegex($searchValue)
    {
        $term = $searchValue;

        $term = str_replace('(', '\\\\(', $term);
        $term = str_replace(')', '\\\\)', $term);
        $term = str_replace('.', '\\\\.', $term);
        $term = str_replace('*', '\\\\*', $term);

        if (str_ends_with($searchValue, ',')) {
            return '"\\\\b'.$term.'"';
        }

        return '"\\\\b'.$term.'\\\\b"';
    }

    private function extractSynonyms($string)
    {
        $synonyms = [];
        if (str_contains($string, '#')) {
            $parts = explode('#', $string);
            array_shift($parts);
            foreach ($parts as $part) {
                $synonyms[] = trim($part);
            }
        }

        return $synonyms;
    }
}
