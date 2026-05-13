<?php

namespace Tests\Feature\Models\Seeders;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederSeedRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_seeds_relation(): void
    {
        $seeder = Seeder::create([
            'name' => 'Seeder',
            'description' => 'Seeder description',
            'type' => 'organization',
            'options' => [],
        ]);

        $seed = $seeder->seeds()->create([]);

        $this->assertCount(1, $seeder->fresh()->seeds);
        $this->assertTrue($seeder->seeds->contains($seed));
        $this->assertSame($seeder->id, $seed->seeder->id);
    }
}
