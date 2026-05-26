<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_questions_relation(): void
    {
        $questionType = QuestionType::create([
            'name' => 'questionType1',
            'class' => SelectQuestion::class,
        ]);

        $question = $questionType->questions()->create([
            'question' => [
                'label' => 'Is this a question?',
                'options' => [
                    'option1',
                    'option2',
                ],
            ],
            'answerable' => true,
        ]);

        $this->assertCount(1, $questionType->fresh()->questions);
        $this->assertEquals($questionType->id, $question->question_type_id);
        $this->assertTrue($questionType->questions->contains($question));
        $this->assertTrue($question->questionType->is($questionType));
    }
}
