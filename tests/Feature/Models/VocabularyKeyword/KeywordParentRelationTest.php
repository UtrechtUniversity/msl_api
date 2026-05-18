<?php

namespace Tests\Feature\Models\VocabularyKeyword;

use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeywordParentRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_keyword_parent_relation(): void
    {
        $vocabulary = Vocabulary::create([
            'name' => 'Materials',
            'uri' => 'https://example.org/vocab/materials',
            'display_name' => 'Materials',
        ]);

        $parent = $vocabulary->keywords()->create([
            'value' => 'rock',
            'uri' => 'https://example.org/vocab/materials/rock',
            'level' => 0,
            'hyperlink' => '',
            'label' => 'Rock',
        ]);

        $child = $vocabulary->keywords()->create([
            'parent_id' => $parent->id,
            'value' => 'granite',
            'uri' => 'https://example.org/vocab/materials/granite',
            'level' => 1,
            'hyperlink' => '',
            'label' => 'Granite',
        ]);

        $this->assertSame($parent->id, $child->parent->id);
    }
}
