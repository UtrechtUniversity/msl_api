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
        if(count($dataPublication->descriptions) > 0) {
            $this->assertContains($dataPublication->descriptions->first()->description, $dataciteDescriptionDescriptions);
        }
    }

    public function test_map_rights()
    {
        $dataciteMetadata = (new DataCite4Faker())->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite4Mapper();

        $dataPublication = $mapper->mapRights($dataciteMetadata, $dataPublication);

        $dataciteRights = Arr::pluck($dataciteMetadata['data']['attributes']['rightsList'], 'rights');

        $this->assertCount(count($dataciteMetadata['data']['attributes']['rightsList']), $dataPublication->rights);
        if(count($dataPublication->rights) > 0) {
            $this->assertContains($dataPublication->rights->first()->right, $dataciteRights);
        }
    }

    public function test_publication_year_mapping()
    {
        $dataciteMetadata = (new DataCite4Faker())->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite4Mapper();

        $dataPublication = $mapper->mapPublicationYear($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['publicationYear'], $dataPublication->publication_year);
    }
}
