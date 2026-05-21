<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_question_type_relation(): void
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

        $this->assertSame($questionType->id, $question->questionType->id);
    }

    public function test_surveys_relation(): void
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

        $question->surveys()->attach($survey->id, ['order' => 1]);

        $this->assertCount(1, $question->fresh()->surveys);
        $this->assertTrue($question->surveys->contains($survey));
        $this->assertSame($question->id, $survey->questions->first()->id);
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

        $answer = $question->answers()->create([
            'response_id' => $response->id,
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $this->assertCount(1, $question->fresh()->answers);
        $this->assertTrue($question->answers->contains($answer));
        $this->assertSame($question->id, $answer->question->id);
    }
}
