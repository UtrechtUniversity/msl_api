<?php

namespace Tests\Feature;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionSurvey;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\Response;
use App\Models\Surveys\SelectQuestion;
use Tests\TestCase;
use App\Models\Surveys\Survey;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     use RefreshDatabase;

    public function test_db(): void
    {
        ////
        //question Type instances
        $qt1 = new SelectQuestion(            [
            'label' => "selectQuestion1",
            'options' => [
                'option1',
                'option2'
            ]

        ]);

        ////
        // save question types
        $questionType = new QuestionType();
        $questionType->fill(
            [
                'name' => 'questionType1',
                'class' => json_encode($qt1)
            ]
        );
        $questionType->save();

        ////
        //generate questions based on type
        $question1 = new Question();
        $question1->fill(
            [
                'question' => 'question1',
                'question_type_id' => $questionType->id
            ]
            );
        $question1->save();


        $question2 = new Question();
        $question2->fill(
            [
                'question' => 'question2',
                'question_type_id' => $questionType->id
            ]
            );
        $question2->save();

        ////
        //first define the survey itself
        $survey = new Survey();
        $survey->fill(
            
            [
                'name'=>'survey1',
                'active'=> true
            ]
            );
        $survey->save();

        ////
        //generating the question collection for the survey
        $questionSurvey = new QuestionSurvey();
        $questionSurvey->fill(
            
            [
                'survey_id'=> $survey->id,
                'question_id'=> $question1->id,
                'order'=> 2
            ]
            );
        $questionSurvey->save();
        
        $questionSurvey2 = new QuestionSurvey();
        $questionSurvey2->fill(
            
            [
                'survey_id'=> $survey->id,
                'question_id'=> $question2->id,
                'order'=> 1 //how to make sure that there is no doublicate in the ordering
            ]
            );
        $questionSurvey2->save();

        ////
        //creating a response
        $reponseSurvey = new Response();
        $reponseSurvey->fill(
            [
                'survey_id' => $survey->id,
                'email' => 'em@il'
            ]
        );
        $reponseSurvey -> save();

        ////
        //all the answers
        $answer1 = new Answer();
        $answer1->fill(
            [
                'response_id' => $reponseSurvey->id,
                'question_id' => $questionSurvey->id,
                'answer' => 'answerQuestion1'
            ]
        );
        $answer1->save();

        $answer2 = new Answer();
        $answer2->fill(
            [
                'response_id' => $reponseSurvey->id,
                'question_id' => $questionSurvey2->id,
                'answer' => 'answerQuestion2'
            ]
        );
        $answer2->save();


        ////
        //Testing

        //question Types
        $this->assertDatabaseCount('question_types', 1);
        $this->assertDatabaseHas('question_types', [
            'name' => 'questionType1',
            'class' => $qt1
        ]);

        //questions
        $this->assertDatabaseCount('questions', 2);
        $this->assertDatabaseHas('questions', [
            'question' => 'question1',
            'question_type_id' => $questionType->id 
        ]);
        $this->assertDatabaseHas('questions', [
            'question' => 'question2',
            'question_type_id' => $questionType->id
        ]);

        //surveys
        $this->assertDatabaseCount('surveys', 1);
        $this->assertDatabaseHas('surveys', [
            'name' => 'survey1',
            'active' => true
        ]);

        //question survey
        $this->assertDatabaseCount('question_surveys', 2);
        $this->assertDatabaseHas('question_surveys', [
            'survey_id'=> $survey->id,
            'question_id'=> $question1->id,
            'order'=> 2
        ]);
        $this->assertDatabaseHas('question_surveys', [
            'survey_id'=> $survey->id,
            'question_id'=> $question2->id,
            'order'=> 1 
        ]);

        //responses
        $this->assertDatabaseCount('responses', 1);
        $this->assertDatabaseHas('responses', [
            'survey_id' => $survey->id,
            'email' => 'em@il'
        ]);

        //answers
        $this->assertDatabaseCount('answers', 2);
        $this->assertDatabaseHas('answers', [
            'response_id' => $reponseSurvey->id,
            'question_id' => $questionSurvey->id,
            'answer' => 'answerQuestion1'
        ]);
        $this->assertDatabaseHas('answers', [
            'response_id' => $reponseSurvey->id,
            'question_id' => $questionSurvey2->id,
            'answer' => 'answerQuestion2'
        ]);

        // checking relations
        $this->assertEquals('survey1', $questionSurvey->survey->name);
        dd($questionSurvey->question->question);
        $this->assertEquals($question1->id, $questionSurvey->question->question_type_id);

    }
}
