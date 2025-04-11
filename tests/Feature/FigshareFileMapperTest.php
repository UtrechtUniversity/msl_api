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
                        'is_link_only' => false,
                        'download_url' => "https://ndownloader.figshare.com/files/24044669",
                        'supplied_md5' => '3315787ee65dac6e49f9c67948e8c955',
                        'computed_md5' => '3315787ee65dac6e49f9c67948e8c955',
                        'mimetype' => 'text/plain'
                    ],
                    [
                        'id' => 24044672,
                        'name' => "data.zip",
                        'size' => 4335,
                        'is_link_only' => false,
                        'download_url' => "https://ndownloader.figshare.com/files/24044672",
                        'supplied_md5' => '57ca9aa3e468ef034059eba8efed90f3',
                        'computed_md5' => '57ca9aa3e468ef034059eba8efed90f3',
                        'mimetype' => 'application/zip'
                    ],
                ]);
        });

        $figshareMapper = new FigshareFileMapper;
        $dataPublication = $figshareMapper->map($dataPublication);

        $this->assertEquals($dataPublication->msl_files[0]->msl_file_name, 'README.txt');
        $this->assertEquals($dataPublication->msl_files[0]->msl_download_link, 'https://ndownloader.figshare.com/files/24044669');
        $this->assertEquals($dataPublication->msl_files[0]->msl_extension, 'txt');

        $this->assertEquals($dataPublication->msl_files[1]->msl_file_name, 'data.zip');
        $this->assertEquals($dataPublication->msl_files[1]->msl_download_link, 'https://ndownloader.figshare.com/files/24044672');
        $this->assertEquals($dataPublication->msl_files[1]->msl_extension, 'zip');
    }
}
