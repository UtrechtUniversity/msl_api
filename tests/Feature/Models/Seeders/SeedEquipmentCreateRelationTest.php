<?php

namespace Tests\Feature\Models\Seeders;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedEquipmentCreateRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_creates_equipment_create_relation(): void
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
