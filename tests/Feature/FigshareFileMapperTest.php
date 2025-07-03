<?php

namespace Tests\Feature;

use App\Mappers\Additional\FigshareFileMapper;
use App\Mappers\Helpers\FigshareFilesHelper;
use App\Mappers\Helpers\RoCrateHelper;
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
        $dataPublication->msl_source = '12345';

        $this->mock('overload:' . FigshareFilesHelper::class, function(MockInterface $mock) {
            $mock->shouldReceive('getRoCrate')
                ->once()
                ->andReturn(json_decode(file_get_contents(base_path('/tests/MockData/Figshare/rocrate.txt')), true));
        });

        $this->mock('overload:' . RoCrateHelper::class, function(MockInterface $mock) {
            $mock->shouldReceive('getFiles')
                ->once()
                ->andReturn(
                    [
                        [
                            '@id' => '8ddd1afc-9f74-4ac6-9e2f-61592909c9e8',
                            '@type' => 'File',
                            'name' => 'DATA True Triax.zip',
                            'contentSize' => '775931560',
                            'contentUrl' => 'https://data.4tu.nl/file/38262dab-3eea-4991-87a0-1b7e849efbfb/8ddd1afc-9f74-4ac6-9e2f-61592909c9e8',
                        ]
                    ]
                );
        });

        $figshareMapper = new FigshareFileMapper;
        $dataPublication = $figshareMapper->map($dataPublication);

        //dd($dataPublication);

        $this->assertEquals($dataPublication->msl_files[0]->msl_file_name, 'DATA True Triax.zip');
        $this->assertEquals($dataPublication->msl_files[0]->msl_download_link, 'https://data.4tu.nl/file/38262dab-3eea-4991-87a0-1b7e849efbfb/8ddd1afc-9f74-4ac6-9e2f-61592909c9e8');
        $this->assertEquals($dataPublication->msl_files[0]->msl_extension, 'zip');
        $this->assertEquals($dataPublication->msl_files[0]->msl_is_folder, false);
    }
}
