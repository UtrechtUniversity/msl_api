<?php

namespace App\DataPublications;

use App\Models\Ckan\DataPublication;

class isInclusiveDataPublication
{
    public function __construct(
        public readonly DataPublication $dataPublication,
        public readonly bool $isInclusive
    ) {}
}
