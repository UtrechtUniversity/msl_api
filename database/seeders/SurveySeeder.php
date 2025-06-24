<?php

namespace Database\Seeders;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dump current data
        Question::truncate();
        QuestionType::truncate();

        // Seed question type
        $textQuestionType = QuestionType::create([
            'name' => 'text',
            'class' => 'App\Models\Surveys\TextQuestion'
        ]);

        $selectQuestionType = QuestionType::create([
            'name' => 'select',
            'class' => 'App\Models\Surveys\SelectQuestion',
        ]);

        // questions
        $question1 = Question::create([
            'question_type_id' => $textQuestionType->id,
            'question' => [
                'label' => 'text label',
            ]
        ]);

        $question2 = Question::create([
            'question_type_id' => $selectQuestionType->id,
            'question' => [
                'label' => 'select label',
                'options' => [
                    '1' => 'Netherlands',
                    '2' => 'Germany',
                ],
            ]
        ]);

    }
}
