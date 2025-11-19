<?php

namespace Tests\Feature\Api\V2;

use App\Http\Controllers\API\V2\FacilityController;
use App\Models\Laboratory;
use App\Models\LaboratoryEquipment;
use App\Models\LaboratoryEquipmentAddon;
use App\Models\LaboratoryOrganization;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;
use Tests\TestCase;

class FacilityControllerTest extends TestCase
{
    public function test_all_success_results(): void
    {

        $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/facilities.json'));

        $guzzleClient = $this->getCkanClientMock($response);

        $mockLab = $this->getEloquentModelsMock();

        $this->app->instance(Laboratory::class, $mockLab);

        $this->app->bind(FacilityController::class, function ($app) use ($guzzleClient) {
            return new FacilityController($guzzleClient, $app->make(Laboratory::class));
        });

        $response = $this->get('api/v2/facilities/all?title="HelLabs - Geophysical laboratory"');

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')->where('messages', [])
                ->where('meta.totalCount', 117)
                ->where('meta.resultCount', 1)
                ->where('meta.limit', 10)
                ->where('meta.offset', 0)
                ->where('links.current_url', config('app.url') . '/api/v2/facilities/all?title=%22HelLabs%20-%20Geophysical%20laboratory%22&offset=0&limit=10')
                ->has(
                    'data.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'HelLabs - Geophysical laboratory')
                        ->where('domain', 'Paleomagnetism')
                        ->where('geojson', [
                            'geometry' => [
                                'coordinates' => [
                                    24.963641,
                                    60.204535,
                                    23,
                                ],
                                'type' => 'Point',
                            ],
                            'properties' => [],
                            'type' => 'Feature',
                        ])->has(
                            'equipment.0',
                            fn(AssertableJson $json) => $json
                                ->where('category', 'Permanent')
                                ->where(
                                    'descriptions.0',
                                    [
                                        'description' => 'cryogenic magnetometer for discrete samples, 2G Model 755 DC,',
                                        'descriptionType' => 'Description',
                                    ]
                                )->has('addOns.0', fn(AssertableJson $json) => $json
                                    ->where('type', 'Detector')
                                    ->where('group', 'EDS detector (Energy Dispersive X-ray Spectroscopy)')
                                    ->where('description.0', [
                                        'description' => 'Bruker XFlash 6-60',
                                        'descriptionType' => 'Description',
                                    ]))->where('name', '2G cryogenic magnetometer')->etc()

                        )->where('organisation', 'Universiteit Utrecht (UU)')

                        ->etc()
                )->etc()
        );
    }

    public function test_paleo_success_results(): void
    {

        $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/facilities.json'));

        $guzzleClient = $this->getCkanClientMock($response);

        $mockLab = $this->getEloquentModelsMock();

        $this->app->instance(Laboratory::class, $mockLab);
        $this->app->bind(FacilityController::class, function ($app) use ($guzzleClient) {
            return new FacilityController($guzzleClient, $app->make(Laboratory::class));
        });

        $response = $this->get('api/v2/facilities/paleo');

        $response->assertStatus(200);
        // Verify response body contents
        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')->where('messages', [])
                ->where('meta.totalCount', 117)
                ->where('meta.resultCount', 1)
                ->where('meta.limit', 10)
                ->where('meta.offset', 0)
                ->where('links.current_url', config('app.url') . '/api/v2/facilities/paleo?offset=0&limit=10')
                ->has(
                    'data.0',
                    fn(AssertableJson $json) => $json
                        ->where('title', 'HelLabs - Geophysical laboratory')
                        ->where('domain', 'Paleomagnetism')
                        ->where('geojson', [
                            'geometry' => [
                                'coordinates' => [
                                    24.963641,
                                    60.204535,
                                    23,
                                ],
                                'type' => 'Point',
                            ],
                            'properties' => [],
                            'type' => 'Feature',
                        ])->has(
                            'equipment.0',
                            fn(AssertableJson $json) => $json
                                ->where('category', 'Permanent')
                                ->where(
                                    'descriptions.0',
                                    [
                                        'description' => 'cryogenic magnetometer for discrete samples, 2G Model 755 DC,',
                                        'descriptionType' => 'Description',
                                    ]
                                )->has('addOns.0', fn(AssertableJson $json) => $json
                                    ->where('type', 'Detector')
                                    ->where('group', 'EDS detector (Energy Dispersive X-ray Spectroscopy)')
                                    ->where('description.0', [
                                        'description' => 'Bruker XFlash 6-60',
                                        'descriptionType' => 'Description',
                                    ]))->where('name', '2G cryogenic magnetometer')->etc()

                        )->where('organisation', 'Universiteit Utrecht (UU)')

                        ->etc()
                )->etc()
        );
    }

    /**
     * Test /all endpoint with error received from CKAN
     */
    public function test_all_error_ckan(): void
    {

        $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V1/package_search_error.txt'));
        $guzzleClient = $this->getCkanClientMock($response);
        $mockLab = $this->getEloquentModelsMock();

        $this->app->instance(Laboratory::class, $mockLab);
        $this->app->bind(FacilityController::class, function ($app) use ($guzzleClient) {
            return new FacilityController($guzzleClient, $app->make(Laboratory::class));
        });
        $response = $this->get('api/v2/facilities/all');

        // Check for 500 status response
        $response->assertStatus(500);

        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
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
        $response = file_get_contents(base_path('/tests/MockData/CkanResponses/V2/facilities.json'));
        $guzzleClient = $this->getCkanClientMock($response);
        $mockLab = $this->getEloquentModelsMock();

        $this->app->instance(Laboratory::class, $mockLab);
        $this->app->bind(FacilityController::class, function ($app) use ($guzzleClient) {
            return new FacilityController($guzzleClient, $app->make(Laboratory::class));
        });

        $response = $this->get('api/v2/facilities/all?limit=a&offset=-1');

        // Check for 500 status response
        $response->assertStatus(422);

        $response->assertJson(
            fn(AssertableJson $json) => $json->has('success')
                ->where('success', false)
                ->where('messages', ['The limit must be an integer.', 'The offset must be at least 0.'])
                ->etc()
        );
    }

    private function getEloquentModelsMock(): Laboratory
    {
        $lab = new Laboratory([
            'id' => 117,
            'laboratory_organization_id' => 64,
            'laboratory_manager_id' => null,
            'fast_id' => 164,
            'msl_identifier' => 'fa7cdfad1a5aaf8370ebeda47a1ff1c3',
            'lab_portal_name' => '',
            'lab_editor_name' => '',
            'msl_identifier_inputstring' => '',
            'original_domain' => '',
            'name' => 'HelLabs - Geophysical laboratory',
            'description' => 'Paleomagnetism, rock magnetism and petrophysics',
            'description_html' => "<p>Paleomagnetism, rock magnetism and petrophysics</p>\n",
            'website' => '',
            'address_street_1' => '',
            'address_street_2' => '',
            'address_postalcode' => '',
            'address_city' => '',
            'address_country_code' => 'FI',
            'latitude' => '60.204535',
            'longitude' => '24.963641',
            'altitude' => '23',
            'external_identifier' => '',
            'fast_domain_id' => 3,
            'fast_domain_name' => 'Paleomagnetism',
            'address_country_name' => 'Finland',
        ]);

        $equipment = new LaboratoryEquipment([
            'id' => 370,
            'fast_id' => 529,
            'laboratory_id' => 117,
            'description' => 'cryogenic magnetometer for discrete samples, 2G Model 755 DC,',
            'description_html' => "<p>cryogenic magnetometer for discrete samples, 2G Model 755 DC,</p>\n",
            'category_name' => 'Permanent',
            'type_name' => 'Magnetometer',
            'domain_name' => 'Paleomagnetism',
            'group_name' => 'Cryogenic Magnetometer',
            'brand' => '2G',
            'website' => '',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'external_identifier' => '',
            'name' => '2G cryogenic magnetometer',
            'keyword_id' => 12947,
        ]);
        $org = new LaboratoryOrganization([
            'id' => 64,
            'fast_id' => 1,
            'name' => 'Universiteit Utrecht (UU)',
            'external_identifier' => 'https://ror.org/04pp8hn57',
            'ror_country' => null,
            'ror_country_code' => null,
            'ror_website' => null,
        ]);
        $addon = new LaboratoryEquipmentAddon([
            'id' => 1,
            'description' => 'Bruker XFlash 6-60',
            'laboratory_equipment_id' => 1,
            'keyword_id' => 12841,
            'type' => 'Detector',
            'group' => 'EDS detector (Energy Dispersive X-ray Spectroscopy)',
            'created_at' => '2025-10-09 12:31:15',
            'updated_at' => '2025-10-09 12:31:15',
        ]);
        $equipment->setRelation('laboratory_equipment_addons', collect([$addon]));
        $lab->setRelation('laboratoryOrganization', $org);

        $lab->setRelation('laboratoryEquipment', collect([$equipment]));
        $builder = \Mockery::mock();
        $builder->shouldReceive('first')
            ->andReturn($lab);

        $mockLab = Mockery::mock(Laboratory::class);
        $mockLab->shouldReceive('where')
            ->with('fast_id', 164)
            ->andReturn($builder);

        return $mockLab;
    }

    private function getCkanClientMock(string $response): Client
    {
        $mock = new MockHandler([
            new Response(200, [], $response),
        ]);
        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
