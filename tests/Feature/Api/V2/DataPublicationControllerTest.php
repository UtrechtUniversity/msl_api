<?php

namespace Tests\Feature\Api\V2;

use App\Http\Controllers\API\V2\DataPublicationController;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DataPublicationControllerTest extends TestCase
{
    public function test_all_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into DataPublicationController constructor to work with mocked results from CKAN
        $this->app->bind(DataPublicationController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/datapublications_all.json'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new DataPublicationController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('api/v2/datapublications/all');

        // Check for 200 status response
        $response->assertStatus(200);
        // Verify response body contents
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('success')->where('messages', [])
                ->where('meta.totalCount', 3517)
                ->where('meta.resultCount', 10)
                ->where('meta.limit', 10)
                ->where('meta.offset', 0)
                ->where('links.current_url', config('app.url').'/api/v2/datapublications/all?offset=0&limit=10')
                ->has(
                    'data.9',
                    fn (AssertableJson $json) => $json
                        ->where('title', 'Paleomagnetic evidence for the existence of the geomagnetic field 3.5 Ga ago (Dataset)')
                        ->where('name', '86dcccf60dc76092265994e2c6b50470')
                        ->where('portalLink', config('app.url').'/data-publication/86dcccf60dc76092265994e2c6b50470')
                        ->where('doi', '10.7288/v4/magic/20541')
                        ->where('source', 'https://earthref.org/MagIC/20541')
                        ->where('publisher', 'Magnetics Information Consortium (MagIC)')
                        ->has(
                            'descriptions.0',
                            fn (AssertableJson $json) => $json->where(
                                'description',
                                'Paleomagnetic, rock magnetic, or geomagnetic data found in the MagIC data repository from a paper titled: Paleomagnetic evidence for the existence of the geomagnetic field 3.5 Ga ago'
                            )->where('descriptionType', 'Other')
                        )->has(
                            'creators.0',
                            fn (AssertableJson $json) => $json->where(
                                'name',
                                'M. W. McElhinny',
                            )->where(
                                'fullName',
                                'M. W. McElhinny',
                            )->where('nameType', '')->etc()
                        )->has(
                            'creators.1',
                            fn (AssertableJson $json) => $json->where(
                                'name',
                                'W. E. Senanayake',
                            )->where(
                                'fullName',
                                'W. E. Senanayake',
                            )->where('nameType', '')->etc()
                        )->count('materials', 3)
                        ->has(
                            'contributors.0',
                            fn (AssertableJson $json) => $json->where(
                                'name',
                                'Not Determined (For Legacy Datasets Only)',
                            )->where('contributorType', 'HostingInstitution')->etc()
                        )->count('researchAspects', 0)
                        ->has(
                            'files.0',
                            fn (AssertableJson $json) => $json->where(
                                'fileName',
                                'magic_contribution_20541.txt',
                            )->where('downloadLink', 'https://earthref.org/MagIC/download/20541/magic_contribution_20541.txt')->where('isFolder', false)->etc()
                        )
                        ->where('resource_type_general', 'Dataset')
                        ->has('publication_year')
                        ->where('language', 'en')
                        ->where('publisher', 'Magnetics Information Consortium (MagIC)')
                        ->where('citation', 'M. W. McElhinny, &amp; W. E. Senanayake. (2025). <i>Paleomagnetic evidence for the existence of the geomagnetic field 3.5 Ga ago (Dataset)</i> (Version 3) [Data set]. Magnetics Information Consortium (MagIC). https://doi.org/10.7288/V4/MAGIC/20541')
                        ->has(
                            'rightsList.0',
                            fn (AssertableJson $json) => $json->where(
                                'rights',
                                'Creative Commons Attribution 4.0 International',
                            )->where('rightsIdentifierScheme', 'SPDX')->etc()
                        )->count('dates', 2)
                        ->has(
                            'relatedIdentifiers.0',
                            fn (AssertableJson $json) => $json->where(
                                'relatedIdentifierType',
                                'DOI',
                            )->where('relatedIdentifier', '10.1029/jb085ib07p03523')->etc()
                        )->count('subjects', 7)
                        ->has(
                            'subjects.3',
                            fn (AssertableJson $json) => $json->where(
                                'subject',
                                'Basalt',
                            )->count('EPOS_Uris', 1)->etc()
                        )->has(
                            'geojson',
                            fn (AssertableJson $json) => $json->where(
                                'type',
                                'FeatureCollection',
                            )->count('features', 51)->etc()
                        )->etc()
                )
        );
    }

    public function test_paleos_success_results(): void
    {
        // Inject GuzzleCLient with Mockhandler into DataPublicationController constructor to work with mocked results from CKAN
        $this->app->bind(DataPublicationController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/datapublications_paleo.json'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new DataPublicationController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('api/v2/datapublications/paleo?offset=2&limit=8');
        // dd($response);

        // Check for 200 status response
        $response->assertStatus(200);
        // Verify response body contents

        $response->assertJson(
            fn (AssertableJson $json) => $json->has('success')->where('messages', [])
                ->where('meta.totalCount', 56)
                ->where('meta.resultCount', 8)
                ->where('meta.limit', 8)
                ->where('meta.offset', 2)
                ->where('links.current_url', config('app.url').'/api/v2/datapublications/paleo?offset=2&limit=8')
                ->has(
                    'data.0',
                    fn (AssertableJson $json) => $json
                        ->where('title', 'Carbon dioxide flux and isotopic composition from sedimentary rock weathering, Draix-Bleone Critical Zone Observatory, France')
                        ->where('name', 'b30393d60e27dbd8bb06391e0c7341c3')
                        ->where('portalLink', config('app.url').'/data-publication/b30393d60e27dbd8bb06391e0c7341c3')
                        ->where('doi', '10.5285/efc082aa-5c2b-4afb-aec8-344aebaea653')
                        ->where('source', 'https://www2.bgs.ac.uk/nationalgeosciencedatacentre/citedData/catalogue/efc082aa-5c2b-4afb-aec8-344aebaea653.html')
                        ->where('publisher', 'NERC EDS National Geoscience Data Centre')

                        ->has(
                            'descriptions.0',
                            fn (AssertableJson $json) => $json->where('descriptionType', 'Abstract')->etc()
                        )->count(
                            'creators',
                            4
                        )->count('materials', 11)
                        ->count('researchAspects', 0)
                        ->where('resource_type_general', 'Dataset')
                        ->has('publication_year')
                        ->where('language', '')
                        ->where('publisher', 'NERC EDS National Geoscience Data Centre')
                        ->where('citation', 'Soulet, G., Hilton, R. G., Garnett, M. H., &amp; Klotz, S. (2021). <i>Carbon dioxide flux and isotopic composition from sedimentary rock weathering, Draix-Bleone Critical Zone Observatory, France</i> [Data set]. NERC EDS National Geoscience Data Centre. https://doi.org/10.5285/EFC082AA-5C2B-4AFB-AEC8-344AEBAEA653')
                        ->count('rightsList', 0)->count('dates', 1)
                        ->count('subjects', 9)
                        ->has(
                            'subjects.6',
                            fn (AssertableJson $json) => $json->where(
                                'subject',
                                'organic carbon',
                            )->where('EPOS_Uris', ['https://epos-msl.uu.nl/voc/geochemistry/1.3/measured_property-carbon_c-organic_carbon'])->etc()
                        )->has(
                            'geojson',
                            fn (AssertableJson $json) => $json->where(
                                'type',
                                'FeatureCollection',
                            )->where(
                                'features',
                                [
                                    [
                                        'geometry' => [
                                            'coordinates' => [
                                                0 => 6.3628,
                                                1 => 44.1406,
                                            ],
                                            'type' => 'Point',
                                        ],
                                        'properties' => [
                                            'name' => 'Draix, France',
                                        ],
                                        'type' => 'Feature',
                                    ],
                                ]
                            )
                        )->etc()
                )
        );
    }

    /**
     * Test /all endpoint with error received from CKAN
     */
    public function test_all_error_ckan(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(DataPublicationController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_error.txt'));

            $mock = new MockHandler([
                new Response(400, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new DataPublicationController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('api/v2/datapublications/all');

        // Check for 500 status response
        $response->assertStatus(500);

        // Verify response body contents
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('messages', ['Error received from CKAN api.'])
                ->etc()
        );
    }

    /**
     * Test /all endpoint with validation errors
     */
    public function test_all_error_validation(): void
    {
        // Inject GuzzleCLient with Mockhandler into APIController constructor to work with mocked results from CKAN
        $this->app->bind(DataPublicationController::class, function ($app) {
            $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/datapublications_all.json'));

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new DataPublicationController(new Client(['handler' => $handler]));
        });

        // Retrieve response from API
        $response = $this->get('api/v2/datapublications/all?limit=a&offset=-1');

        // Check for 500 status response
        $response->assertStatus(422);

        // Verify response body contents
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('messages', ['The limit must be an integer.', 'The offset must be at least 0.'])
                ->etc()
        );
    }
}
