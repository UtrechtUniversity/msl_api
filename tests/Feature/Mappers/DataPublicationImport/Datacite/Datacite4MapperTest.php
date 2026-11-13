<?php

namespace Feature\Mappers\DataPublicationImport\Datacite;

use App\Faker\DataCite4Faker;
use App\Mappers\DataPublicationImport\Datacite\Datacite4Mapper;
use App\Models\DataPublication\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class Datacite4MapperTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_identifier()
    {
        $dataciteMetadata = (new DataCite4Faker())->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite4Mapper();

        $dataPublication = $mapper->mapIdentifier($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['doi'], $dataPublication->doi);
    }

    public function test_map_titles()
    {
        $dataciteMetadata = (new DataCite4Faker())->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite4Mapper();

        $dataPublication = $mapper->mapTitles($dataciteMetadata, $dataPublication);

        $dataciteTitles = Arr::pluck($dataciteMetadata['data']['attributes']['titles'], 'title');
        $this->assertContains($dataPublication->title, $dataciteTitles);
    }

    public function test_map_descriptions()
    {
        $dataciteMetadata = (new DataCite4Faker())->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite4Mapper();

        $dataPublication = $mapper->mapDescriptions($dataciteMetadata, $dataPublication);

        $dataciteDescriptionTypes = array_unique(Arr::pluck($dataciteMetadata['data']['attributes']['descriptions'], 'descriptionType'));
        $dataciteDescriptionDescriptions = Arr::pluck($dataciteMetadata['data']['attributes']['descriptions'], 'description');

        $this->assertCount(count($dataciteDescriptionTypes), $dataPublication->descriptions);
        $this->assertContains($dataPublication->descriptions->first()->description, $dataciteDescriptionDescriptions);
    }
}
