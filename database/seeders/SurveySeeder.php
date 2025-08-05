<?php

namespace Database\Seeders;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Survey;
use Illuminate\Database\Seeder;
use App\Models\Surveys\Question;
use App\Models\Surveys\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\CheckBox;
use App\Models\Surveys\QuestionTypes\RadioSelect;
use App\Models\Surveys\QuestionTypes\TextQuestion;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dump current data
        Survey::truncate();
        Question::truncate();
        QuestionType::truncate();
        Answer::truncate();
        Response::truncate();
        DB::table('question_survey')->truncate();
       
        // Seed question types
        $textQuestionType = QuestionType::create([
            'name' => 'text',
            'class' => TextQuestion::class
        ]);

        $selectQuestionType = QuestionType::create([
            'name' => 'select',
            'class' => SelectQuestion::class,
        ]);

        $radioSelectType = QuestionType::create([
            'name' => 'radio',
            'class' => RadioSelect::class,
        ]);

        $checkBoxType = QuestionType::create([
            'name' => 'check',
            'class' => CheckBox::class,
        ]);

        //survey test
        $survey = Survey::create([
            'name' => 'scenarioSurvey',
            'active' => true,
        ]);

        //survey test
        $survey2 = Survey::create([
            'name' => 'randomSurvey_inactive',
            'active' => false,
        ]);

        $order = 0;
        // seed test questions
        $order++;
        $question2 = Question::create([
            'question_type_id' => $selectQuestionType->id,
            'question' => [
                'title' => 'Is this a question?',
                'options' => [
                    'Netherlands',
                    'Germany',
                ],
                'validation' => ['required'],
                'sectionName' => "WhichCountry",
                'placeholder' => 'Select an option from this list',
                'titleBold' => true,

            ]
        ]);
        $question2->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        $question1 = Question::create([
            'question_type_id' => $textQuestionType->id,
            'question' => [
                'title' => 'Is this another question?',
                'titleBold' => true,
                'sectionName' => 'AnotherQuestion',
                'textBlock' => true,
                'placeholder' => 'Please type your answer here',
                'validation' => ['required', 'min:50']
            ]
        ]);
        $question1->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        $question3 = Question::create([
            'question_type_id' => $radioSelectType->id,
            'question' => [
                'title' => 'Is this another question?',
                'titleBold' => true,
                'sectionName' => 'AgreementRadio',
                'validation' => ['required'],
                'options' => [
                    'Agree very much',
                    'Agree',
                    'Disagree',
                    'Disagree very much',
                ],
            ]
        ]);
        $question3->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        $question4 = Question::create([
            'question_type_id' => $checkBoxType->id,
            'question' => [
                'title' => 'Is this another question?',
                'titleBold' => true,
                'sectionName' => 'PhaseSelect',
                'validation' => ['required'],
                'options' => [
                    'Problem Identification',
                    'Literature Review',
                    'Setting Research Questions, Objectives, and Hypothesis',
                    'Choosing the Design Study',
                ],
            ]
        ]);
        $question4->surveys()->attach($survey->id, ['order' => $order]);

    }
}
