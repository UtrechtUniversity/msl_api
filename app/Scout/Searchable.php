<?php

namespace App\Scout;

use Laravel\Scout\EngineManager;
use App\Scout\Builder;

trait Searchable
{
    use \Laravel\Scout\Searchable;



    public static function search($query = '', $callback = null)
    {
        return app(static::$scoutBuilder ?? \App\Scout\Builder::class, [
            'model' => new static,
            'query' => $query,
            'callback' => $callback,
            'softDelete' => static::usesSoftDelete() && config('scout.soft_delete', false),
        ]);
    }
}
