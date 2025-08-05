<?php

namespace Tests\Feature;

// namespace App\Models\Surveys\QuestionTypes;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    private $surveys = [];

    private $questionTypes = [];

    private $questions = [];

    private $responses = [];

    private $answers = [];

    protected function setUp(): void
    {
        // //
        parent::setUp();

        // first define the survey itself
        $survey = new Survey;
        $survey->fill(

            [
                'name' => 'survey1',
                'active' => true,
            ]
        );
        $survey->save();
        array_push($this->surveys, $survey);

        // //
        // save question types
        $questionType = new QuestionType;
        $questionType->fill(
            [
                'name' => 'questionType1',
                'class' => SelectQuestion::class,
            ]
        );
        $questionType->save();
        array_push($this->questionTypes, $questionType);

        // //
        // generate questions based on type
        $question1 = new Question;
        $question1->fill(
            [
                'question' => [
                    'label' => 'Is this a question?',
                    'options' => [
                        'option1',
                        'option2',
                    ],
                ],

                'question_type_id' => $questionType->id,
            ]
        );
        $question1->save();
        $question1->surveys()->attach($survey->id, ['order' => 1]);

        $question2 = new Question;
        $question2->fill(
            [
                'question' => [
                    'label' => 'Is this another question?',
                    'options' => [
                        'option1',
                        'option2',
                    ],
                ],
                'question_type_id' => $questionType->id,
            ]
        );
        $question2->save();
        $question2->surveys()->attach($survey->id, ['order' => 2]);

        array_push($this->questions, $question1, $question2);

        // //
        // creating a response
        $responseSurvey = new Response;
        $responseSurvey->fill(
            [
                'survey_id' => $survey->id,
                'email' => 'em@il',
            ]
        );
        $responseSurvey->save();

        array_push($this->responses, $responseSurvey);

        // //
        // all the answers
        $answer1 = new Answer;
        $answer1->fill(
            [
                'response_id' => $responseSurvey->id,
                'question_id' => $question1->id,
                'answer' => 'answerQuestion1',
            ]
        );
        $answer1->save();
        // $this->answer = $answer1;

        $answer2 = new Answer;
        $answer2->fill(
            [
                'response_id' => $responseSurvey->id,
                'question_id' => $question2->id,
                'answer' => 'answerQuestion2',
            ]
        );
        $answer2->save();

        array_push($this->answers, $answer1, $answer2);
    }

    public function test_relation_question_to_survey(): void
    {
        $this->assertInstanceOf(Survey::class, $this->questions[0]->surveys->first->id);
    }

    public function test_relation_survey_to_question(): void
    {
        $this->assertInstanceOf(Question::class, $this->surveys[0]->questions->first->id);
    }

    public function test_relation_questiontype_to_question(): void
    {
        $this->assertInstanceOf(Question::class, $this->questionTypes[0]->questions->first->id);
    }

    public function test_relation_question_to_questiontype(): void
    {
        $this->assertInstanceOf(QuestionType::class, $this->questions[0]->question_type);
    }

    public function test_relation_answers_to_question(): void
    {
        $this->assertInstanceOf(Question::class, $this->answers[0]->question);
    }

    public function test_relation_question_to_answer(): void
    {
        $this->assertInstanceOf(Answer::class, $this->questions[0]->answers->first->id);
    }

    public function test_relation_answers_to_response(): void
    {
        $this->assertInstanceOf(Response::class, $this->answers[0]->response);
    }

    public function test_relation_response_to_answer(): void
    {
        $this->assertInstanceOf(Answer::class, $this->responses[0]->answers->first->id);
    }

    public function test_relation_response_to_survey(): void
    {
        $this->assertInstanceOf(Survey::class, $this->responses[0]->survey);
    }

    public function test_relation_survey_to_response(): void
    {
        $this->assertInstanceOf(Response::class, $this->surveys[0]->responses->first->id);
    }
}
