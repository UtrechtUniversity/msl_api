<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_questions_relation(): void
    {
        $survey = Survey::create([
            'name' => 'survey1',
            'active' => true,
        ]);

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

        $survey->questions()->attach($question->id, ['order' => 1]);

        $this->assertCount(1, $survey->fresh()->questions);
        $this->assertTrue($survey->questions->contains($question));
        $this->assertSame($survey->id, $question->surveys->first()->id);
    }

    public function test_responses_relation(): void
    {
        $survey = Survey::create([
            'name' => 'survey1',
            'active' => true,
        ]);

        $response = $survey->responses()->create([
            'email' => 'em@il',
        ]);

        $this->assertCount(1, $survey->fresh()->responses);
        $this->assertTrue($survey->responses->contains($response));
        $this->assertSame($survey->id, $response->survey->id);
    }
}
