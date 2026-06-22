<?php

namespace App\Scout;

interface CkanSearchableInterface
{
    /*
     * Type used for this class in CKAN/SOLR
     */
    public function getCkanType(): string;

    /*
     * Key used from CKAN record to map back to its database equivalent
     * The reverse key is specified in the getScoutKeyName function.
     */
    public function getCkanMapKeyName(): string;

}
