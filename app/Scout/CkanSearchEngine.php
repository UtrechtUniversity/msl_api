<?php

namespace App\Scout;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Jobs\ProcessCkanCreate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
use App\Scout\Builder;
use Laravel\Scout\Contracts\PaginatesEloquentModels;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Exceptions\NotSupportedException;

class CkanSearchEngine extends Engine implements PaginatesEloquentModels
{
    private Client $ckanClient;
    public function __construct()
    {
        $this->ckanClient = new Client();
    }

    /**
     * Update the given models in ckan.
     *
     * @param Collection $models
     * @return void
     */
    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        foreach ($models as $model) {
            ProcessCkanCreate::dispatch($model);
        }
    }

    /**
     * Remove the given models from the ckan.
     *
     * @param Collection $models
     * @return void
     */
    public function delete($models)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     *
     * @return mixed
     */
    public function search(ScoutBuilder $builder): mixed
    {
        return $this->performSearch($builder, array_filter([
            //'query' => $this->buildQuery($builder),
            //'limit' => $builder->limit,
        ]));
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param int $perPage
     * @param int $page
     *
     * @return mixed
     */
    public function paginate(ScoutBuilder $builder, $perPage, $page): mixed
    {
        $searchResults = $this->performSearch($builder, [
            'hitsPerPage' => $perPage,
            'page' => $page,
        ]);

        $results = $this->map($builder, $searchResults, $builder->model);

        $perPage = $perPage ?: $builder->model->getPerPage();

        $paginator = new LengthAwarePaginator($results, $results->totalResults, $perPage, $page);
        $paginator->appends('query', $builder->query);

        return $paginator;
    }

    protected function performSearch(Builder $builder, array $options = [])
    {
        $request = new PackageSearchRequest();

        $request->query = $this->buildQuery($builder);

        $request->rows = $builder->limit ?? 10;

        //dd($request->query);

        // TODO: add filterQuery parts to query


        if(isset($options['hitsPerPage'])) {
            $request->rows = $options['hitsPerPage'];
        }

        if(isset($options['page'])) {
            $request->start = $options['page'] * $request->rows;
        }

        foreach ($builder->facetFields as $facetField) {
            $request->addFacetField($facetField);
        }


        //dd($builder->options);
        //dd($options);


        // TODO: replace this filter with model property from model
        $request->addFilterQuery('type', 'lab');

        $response = $this->ckanClient->get($request);
        //dd($request, $response);

        return $response->getResult();
    }

    protected function buildQuery(Builder $builder): string
    {
        $search = strlen($builder->query) > 0 ? $builder->query : [];
        $search = collect($search);

        $wheres = collect($builder->wheres)
            ->map(function ($where) {
                $field = $where['field'];
                $operator = $where['operator'];
                $value = $where['value'];

                if (!in_array($operator, ['=', ':'])) {
                    throw new \Exception('Operator not supported: '.$operator);
                }

                if (is_string($value) || $operator === '=') {
                    $operator = ':';
                    $value = "\"{$value}\"";
                }

                return $field.$operator.$value;
            })
            ->values();

        $whereIns = collect($builder->whereIns)
            ->map(function ($values, $key) {
                if (empty($values)) {
                    return '';
                }

                return '('.collect($values)->map(function ($value) use ($key) {
                        return $key.":\"{$value}\"";
                    })->implode(' OR ').')';
            })->values();

        //where not in not implemented due to solr issues

        return $search->merge($wheres)->merge($whereIns)->filter()->implode(' AND ');
    }

    /**
     * Pluck and return the primary keys of the given results.
     *
     * @param  mixed  $results
     * @return \Illuminate\Support\Collection
     */
    public function mapIds($results): \Illuminate\Support\Collection
    {
        return collect($results['results'])->pluck('msl_fast_id')->values();
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param Builder $builder
     * @param  mixed  $results
     * @param  Model  $model
     */
    public function map(ScoutBuilder $builder, $results, $model): ResultCollection
    {
        if (count($results['results']) === 0) {
            return ResultCollection::make();
        }

        // TODO: the id field should either be a fixed field thats the same for all classes or set in model
        $objectIds = collect($results['results'])->pluck('msl_fast_id')->values()->all();

        $objectIdPositions = array_flip($objectIds);

        $collection = $model->getScoutModelsByIds($builder, $objectIds)
            ->filter(function ($model) use ($objectIds) {
                return in_array($model->getScoutKey(), $objectIds);
            })->sortBy(function ($model) use ($objectIdPositions) {
                return $objectIdPositions[$model->getScoutKey()];
            })->values();

        /*
         * Unfortunately, there is no current support for facets in Scout.
         * This is a workaround to get the facets in the returned collection.
         */

        $collection = ResultCollection::make($collection);

        $collection->facets = $results['facets'];
        $collection->searchFacets = $results['search_facets'];
        $collection->totalResults = $results['count'];

        return $collection;
    }

    /**
     * Map the given results to instances of the given model via a lazy collection.
     *
     * @param  Builder  $builder
     * @param  mixed  $results
     * @param Model $model
     * @return LazyCollection
     */
    public function lazyMap(ScoutBuilder $builder, $results, $model)
    {
        // TODO: Implement lazyMap() method.
    }

    /**
     * Get the total count from a raw result returned by the engine.
     *
     * @param  mixed  $results
     * @return int
     */
    public function getTotalCount($results): int
    {
        return $results['count'];
    }

    /**
     * Flush all of the model's records from the engine.
     *
     * @param Model $model
     * @return void
     * @throws NotSupportedException
     */
    public function flush($model)
    {
        throw new NotSupportedException('Flush is not supported via CKAN API.');
    }

    /**
     * @throws NotSupportedException
     */
    public function createIndex($name, array $options = [])
    {
        throw new NotSupportedException('Index creation is not supported via CKAN API.');
    }

    /**
     * @throws NotSupportedException
     */
    public function deleteIndex($name)
    {
        throw new NotSupportedException('Index deletion is not supported via CKAN API.');
    }

    public function simplePaginate(ScoutBuilder $builder, $perPage, $page)
    {
        // TODO: Implement simplePaginate() method.
    }
}
