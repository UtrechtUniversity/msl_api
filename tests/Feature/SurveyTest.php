<?php

namespace Tests\Feature;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // first define the survey itself
        $survey = Survey::create(
            [
                'name' => 'survey1',
                'active' => true,
            ]
        );

        // save question types
        $questionType = QuestionType::create(
            [
                'name' => 'questionType1',
                'class' => SelectQuestion::class,
            ]
        );

        // generate questions based on type

        $question1 = Question::create(
            [
                'question' => [
                    'label' => 'Is this a question?',
                    'options' => [
                        'option1',
                        'option2',
                    ],
                ],

                'question_type_id' => $questionType->id,
                'answerable' => true
            ]
        );
        $question1->surveys()->attach($survey->id, ['order' => 1]);

        $question2 = Question::create(
            [
                'question' => [
                    'label' => 'Is this another question?',
                    'options' => [
                        'option1',
                        'option2',
                    ],
                ],
                'question_type_id' => $questionType->id,
                'answerable' => true
            ]
        );
        $question2->surveys()->attach($survey->id, ['order' => 2]);

        // creating a response
        $responseSurvey = Response::create(
            [
                'survey_id' => $survey->id,
                'email' => 'em@il',
            ]
        );

        // all the answers
        Answer::create(
            [
                'response_id' => $responseSurvey->id,
                'question_id' => $question1->id,
                'answer' => 'answerQuestion1',
            ]
        );

        Answer::create(
            [
                'response_id' => $responseSurvey->id,
                'question_id' => $question2->id,
                'answer' => 'answerQuestion2',
            ]
        );
    }

    public function test_relation_question_to_survey(): void
    {
        $questions = Question::all();
        $this->assertInstanceOf(Collection::class, $questions);
        $this->assertEquals(2, $questions->count());
        $this->assertInstanceOf(Survey::class, $questions->first()->surveys->first());
    }

    public function test_relation_survey_to_question(): void
    {
        $surveys = Survey::all();
        $this->assertInstanceOf(Collection::class, $surveys);
        $this->assertEquals(1, $surveys->count());
        $this->assertInstanceOf(Question::class, $surveys->first()->questions->first());
    }

    public function test_relation_questiontype_to_question(): void
    {
        $questionTypes = QuestionType::all();
        $this->assertInstanceOf(Collection::class, $questionTypes);
        $this->assertEquals(1, $questionTypes->count());
        $this->assertInstanceOf(Question::class, $questionTypes->first()->questions->first());
    }

    public function test_relation_question_to_questiontype(): void
    {
        $questions = Question::all();
        $this->assertInstanceOf(Collection::class, $questions);
        $this->assertEquals(2, $questions->count());
        $this->assertInstanceOf(QuestionType::class, $questions->first()->question_type);
    }

    public function test_relation_answers_to_question(): void
    {
        $answers = Answer::all();
        $this->assertInstanceOf(Collection::class, $answers);
        $this->assertEquals(2, $answers->count());
        $this->assertInstanceOf(Question::class, $answers->first()->question);
    }

    public function test_relation_question_to_answer(): void
    {
        $questions = Question::all();
        $this->assertInstanceOf(Collection::class, $questions);
        $this->assertEquals(2, $questions->count());
        $this->assertInstanceOf(Answer::class, $questions->first()->answers->first());
    }

    public function test_relation_answers_to_response(): void
    {
        $answers = Answer::all();
        $this->assertInstanceOf(Collection::class, $answers);
        $this->assertEquals(2, $answers->count());
        $this->assertInstanceOf(Response::class, $answers->first()->response);
    }

    public function test_relation_response_to_answer(): void
    {
        $responses = Response::all();
        $this->assertInstanceOf(Collection::class, $responses);
        $this->assertEquals(1, $responses->count());
        $this->assertInstanceOf(Answer::class, $responses->first()->answers->first());
    }

    public function test_relation_response_to_survey(): void
    {
        $responses = Response::all();
        $this->assertInstanceOf(Collection::class, $responses);
        $this->assertEquals(1, $responses->count());
        $this->assertInstanceOf(Survey::class, $responses->first()->survey);
    }

    public function test_relation_survey_to_response(): void
    {
        $surveys = Survey::all();
        $this->assertInstanceOf(Collection::class, $surveys);
        $this->assertEquals(1, $surveys->count());
        $this->assertInstanceOf(Response::class, $surveys->first()->responses->first());
    }
}
