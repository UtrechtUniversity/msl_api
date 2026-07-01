<?php

namespace App\Scout;

use App\Clients\CkanClient\Client;
use App\Clients\CkanClient\Request\PackageSearchRequest;
use App\Jobs\ProcessCkanCreate;
use App\Jobs\ProcessCkanDelete;
use App\Jobs\ProcessCkanFlush;
use App\Scout\Builder as Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\LazyCollection;
use Laravel\Scout\Builder as ScoutBuilder;
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
     */
    public function update($models): void
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
     */
    public function delete($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        foreach ($models as $model) {
            ProcessCkanDelete::dispatch($model->id);
        }
    }

    /**
     * Perform the given search on the engine.
     */
    public function search(ScoutBuilder $builder): mixed
    {
        return $this->performSearch($builder, array_filter([]));
    }

    /**
     * Perform the given search on the engine.
     */
    public function paginate(ScoutBuilder $builder, $perPage, $page): mixed
    {
        $searchResults = $this->performSearch($builder, [
            'hitsPerPage' => $perPage,
            'page' => $page,
        ]);

        $results = $this->map($builder, $searchResults, $builder->model);

        $perPage = $perPage ?: $builder->model->getPerPage();

        return new LengthAwarePaginator(
            $results,
            $results->totalResults,
            $perPage,
            $page
        );
    }

    protected function performSearch(Builder $builder, array $options = [])
    {
        $ckanRequest = new PackageSearchRequest();

        $builder->filterWhere('type', $builder->model->getCkanType());

        $ckanRequest->query = $this->buildQuery($builder);

        $ckanRequest->filterQuery = $this->buildFilterQuery($builder);

        $ckanRequest->rows = $builder->limit ?? 10;

        if(isset($options['hitsPerPage'])) {
            $ckanRequest->rows = $options['hitsPerPage'];
        }

        if(isset($options['page'])) {
            $ckanRequest->start = $options['page'] * ($builder->limit ?? 10);
        }

        foreach ($builder->facetFields as $facetField) {
            $ckanRequest->addFacetField($facetField);
        }

        if ($builder->orders) {
            $orders = [];
            foreach ($builder->orders as $sort) {
                $orders[] = $sort['column'] . ' ' . $sort['direction'];
            }

            $ckanRequest->sortField = implode(', ', $orders);
        }

        if ($builder->boundingBox) {
            $ckanRequest->setBoundingBox(
               $builder->boundingBox['minX'],
               $builder->boundingBox['minY'],
               $builder->boundingBox['maxX'],
               $builder->boundingBox['maxY']
           );
        }

        $response = $this->ckanClient->get($ckanRequest);

        return $response->getResult();
    }

    protected function buildQuery(Builder $builder): string
    {
        $search = strlen($builder->query) > 0 ? $builder->query : [];
        $search = collect($search);

        $wheres = $this->wheresToQueryParts($builder->wheres);
        $whereIns = $this->whereInsToQueryParts($builder->whereIns);

        // whereNotIn not implemented due to solr issues

        return $search->merge($wheres)->merge($whereIns)->filter()->implode(' AND ');
    }

    protected function buildFilterQuery(Builder $builder): string
    {
        $wheres = $this->wheresToQueryParts($builder->filterWheres);
        $whereIns = $this->whereInsToQueryParts($builder->filterWhereIns);

        // whereNotIn not implemented due to solr issues

        return $wheres->merge($whereIns)->filter()->implode(' AND ');
    }

    private function wheresToQueryParts(array $wheres)
    {
        return collect($wheres)
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
    }

    private function whereInsToQueryParts(array $whereIns)
    {
        return collect($whereIns)
            ->map(function ($values, $key) {
                if (empty($values)) {
                    return '';
                }

                return '('.collect($values)->map(function ($value) use ($key) {
                        return $key.":\"{$value}\"";
                    })->implode(' OR ').')';
            })->values();
    }


    /**
     * Pluck and return the primary keys of the given results.
     */
    public function mapIds($results): \Illuminate\Support\Collection
    {
        dd('?');
        return collect($results['results'])->pluck()->values();
    }

    /**
     * Map the given results to instances of the given model.
     */
    public function map($builder, $results, $model): ResultCollection
    {
        if (count($results['results']) === 0) {
            return ResultCollection::make();
        }

        $objectIds = collect($results['results'])->pluck($model->getCkanMapKeyName())->values()->all();

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
    public function lazyMap($builder, $results, $model)
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
        ProcessCkanFlush::dispatch($model->getCkanType());
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

    public function simplePaginate(ScoutBuilder $builder, $perPage, $page): Paginator
    {
        $searchResults = $this->performSearch($builder, [
            'hitsPerPage' => $perPage,
            'page' => $page,
        ]);

        $results = $this->map($builder, $searchResults, $builder->model);

        $perPage = $perPage ?: $builder->model->getPerPage();

        $paginator = new Paginator($results, $results->totalResults, $perPage);
        $paginator->hasMorePagesWhen(($perPage * $page) < $results->totalResults);

        return $paginator;
    }
}
