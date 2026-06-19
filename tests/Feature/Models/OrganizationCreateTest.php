<?php

namespace Tests\Feature\Models;

use App\Models\Seed;
use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_relation(): void
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

        $this->assertInstanceOf(Seed::class, $organizationCreate->seed);
        $this->assertSame($seed->id, $organizationCreate->seed->id);
    }
}
