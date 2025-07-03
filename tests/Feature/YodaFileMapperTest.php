<?php

namespace Tests\Feature;

use App\Mappers\Additional\YodaFileMapper;
use App\Mappers\Helpers\YodaDownloadHelper;
use App\Models\Ckan\DataPublication;
use Mockery\MockInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\PreserveGlobalState;

class YodaFileMapperTest extends TestCase
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

        $this->mock('overload:' . YodaDownloadHelper::class, function(MockInterface $mock) {
            $mock->shouldReceive('getFileList')
                ->once()
                ->andReturn(
                    [
                        [
                            'fileName' => 'Data_explanation.pdf',
                            'downloadLink' => 'https://geo.public.data.uu.nl:443/vault-seismic-slip-pulse-experiments/research-seismic-slip-pulse-experiments[1618835278]/original/Data_explanation.pdf',
                            'extension' => 'pdf',
                            'isFolder' => false,
                        ]
                    ]
                );
        });

        $yodaFileMapper = new YodaFileMapper;
        $dataPublication = $yodaFileMapper->map($dataPublication);

        $this->assertEquals($dataPublication->msl_files[0]->msl_file_name, 'Data_explanation.pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_download_link, 'https://geo.public.data.uu.nl:443/vault-seismic-slip-pulse-experiments/research-seismic-slip-pulse-experiments[1618835278]/original/Data_explanation.pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_extension, 'pdf');
        $this->assertEquals($dataPublication->msl_files[0]->msl_is_folder, false);
    }
}
