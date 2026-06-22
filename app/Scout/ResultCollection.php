<?php

namespace App\Scout;

use Illuminate\Database\Eloquent\Collection;

class ResultCollection extends Collection
{
    public array $facets = [];

    public array $searchFacets= [];

    public int $totalResults;
}
