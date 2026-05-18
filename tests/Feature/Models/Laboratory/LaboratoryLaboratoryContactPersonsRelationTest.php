<?php

namespace Tests\Feature\Models\Laboratory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Models\Laboratory\Support\CreatesLaboratoryFixtures;
use Tests\TestCase;

class LaboratoryLaboratoryContactPersonsRelationTest extends TestCase
{
    use CreatesLaboratoryFixtures;
    use RefreshDatabase;

    public function test_laboratory_laboratory_contact_persons_relation(): void
    {
        $organization = $this->makeLaboratoryOrganization();
        $manager = $this->makeLaboratoryManager();
        $laboratory = $this->makeLaboratory($organization, $manager);

        $contact = $laboratory->laboratoryContactPersons()->create([
            'email' => 'contact@example.org',
        ]);

        $this->assertCount(1, $laboratory->fresh()->laboratoryContactPersons);
        $this->assertTrue($laboratory->laboratoryContactPersons->contains($contact));
        $this->assertSame($laboratory->id, $contact->laboratory->id);
    }
}
