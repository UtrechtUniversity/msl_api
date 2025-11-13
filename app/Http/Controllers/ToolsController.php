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
        $allVocabs = Vocabulary::where('version', config('vocabularies.vocabularies_current_version'))->get();
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
        $group1 = [];
        $group2 = [];

        $vocabularies = Vocabulary::where('version', config('vocabularies.vocabularies_current_version'))->get();

        foreach ($vocabularies as $vocabulary) {
            $keywordsGroup1 = Keyword::where('vocabulary_id', $vocabulary->id)->where('selection_group_1', true)->get();

            foreach ($keywordsGroup1 as $keywordGroup1) {
                foreach ($keywordGroup1->keyword_search as $keywordSearch) {
                    if (! $keywordSearch->exclude_abstract_mapping) {
                        $group1[] = $this->createKeywordSearchRegex($keywordSearch->search_value);
                    }
                }
            }

            $keywordsGroup2 = Keyword::where('vocabulary_id', $vocabulary->id)->where('selection_group_2', true)->get();

            foreach ($keywordsGroup2 as $keywordGroup2) {
                foreach ($keywordGroup2->keyword_search as $keywordSearch) {
                    if (! $keywordSearch->exclude_abstract_mapping) {
                        $group2[] = $this->createKeywordSearchRegex($keywordSearch->search_value);
                    }
                }
            }
        }

        $query1 = implode(',', array_unique($group1));
        $query2 = implode(',', array_unique($group2));

        return view('admin.query-generator', ['queryGroup1' => $query1, 'group1Count' => count(array_unique($group1)), 'queryGroup2' => $query2, 'group2Count' => count(array_unique($group2))]);
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
