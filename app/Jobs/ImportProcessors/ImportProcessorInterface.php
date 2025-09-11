<?php

namespace App\Jobs\ImportProcessors;

use App\Models\Import;

interface ImportProcessorInterface
{
    /**
     * @param Import $import
     * @param bool|int $limit limit the amount of SourceDatasetIdentifier jobs created
     */
    public static function process(Import $import, bool|int $limit = false): bool;
}