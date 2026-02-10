<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\API\GeoJsonDataPublicationsController;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GeoJsonDataPublicationControllerTest extends TestCase
{
    private function bindControllerToApp(string $fileContents): void
    {

        $path = base_path($fileContents);
        $this->app->bind(GeoJsonDataPublicationsController::class, function () use ($path) {
            $response = file_get_contents($path);

            $mock = new MockHandler([
                new Response(200, [], $response),
            ]);

            $handler = HandlerStack::create($mock);

            return new GeoJsonDataPublicationsController(new Client(['handler' => $handler]));
        });
    }

    public function test_all_geojson_success_results(): void
    {
        $this->bindControllerToApp(fileContents: '/tests/MockData/CkanResponses/V2/datapublications_all.json');

        // Retrieve response from API
        $response = $this->get('/api/geoJsonDataPublications?boundingBox=%5B0%2C0%2C180%2C90%5D');
        // Check for 200 status response
        $response->assertStatus(200);
        // Verify response body contents
        $response->assertJson(
            fn (AssertableJson $json) => $json->has('success')->where('messages', [])
                ->where('meta.totalCount', 3517)
                ->where('meta.resultCount', 10)
                ->where('meta.limit', 10)
                ->where('meta.offset', 0)
                ->where('links.current_url', config('app.url').'/api/geoJsonDataPublications?boundingBox=%5B0%2C0%2C180%2C90%5D&offset=0&limit=10')
                ->has(
                    'data.data_publications.9',
                    fn (AssertableJson $json) => $json->where('title', 'Paleomagnetic evidence for the existence of the geomagnetic field 3.5 Ga ago (Dataset)')
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
                            )->where('downloadLink', route('file-download', ['id' => '86dcccf60dc76092265994e2c6b50470', 'url' => base64_encode('https://earthref.org/MagIC/download/20541/magic_contribution_20541.txt')]))->where('isFolder', false)->etc()
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
                        )->etc()
                )->has(
                    'data.geojson',
                    fn (AssertableJson $json) => $json->has(
                        'exclusive.0',
                        fn (AssertableJson $json) => $json->where('feature.geometry.type', 'Polygon')->etc()
                    )->count('exclusive', 68)->has(
                        'exclusive.9',
                        fn (AssertableJson $json) => $json->where('feature.geometry.type', 'Polygon')->etc()
                    )
                        ->has(
                            'exclusive.10',
                            fn (AssertableJson $json) => $json->where('feature.geometry.type', 'Point')->etc()
                        )
                        ->has(
                            'exclusive.67',
                            fn (AssertableJson $json) => $json->where('feature.geometry.type', 'Point')->etc()
                        )
                        ->has('inclusive')
                )

        );
    }
}
