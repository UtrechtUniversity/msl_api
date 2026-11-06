<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\File;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $file = File::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $file->dataPublication);
    }

    public function test_parent_relation()
    {
        $file = File::factory()
            ->for(File::factory(), 'parent')
            ->create();

        $this->assertInstanceOf(File::class, $file->parent);
    }
}
