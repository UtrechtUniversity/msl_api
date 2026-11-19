<?php

namespace Tests\Feature\Mappers\DataPublicationImport\Datacite;

use App\Faker\DataCite3Faker;
use App\Mappers\DataPublicationImport\Datacite\Datacite3Mapper;
use App\Models\DataPublication\DataPublication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class Datacite3MapperTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_identifier()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapIdentifier($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['doi'], $dataPublication->doi);
    }

    public function test_map_titles()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapTitles($dataciteMetadata, $dataPublication);

        $dataciteTitles = Arr::pluck($dataciteMetadata['data']['attributes']['titles'], 'title');
        $this->assertContains($dataPublication->title, $dataciteTitles);
    }

    public function test_map_descriptions()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapDescriptions($dataciteMetadata, $dataPublication);

        $dataciteDescriptionTypes = array_unique(Arr::pluck($dataciteMetadata['data']['attributes']['descriptions'], 'descriptionType'));
        $dataciteDescriptionDescriptions = Arr::pluck($dataciteMetadata['data']['attributes']['descriptions'], 'description');

        $this->assertCount(count($dataciteDescriptionTypes), $dataPublication->descriptions);

        foreach($dataPublication->descriptions as $description)
        {
            $this->assertContains($description->description, $dataciteDescriptionDescriptions);
            $this->assertContains($description->type, $dataciteDescriptionTypes);
        }
    }

    public function test_map_rights()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapRights($dataciteMetadata, $dataPublication);

        $dataciteRights = $dataciteMetadata['data']['attributes']['rightsList'];

        $this->assertCount(count($dataciteRights), $dataPublication->rights);

        for ($i = 0; $i < count($dataPublication->rights); $i++) {
            $this->assertEquals($dataPublication->rights[$i]->right, $dataciteRights[$i]['rights']);
            $this->assertEquals($dataPublication->rights[$i]->uri, $dataciteRights[$i]['rightsUri'] ?? '');
            $this->assertEquals($dataPublication->rights[$i]->identifier, $dataciteRights[$i]['rightsIdentifier'] ?? '');
            $this->assertEquals($dataPublication->rights[$i]->identifier_scheme, $dataciteRights[$i]['rightsIdentifierScheme'] ?? '');
            $this->assertEquals($dataPublication->rights[$i]->identifier_scheme_uri, $dataciteRights[$i]['schemeUri'] ?? '');
        }
    }

    public function test_publication_year_mapping()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapPublicationYear($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['publicationYear'], $dataPublication->publication_year);
    }

    public function test_map_alternate_identifiers()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapAlternateIdentifiers($dataciteMetadata, $dataPublication);

        $dataciteAlternateIdentifiers = $dataciteMetadata['data']['attributes']['alternateIdentifiers'];

        $this->assertCount(count($dataciteAlternateIdentifiers), $dataPublication->alternateIdentifiers);

        for ($i = 0; $i < count($dataPublication->alternateIdentifiers); $i++) {
            $this->assertEquals($dataPublication->alternateIdentifiers[$i]->identifier, $dataciteAlternateIdentifiers[$i]['alternateIdentifier']);
            $this->assertEquals($dataPublication->alternateIdentifiers[$i]->type, $dataciteAlternateIdentifiers[$i]['alternateIdentifierType'] ?? '');
        }
    }

    public function test_map_related_identifiers()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapRelatedIdentifiers($dataciteMetadata, $dataPublication);

        $dataciteRelatedIdentifiers = $dataciteMetadata['data']['attributes']['relatedIdentifiers'];

        $this->assertCount(count($dataciteRelatedIdentifiers), $dataPublication->relatedIdentifiers);

        for ($i = 0; $i < count($dataPublication->relatedIdentifiers); $i++) {
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->identifier, $dataciteRelatedIdentifiers[$i]['relatedIdentifier']);
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->type, $dataciteRelatedIdentifiers[$i]['relatedIdentifierType']);
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->relation_type, $dataciteRelatedIdentifiers[$i]['relationType']);
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->metadata_scheme, $dataciteRelatedIdentifiers[$i]['relatedMetadataScheme'] ?? '');
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->metadata_scheme_uri, $dataciteRelatedIdentifiers[$i]['schemeURI'] ?? '');
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->metadata_scheme_type, $dataciteRelatedIdentifiers[$i]['schemeType'] ?? '');
            $this->assertEquals($dataPublication->relatedIdentifiers[$i]->resource_type_general, $dataciteRelatedIdentifiers[$i]['resourceTypeGeneral'] ?? '');
        }
    }

    public function test_map_url()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapUrl($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['url'], $dataPublication->source_landing_page_url);
    }

    public function test_map_language()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapLanguage($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['language'], $dataPublication->language);
    }

    public function test_map_dates()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = DataPublication::factory()->create();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapDates($dataciteMetadata, $dataPublication);

        $dataciteDates = $dataciteMetadata['data']['attributes']['dates'];

        $this->assertCount(count($dataciteDates), $dataPublication->dates);

        for ($i = 0; $i < count($dataPublication->dates); $i++) {
            $this->assertEquals($dataPublication->dates[$i]->date, $dataciteDates[$i]['date']);
            $this->assertEquals($dataPublication->dates[$i]->type, $dataciteDates[$i]['dateType'] ?? '');
            $this->assertEquals($dataPublication->dates[$i]->information, $dataciteDates[$i]['dateInformation'] ?? '');
        }
    }

    public function test_map_publishers()
    {
        $dataciteMetadata = new DataCite3Faker()->populate()->toArray();
        $dataPublication = new DataPublication();
        $mapper = new Datacite3Mapper();

        $dataPublication = $mapper->mapPublishers($dataciteMetadata, $dataPublication);

        $this->assertEquals($dataciteMetadata['data']['attributes']['publisher'], $dataPublication->publisher);
    }
}
