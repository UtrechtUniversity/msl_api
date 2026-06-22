<?php

namespace App\Scout;

use Illuminate\Contracts\Support\Arrayable;
use Laravel\Scout\Builder as ScoutBuilder;

class Builder extends ScoutBuilder
{
    /**
     * Array of facet fields to facet on.
     *
     * @var array<string>
     */
    public array $facetFields = [];

    /**
     * The "where" filter query constraints added to the query.
     */
    public array $filterWheres = [];

    /**
     * The "where in" filter query constraints added to the query.
     */
    public array $filterWhereIns = [];

    /**
     * Bounding box
     */
    public array $boundingBox = [];

    public function facetField(string $field): static
    {
        $this->facetFields[] = $field;

        return $this;
    }

    /**
     * Add a "where" constraint to the filter query.
     */
    public function filterWhere($field, $operator, $value = null): static
    {
        $this->filterWheres[] = [
            'field' => $field,
            'operator' => func_num_args() === 2 ? '=' : $operator,
            'value' => func_num_args() === 2 ? $operator : $value,
        ];

        return $this;
    }

    /**
     * Add a "where in" constraint to the filter query.
     */
    public function filterWhereIn(string $field, array|Arrayable $values): static
    {
        if ($values instanceof Arrayable) {
            $values = $values->toArray();
        }

        $this->filterWhereIns[$field] = $values;

        return $this;
    }

    public function boundingBox(float $minX, float $minY, float $maxX, float $maxY): static
    {
        $this->boundingBox = [
            'minX' => $minX,
            'minY' => $minY,
            'maxX' => $maxX,
            'maxY' => $maxY,
        ];

        return $this;
    }



}
