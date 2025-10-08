<?php

namespace App\Jobs\SourceDatasetIdentifierProcessors;

use App\Models\SourceDatasetIdentifier;

interface SourceDatasetIdentifierProcessorInterface
{
    public static function process(SourceDatasetIdentifier $sourceDatasetIdentifier): bool;
}