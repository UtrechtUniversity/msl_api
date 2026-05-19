<?php

namespace Tests\Feature\Models;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoryCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_relation(): void
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

        $this->assertSame($seed->id, $laboratoryCreate->seed->id);
    }
}
