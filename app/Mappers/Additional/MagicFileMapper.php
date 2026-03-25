<?php

namespace App\Mappers\Additional;

use App\Models\Ckan\DataPublication;
use App\Models\Ckan\File;
use App\Models\SourceDataset;
use Illuminate\Support\Uri;

class MagicFileMapper extends AdditionalMapper
{
    protected function validateInput(array $args): array
    {
        return [];
    }

    protected function initialize(array $args): void {}

    /**
     * Add MagIC files associated by identifier used
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        $uri = Uri::of($dataPublication->msl_source);

        if (! $uri->isEmpty()) {
            $magicIdentifier = $uri->pathSegments()->last();

            $mslFile = new File(
                'magic_contribution_'.$magicIdentifier.'.txt',
                'https://earthref.org/MagIC/download/'.$magicIdentifier.'/magic_contribution_'.$magicIdentifier.'.txt',
                'txt',
                false
            );

            $dataPublication->addFile($mslFile);
        }

        return $dataPublication;
    }
}
