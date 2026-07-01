<?php

namespace App\Services;

use App\Models\Keyword;
use App\Models\Laboratory\Laboratory;
use App\Scout\Builder;
use Illuminate\Http\Request;

class LaboratoryService
{
    public function search(Request $request)
    {
        // @todo add generic query builder for search to be reused in other searchable classes

        // first handle the query parameter as it set once to create the query builder
        $builder = Laboratory::search($this->getSearchString($request));

        $builder = $this->setFacetFilters($builder, $request);
        $builder = $this->setFacets($builder);
        $builder->orderBy('title_string', 'asc');


        return $builder->paginate(20, 'page', $request->page)->setPath($request->url())->appends($request->query());
    }

    public function getActiveFilters(Request $request)
    {
        $activeFilters = [];

        foreach ($request->query() as $key => $values) {
            if (array_key_exists($key, config('ckan.facets.laboratories')) || $key === 'query') {
                if(is_array($values)) {
                    foreach ($values as $value) {
                        $activeFilters[$key][] = $value;
                    }
                }
            }
        }

        return $activeFilters;
    }

    public function getActiveFiltersFrontend(Request $request)
    {
        $activeFiltersFrontend = [];

        foreach ($request->query() as $key => $values) {
            if (array_key_exists($key, config('ckan.facets.laboratories')) || $key === 'query') {
                if(is_array($values)) {
                    foreach ($values as $value) {
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
                            $label = 'Search: ' . $value;
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

                        $removeUrl = $query ? url()->current() . '?' . http_build_query($query) : url()->current();

                        $activeFiltersFrontend[] = [
                            'value' => $value,
                            'label' => $label,
                            'removeUrl' => $removeUrl,
                        ];
                    }
                }
            }
        }

        return $activeFiltersFrontend;
    }

    private function getSearchString(Request $request)
    {
        $searchQuery = "";
        if ($request->query('query')) {
            if(is_array($request->query('query')))
            {
                $searchQuery = implode(' ', $request->query('query'));
            } else {
                $searchQuery = $request->query('query');
            }
        }

        return $searchQuery;
    }

    private function setFacets(Builder $builder)
    {
        $facets = config('ckan.facets.laboratories');
        foreach ($facets as $key => $value) {
            $builder->facetField($key);
        }

        return $builder;
    }

    private function setFacetFilters(Builder $builder, Request $request)
    {
        foreach ($request->query() as $key => $values) {
            if (array_key_exists($key, config('ckan.facets.laboratories'))) {
                foreach ($values as $value) {
                    $builder->filterWhere($key, ':', $value);
                }
            }
        }

        return $builder;
    }
}
