<?php

namespace Tests\Feature\Models\DataPublication\DataPublications;

use App\Models\DataPublication\DataPublication;
use App\Models\DataPublication\Subject;
use App\Models\Keyword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_data_publication_relation()
    {
        $subject = Subject::factory()
            ->for(DataPublication::factory())
            ->create();

        $this->assertInstanceOf(DataPublication::class, $subject->dataPublication);
    }

    public function test_keywords_relation()
    {
        $subject = Subject::factory()
            ->for(DataPublication::factory())
            ->has(Keyword::factory()->count(3))
            ->create();

        $this->assertCount(3, $subject->keywords);
    }
}
