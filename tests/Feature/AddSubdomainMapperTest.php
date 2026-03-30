<?php

namespace Tests\Feature;

use App\Enums\DataPublicationSubDomain;
use App\Mappers\Additional\AddSubdomainMapper;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use Tests\TestCase;

class AddSubdomainMapperTest extends TestCase
{
    public function test_map(): void
    {
        $datapublication = new DataPublication;

        $magicFileMapper = new AddSubdomainMapper(['subdomains' => [DataPublicationSubDomain::ROCK_PHYSICS->value, DataPublicationSubDomain::MICROSCOPY->value]]);

        $datapublication = $magicFileMapper->map($datapublication, new SourceDataset);

        $this->assertEquals($datapublication->msl_subdomains[0]['msl_subdomain'], DataPublicationSubDomain::ROCK_PHYSICS->value);
        $this->assertEquals($datapublication->msl_subdomains_original[1]['msl_subdomain_original'], DataPublicationSubDomain::MICROSCOPY->value);
        $this->assertEquals($datapublication->msl_subdomains_interpreted[1]['msl_subdomain_interpreted'], DataPublicationSubDomain::MICROSCOPY->value);

        $this->assertTrue($datapublication->msl_has_microscopy_original);
        $this->assertTrue($datapublication->msl_has_microscopy);

        $this->assertTrue($datapublication->msl_has_rockphysic);
        $this->assertTrue($datapublication->msl_has_rockphysic_original);
    }
}
