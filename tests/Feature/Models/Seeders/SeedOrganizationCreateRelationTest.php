<?php

namespace Tests\Feature\Models\Seeders;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedOrganizationCreateRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_creates_organization_create_relation(): void
    {
        $seeder = Seeder::create([
            'name' => 'Org seeder',
            'description' => 'Creates organizations',
            'type' => 'organization',
            'options' => [],
        ]);

        $seed = $seeder->seeds()->create([]);

        $organizationCreate = $seed->creates()->create([
            'organization_type' => 'default',
            'organization' => ['name' => 'Org'],
        ]);

        $this->assertCount(1, $seed->fresh()->creates);
        $this->assertTrue($seed->creates->contains($organizationCreate));
        $this->assertSame($seed->id, $organizationCreate->seed->id);
    }
}
