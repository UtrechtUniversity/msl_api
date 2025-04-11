<?php

namespace Tests\Feature;

use App\Mappers\Additional\FigshareFileMapper;
use App\Mappers\Helpers\FigshareFilesHelper;
use App\Models\Ckan\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\PreserveGlobalState;


class FigshareFileMapperTest extends TestCase
{
    /**
     * Test mapping of file retrieved using FigShareFilesHelper
     */
    #[RunInSeparateProcess]
    #[preserveGlobalState(false)]
    public function test_map(): void
    {
        $dataPublication = new DataPublication;
        $dataPublication->msl_doi = '12345';



        $this->mock('overload:' . FigshareFilesHelper::class, function(MockInterface $mock) {
            $mock->shouldReceive('getFileListByDOI')
                ->once()
                ->andReturn([
                    [
                        'id' => 24044669,
                        'name' => "README.txt",
                        'size' => 638,
                        'download_url' => "https://ndownloader.figshare.com/files/24044669"
                    ]
                ]);
        });

        $figshareMapper = new FigshareFileMapper;
        $dataPublication = $figshareMapper->map($dataPublication);

        $this->assertEquals($dataPublication->msl_files[0]->msl_file_name, 'README.txt');
        $this->assertEquals($dataPublication->msl_files[0]->msl_download_link, 'https://ndownloader.figshare.com/files/24044669');
        $this->assertEquals($dataPublication->msl_files[0]->msl_extension, 'txt');
    }
}
