<?php

namespace Tests\Feature;

use App\Mappers\Additional\GfzFileMapper;
use App\Mappers\Helpers\GfzDownloadHelper;
use App\Models\Ckan\DataPublication;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\PreserveGlobalState;

class GfzFileMapperTest extends TestCase
{
    /**
     * Test mapping of file retrieved using YodaFileMapper
     */
    #[RunInSeparateProcess]
    #[preserveGlobalState(false)]
    public function test_map(): void
    {
        $dataPublication = new DataPublication;
        $dataPublication->msl_source = '12345';

        $this->mock('overload:' . GfzDownloadHelper::class, function(MockInterface $mock) {
            $mock->shouldReceive('getFiles')
                ->once()
                ->andReturn(
                    [
                        [
                            'fileName' => '2024-001_Wittmann-et-al_Data-Description.pdf',
                            'downloadLink' => 'https://datapub.gfz-potsdam.de/download/10.5880.GFZ.3.3.2024.001jbbbh2024-001_Wittmann-et-al_Data-Description.pdf',
                            'extension' => 'pdf',
                            'isFolder' => false,
                        ]
                    ]
                );
        });

        $gfzFileMapper = new GfzFileMapper;
        $dataPublication = $gfzFileMapper->map($dataPublication);

        $this->assertEquals($dataPublication->msl_files[0]->msl_file_name, '2024-001_Wittmann-et-al_Data-Description.pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_download_link, 'https://datapub.gfz-potsdam.de/download/10.5880.GFZ.3.3.2024.001jbbbh2024-001_Wittmann-et-al_Data-Description.pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_extension, 'pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_is_folder, false);
    }
}
