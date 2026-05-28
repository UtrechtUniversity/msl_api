<?php

namespace Tests\Feature\Models;

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

        $response = new Response([
            'email' => 'em@il',
        ]);

        $response->survey()->associate($survey);
        $response->save();

        $this->assertTrue($response->fresh()->survey->is($survey));
    }

    public function test_answers_relation(): void
    {
        $survey = Survey::create([
            'name' => 'survey1',
            'active' => true,
        ]);

        $response = $survey->responses()->create([
            'email' => 'em@il',
        ]);

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

        $answer = $question->answers()->make([
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $response->answers()->save($answer);

        $this->assertCount(1, $response->fresh()->answers);
        $this->assertTrue($response->answers->contains($answer));
    }
}
