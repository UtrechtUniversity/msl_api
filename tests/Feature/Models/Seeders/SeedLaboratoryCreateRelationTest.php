<?php

namespace Tests\Feature\Models\Seeders;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeedLaboratoryCreateRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_creates_laboratory_create_relation(): void
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
}
