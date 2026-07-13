<?php

namespace Feature\Models\Laboratory;

use App\Models\Laboratory\Laboratory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryKeywordTest extends TestCase
{
    use RefreshDatabase;

    public function test_laboratory_relation(): void
    {
        $laboratory = Laboratory::create([
            'msl_identifier' => 'test_lab_1',
            'lab_portal_name' => 'Portal',
            'lab_editor_name' => 'Editor',
            'msl_identifier_inputstring' => 'test_lab_1',
            'original_domain' => 'example.org',
            'name' => 'Test Lab',
            'description' => 'Description',
            'description_html' => '<p>Description</p>',
            'website' => 'https://lab.example.org',
            'address_street_1' => '1 Lab Rd',
            'address_street_2' => '',
            'address_postalcode' => '2000BB',
            'address_city' => 'Utrecht',
            'address_country_code' => 'NL',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '0',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $laboratoryKeyword = $laboratory->laboratoryKeywords()->createQuietly([
            'value' => 'geology',
            'uri' => 'https://example.org/kw/geology',
        ]);

        $this->assertInstanceOf(Laboratory::class, $laboratoryKeyword->laboratory);
        $this->assertSame($laboratory->id, $laboratoryKeyword->laboratory->id);
    }
}
