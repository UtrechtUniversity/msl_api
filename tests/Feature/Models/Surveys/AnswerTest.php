<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_relation(): void
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

        $answer->response()->associate($response);
        $answer->save();

        $this->assertTrue($answer->fresh()->response->is($response));
    }

    public function test_question_relation(): void
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

        $answer = $response->answers()->make([
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $answer->question()->associate($question);
        $answer->save();

        $this->assertTrue($answer->fresh()->question->is($question));
    }
}
