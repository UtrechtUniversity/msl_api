<?php

namespace Feature\Models\Laboratory;

use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;
use App\Models\LaboratoryUpdateGroupFast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Scout\Jobs\MakeSearchable;
use Laravel\Scout\Jobs\RemoveFromSearch;
use Tests\TestCase;

class LaboratoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_cascading_delete(): void
    {
        Laboratory::withoutSyncingToSearch(function () {
            $laboratory = Laboratory::createQuietly([
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
                'altitude' => '',
                'external_identifier' => 'ext-lab-1',
                'fast_domain_name' => 'domain',
            ]);

            $contact = $laboratory->laboratoryContactPersons()->createQuietly([
                'email' => 'contact@example.org',
            ]);

            $equipment = $laboratory->laboratoryEquipment()->createQuietly([
                'description' => 'Equipment description',
                'description_html' => '<p>Equipment description</p>',
                'category_name' => 'cat',
                'type_name' => 'type',
                'domain_name' => 'domain',
                'group_name' => 'group',
                'brand' => 'brand',
                'website' => 'https://eq.example.org',
                'latitude' => '',
                'longitude' => '',
                'altitude' => '',
                'external_identifier' => 'eq-1',
            ]);

            $laboratoryKeyword = $laboratory->laboratoryKeywords()->createQuietly([
                'value' => 'geology',
                'uri' => 'https://example.org/kw/geology',
            ]);

            $laboratory->delete();

            $this->assertModelMissing($contact);
            $this->assertModelMissing($equipment);
            $this->assertModelMissing($laboratoryKeyword);
        });
    }

    public function test_create_dispatches_scout_job(): void
    {
        Queue::fake();

        $organization = LaboratoryOrganization::createQuietly([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);

        $laboratory = Laboratory::create([
            'laboratory_organization_id' => $organization->id,
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        Queue::assertPushed(MakeSearchable::class);
    }

    public function test_update_dispatches_scout_job(): void
    {
        Queue::fake();

        $organization = LaboratoryOrganization::createQuietly([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);

        $laboratory = Laboratory::createQuietly([
            'laboratory_organization_id' => $organization->id,
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $laboratory->touch();

        Queue::assertPushed(MakeSearchable::class);
    }

    public function test_delete_dispatches_scout_job(): void
    {
        Queue::fake();

        $organization = LaboratoryOrganization::createQuietly([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);

        $laboratory = Laboratory::createQuietly([
            'laboratory_organization_id' => $organization->id,
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $laboratory->delete();

        Queue::assertPushed(RemoveFromSearch::class);
    }
    public function test_laboratory_organization_relation(): void
    {
        $organization = LaboratoryOrganization::createQuietly([
            'fast_id' => 1,
            'name' => 'Test organization',
            'external_identifier' => 'ext-org-1',
        ]);

        $laboratory = Laboratory::createQuietly([
            'laboratory_organization_id' => $organization->id,
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $this->assertInstanceOf(LaboratoryOrganization::class, $laboratory->laboratoryOrganization);
        $this->assertSame($organization->id, $laboratory->laboratoryOrganization->id);
    }

    public function test_laboratory_contact_persons_relation(): void
    {
        $laboratory = Laboratory::createQuietly([
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $contact = $laboratory->laboratoryContactPersons()->createQuietly([
            'email' => 'contact@example.org',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryContactPersons);
        $this->assertTrue($laboratory->laboratoryContactPersons->contains($contact));
    }

    public function test_laboratory_manager_relation(): void
    {
        $manager = LaboratoryManager::createQuietly([
            'email' => 'manager@example.org',
            'first_name' => 'Pat',
            'last_name' => 'Lee',
            'orcid' => '0000-0002-1825-0097',
            'address_street_1' => '1 Main St',
            'address_street_2' => '',
            'address_postalcode' => '1000AA',
            'address_city' => 'Amsterdam',
            'address_country_code' => 'NL',
            'address_country_name' => 'Netherlands',
            'affiliation_fast_id' => 1,
            'nationality_code' => 'NL',
            'nationality_name' => 'Dutch',
        ]);

        $laboratory = Laboratory::createQuietly([
            'laboratory_manager_id' => $manager->id,
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $this->assertInstanceOf(LaboratoryManager::class, $laboratory->laboratoryManager);
        $this->assertSame($manager->id, $laboratory->laboratoryManager->id);
    }

    public function test_laboratory_equipment_relation(): void
    {
        $laboratory = Laboratory::createQuietly([
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $equipment = $laboratory->laboratoryEquipment()->createQuietly([
            'description' => 'Equipment description',
            'description_html' => '<p>Equipment description</p>',
            'category_name' => 'cat',
            'type_name' => 'type',
            'domain_name' => 'domain',
            'group_name' => 'group',
            'brand' => 'brand',
            'website' => 'https://eq.example.org',
            'latitude' => '',
            'longitude' => '',
            'altitude' => '',
            'external_identifier' => 'eq-1',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryEquipment);
        $this->assertTrue($laboratory->laboratoryEquipment->contains($equipment));
    }

    public function test_laboratory_keywords_relation(): void
    {
        $laboratory = Laboratory::createQuietly([
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $laboratoryKeyword = $laboratory->laboratoryKeywords()->createQuietly([
            'value' => 'geology',
            'uri' => 'https://example.org/kw/geology',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryKeywords);
        $this->assertTrue($laboratory->laboratoryKeywords->contains($laboratoryKeyword));
    }

    public function test_laboratory_updates_fast_relation(): void
    {
        $laboratory = Laboratory::createQuietly([
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
            'altitude' => '',
            'external_identifier' => 'ext-lab-1',
            'fast_domain_name' => 'domain',
        ]);

        $group = LaboratoryUpdateGroupFast::create([]);

        $update = $laboratory->laboratoryUpdatesFast()->create([
            'laboratory_update_group_fast_id' => $group->id,
            'response_code' => 200,
            'source_laboratory_data' => '{}',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryUpdatesFast);
        $this->assertTrue($laboratory->laboratoryUpdatesFast->contains($update));
    }
}
