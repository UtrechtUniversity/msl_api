<?php

namespace Tests\Feature\Models;

use App\Models\Seed;
use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_relation(): void
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

        $this->assertInstanceOf(Seed::class, $equipmentCreate->seed);
        $this->assertSame($seed->id, $equipmentCreate->seed->id);
    }
}
