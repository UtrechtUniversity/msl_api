<?php

namespace Tests\Feature\Models;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
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

        $question = new Question([
            'question' => [
                'label' => 'Is this a question?',
                'options' => [
                    'option1',
                    'option2',
                ],
            ],
            'answerable' => true,
        ]);

        $question->questionType()->associate($questionType);
        $question->save();

        $this->assertTrue($question->fresh()->questionType->is($questionType));
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

        $question->surveys()->attach($survey, ['order' => 1]);

        $this->assertCount(1, $question->fresh()->surveys);
        $this->assertTrue($question->surveys->contains($survey));
        $this->assertTrue($survey->fresh()->questions->contains($question));
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

        $answer = $response->answers()->make([
            'answer' => [
                'value' => 'answerQuestion1',
            ],
        ]);

        $question->answers()->save($answer);

        $this->assertCount(1, $question->fresh()->answers);
        $this->assertTrue($question->answers->contains($answer));
        $this->assertTrue($answer->fresh()->question->is($question));
    }
}
