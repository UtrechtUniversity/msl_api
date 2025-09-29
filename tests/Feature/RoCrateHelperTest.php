<?php

namespace Tests\Feature;

use App\Mappers\Helpers\RoCrateHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoCrateHelperTest extends TestCase
{
    /**
     * Extract files from ro crate single file
     */
    public function test_get_files_from_ro_crate_single(): void
    {
        $roCrate = json_decode(file_get_contents(base_path('/tests/MockData/Figshare/rocrate.txt')), true);

        $roCrateHelper = new RoCrateHelper();

        $files = $roCrateHelper->getFiles($roCrate);

        $this->assertEquals('8ddd1afc-9f74-4ac6-9e2f-61592909c9e8', $files[0]['@id']);
        $this->assertEquals('File', $files[0]['@type']);
        $this->assertEquals('DATA True Triax.zip', $files[0]['name']);
        $this->assertEquals('775931560', $files[0]['contentSize']);
        $this->assertEquals('https://data.4tu.nl/file/38262dab-3eea-4991-87a0-1b7e849efbfb/8ddd1afc-9f74-4ac6-9e2f-61592909c9e8', $files[0]['contentUrl']);
    }

    /**
     * Extract files from ro crate single file
     */
    public function test_get_files_from_ro_crate_multiple(): void
    {
        $roCrate = json_decode(file_get_contents(base_path('/tests/MockData/Figshare/rocrate_multiple_files.txt')), true);

        $roCrateHelper = new RoCrateHelper();

        $files = $roCrateHelper->getFiles($roCrate);

        $this->assertEquals('058a0064-1a0a-46c2-ba65-2b3eb50a5c98', $files[0]['@id']);
        $this->assertEquals('File', $files[0]['@type']);
        $this->assertEquals('Table S1_Titanite_dates.xlsx', $files[0]['name']);
        $this->assertEquals('491602', $files[0]['contentSize']);
        $this->assertEquals('https://data.4tu.nl/file/2d498c49-622b-4049-aadd-5b9bb28d2c39/058a0064-1a0a-46c2-ba65-2b3eb50a5c98', $files[0]['contentUrl']);

        $this->assertEquals('e3f8dad9-3cc7-451e-9594-ab2ffd0f2e72', $files[1]['@id']);
        $this->assertEquals('File', $files[1]['@type']);
        $this->assertEquals('Table S2_Zr-in-Titanite temperature.xlsx', $files[1]['name']);
        $this->assertEquals('35268', $files[1]['contentSize']);
        $this->assertEquals('https://data.4tu.nl/file/2d498c49-622b-4049-aadd-5b9bb28d2c39/e3f8dad9-3cc7-451e-9594-ab2ffd0f2e72', $files[1]['contentUrl']);
    }
}
