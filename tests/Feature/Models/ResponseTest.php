<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_relation(): void
    {
        $survey = Survey::create([
            'name' => 'survey1',
            'active' => true,
        ]);

        $response = Response::create([
            'survey_id' => $survey->id,
            'email' => 'em@il',
        ]);

        $this->assertSame($survey->id, $response->survey->id);
    }

    public function test_answers_relation(): void
    {
        $survey = Survey::create([
            'name' => 'survey1',
            'active' => true,
        ]);

        $response = Response::create([
            'survey_id' => $survey->id,
            'email' => 'em@il',
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

        $answer = $response->answers()->create([
            'question_id' => $question->id,
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $this->assertCount(1, $response->fresh()->answers);
        $this->assertTrue($response->answers->contains($answer));
        $this->assertSame($response->id, $answer->response->id);
    }
}
