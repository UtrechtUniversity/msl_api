<?php

namespace Tests\Feature;

use App\Mappers\Additional\MagicFileMapper;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;
use Tests\TestCase;

class MagicFileMapperTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_map(): void
    {
        $datapublication = new DataPublication;
        $sourceDataset = new SourceDataset;
        $sourceDatasetIdentifier = new SourceDatasetIdentifier;

        $sourceDataset->setRelation('source_dataset_identifier', $sourceDatasetIdentifier);

        $magicFileMapper = new MagicFileMapper;
        $datapublication->msl_source = 'https://earthref.org/MagIC/12020';

        $datapublication = $magicFileMapper->map($datapublication, $sourceDataset);

        $this->assertEquals($datapublication->msl_files[0]->msl_file_name, 'magic_contribution_12020.txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_download_link, 'https://earthref.org/MagIC/download/12020/magic_contribution_12020.txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_extension, 'txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_is_folder, false);
    }
}
