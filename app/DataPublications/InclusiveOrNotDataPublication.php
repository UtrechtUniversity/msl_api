<?php

namespace App\DataPublications;

use App\Models\Ckan\DataPublication;

class InclusiveOrNotDataPublication
{
    public function __construct(
        public readonly DataPublication $dataPublication,
        public readonly bool $inclusiveOrNot
    ) {}
}
