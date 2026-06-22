<?php

namespace App\Scout;

use Laravel\Scout\Builder as ScoutBuilder;

class Builder extends ScoutBuilder
{
    /**
     * Array of facet fields to facet on.
     *
     * @var array<string>
     */
    public array $facetFields = [];

    public function facetField(string $field): static
    {
        $this->facetFields[] = $field;

        return $this;
    }



}
