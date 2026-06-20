<?php

namespace App\Scout;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageSearchRequest;
use App\Jobs\ProcessCkanCreate;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class CkanSearchEngine extends Engine
{
    private Client $ckanClient;
    public function __construct()
    {
        $this->ckanClient = new Client();
    }

    public function update($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        foreach ($models as $model) {
            ProcessCkanCreate::dispatch($model);
        }
    }

    public function delete($models)
    {
        // TODO: Implement delete() method.
    }

    public function search(Builder $builder)
    {
        return $this->performSearch($builder, array_filter([
            //'query' => $this->buildQuery($builder),
            //'limit' => $builder->limit,
        ]));
    }

    public function paginate(Builder $builder, $perPage, $page)
    {
        // TODO: Implement paginate() method.
    }

    protected function performSearch(Builder $builder, array $options = [])
    {
        $request = new PackageSearchRequest();

        if (array_key_exists('filters', $options)) {
            $request->query = $options['filters'];
        } else {
            $request->query = $builder->query;
        }

        $request->query = $this->buildQuery($builder);

        // TODO: add filterQuery parts to query


        //dd($builder->options);
        //dd($options);


        // TODO: replace this filter with model property from model
        $request->addFilterQuery('type', 'lab');

        $response = $this->ckanClient->get($request);

        return $response->getResult();
    }

    protected function buildQuery(Builder $builder)
    {
        dd($builder->wheres);
    }

    public function mapIds($results)
    {
        return collect($results['results'])->pluck('msl_id')->values();
    }

    public function map(Builder $builder, $results, $model)
    {
        if (count($results['results']) === 0) {
            return $model->newCollection();
        }

        // TODO: the id field should either be a fixed field thats the same for all classes or set in model
        $objectIds = collect($results['results'])->pluck('msl_fast_id')->values()->all();

        $objectIdPositions = array_flip($objectIds);

        return $model->getScoutModelsByIds($builder, $objectIds)
            ->filter(function ($model) use ($objectIds) {
                return in_array($model->getScoutKey(), $objectIds);
            })->sortBy(function ($model) use ($objectIdPositions) {
                return $objectIdPositions[$model->getScoutKey()];
            })->values();
    }

    public function lazyMap(Builder $builder, $results, $model)
    {
        // TODO: Implement lazyMap() method.
    }

    public function getTotalCount($results)
    {
        // TODO: Implement getTotalCount() method.
    }

    public function flush($model)
    {
        // TODO: Implement flush() method.
    }

    public function createIndex($name, array $options = [])
    {
        // TODO: Implement createIndex() method.
    }

    public function deleteIndex($name)
    {
        // TODO: Implement deleteIndex() method.
    }

}
