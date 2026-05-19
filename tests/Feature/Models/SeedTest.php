<?php

namespace Tests\Feature\Models;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_relation(): void
    {
        $seeder = Seeder::create([
            'name' => 'Seeder',
            'description' => 'Seeder description',
            'type' => 'organization',
            'options' => [],
        ]);

        $seed = $seeder->seeds()->create([]);

        $this->assertSame($seeder->id, $seed->seeder->id);
    }

    public function test_creates_relation_for_organization_creates(): void
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

    public function test_creates_relation_for_laboratory_creates(): void
    {
        $seeder = Seeder::create([
            'name' => 'Lab seeder',
            'description' => 'Creates laboratories',
            'type' => 'lab',
            'options' => [],
        ]);

        $seed = $seeder->seeds()->create([]);

        $laboratoryCreate = $seed->creates()->create([
            'laboratory_type' => 'default',
            'laboratory' => ['name' => 'Lab'],
        ]);

        $this->assertCount(1, $seed->fresh()->creates);
        $this->assertTrue($seed->creates->contains($laboratoryCreate));
        $this->assertSame($seed->id, $laboratoryCreate->seed->id);
    }

    public function test_creates_relation_for_equipment_creates(): void
    {
        $seeder = Seeder::create([
            'name' => 'Equipment seeder',
            'description' => 'Creates equipment',
            'type' => 'equipment',
            'options' => [],
        ]);

        $seed = $seeder->seeds()->create([]);

        $equipmentCreate = $seed->creates()->create([
            'equipment_type' => 'default',
            'equipment' => ['name' => 'Device'],
        ]);

        $this->assertCount(1, $seed->fresh()->creates);
        $this->assertTrue($seed->creates->contains($equipmentCreate));
        $this->assertSame($seed->id, $equipmentCreate->seed->id);
    }
}
