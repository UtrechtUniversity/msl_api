<?php

namespace Tests\Feature;

use App\Mappers\Additional\MagicFileMapper;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use App\Models\SourceDatasetIdentifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $sourceDatasetIdentifier->extra_payload = [
            'contentUrl' => 'https:\/\/earthref.org\/MagIC\/download\/11846\/magic_contribution_11846.txt'
        ];

        $sourceDataset->setRelation('source_dataset_identifier', $sourceDatasetIdentifier);
        
        $magicFileMapper = new MagicFileMapper;

        $datapublication = $magicFileMapper->map($datapublication, $sourceDataset);

        $this->assertEquals($datapublication->msl_files[0]->msl_file_name, 'magic_contribution_11846.txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_download_link, 'https:\/\/earthref.org\/MagIC\/download\/11846\/magic_contribution_11846.txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_extension, 'txt');
        $this->assertEquals($datapublication->msl_files[0]->msl_is_folder, false);        
    }
}
