<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Question;
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

        $question = Question::create([
            'question' => [
                'label' => 'Is this a question?',
                'options' => [
                    'option1',
                    'option2',
                ],
            ],
            'question_type_id' => $questionType->id,
            'answerable' => true,
        ]);

        $this->assertCount(1, $questionType->fresh()->questions);
        $this->assertTrue($questionType->questions->contains($question));
        $this->assertSame($questionType->id, $question->questionType->id);
    }
}
