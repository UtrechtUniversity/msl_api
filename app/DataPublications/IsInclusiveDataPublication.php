<?php

namespace App\DataPublications;

use App\Models\Ckan\DataPublication;

class IsInclusiveDataPublication
{
    public function __construct(
        public DataPublication $dataPublication,
        public bool $isInclusive
    ) {}
}
