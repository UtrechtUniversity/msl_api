<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
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

        $answer = Answer::create([
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $this->assertSame($response->id, $answer->response->id);
    }

    public function test_question_relation(): void
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

        $answer = Answer::create([
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $this->assertSame($question->id, $answer->question->id);
    }
}
