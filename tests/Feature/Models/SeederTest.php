<?php

namespace Tests\Feature\Models;

use App\Models\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_relation(): void
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
