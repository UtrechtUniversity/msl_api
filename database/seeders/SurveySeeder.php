<?php

namespace Database\Seeders;

use App\Models\Surveys\Question;
use App\Models\Surveys\QuestionType;
use App\Models\Surveys\QuestionTypes\CheckBox;
use App\Models\Surveys\QuestionTypes\DisplayBlade;
use App\Models\Surveys\QuestionTypes\RadioSelect;
use App\Models\Surveys\QuestionTypes\SelectQuestion;
use App\Models\Surveys\QuestionTypes\TextQuestion;
use App\Models\Surveys\Survey;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //truncation due to duplication of entries each seed run
        DB::table('question_survey')->truncate();
        Question::truncate();

        // Seed question types
        $textQuestionType = QuestionType::updateOrCreate([
            'name' => 'text',
            'class' => TextQuestion::class,
        ]);

        $selectQuestionType = QuestionType::updateOrCreate([
            'name' => 'select',
            'class' => SelectQuestion::class,
        ]);

        $radioSelectType = QuestionType::updateOrCreate([
            'name' => 'radio',
            'class' => RadioSelect::class,
        ]);

        $checkBoxType = QuestionType::updateOrCreate([
            'name' => 'check',
            'class' => CheckBox::class,
        ]);

        $displayBladeType = QuestionType::updateOrCreate([
            'name' => 'displayBlade',
            'class' => DisplayBlade::class,
        ]);

        $allDomains = [
            'analogue' => 'Analogue Modelling of Geological Processes',
            'geochemistry' => 'Geochemistry',
            'microtomo' => 'Microscopy and Tomography',
            'paleomag' => 'Magnetism and Paleomagnetism',
            'rockmelt' => 'Rock and Melt Physics',
            'testbeds' => 'Geo-Energy Test Beds',
        ];

        foreach ($allDomains as $key => $value) {
            $this->scenarioSurveySeeding(
                $key,
                $textQuestionType,
                $selectQuestionType,
                $radioSelectType,
                $checkBoxType,
                $displayBladeType
            );
        }

    }

    private function scenarioSurveySeeding(
        $domainName,
        $textQuestionType,
        $selectQuestionType,
        $radioSelectType,
        $checkBoxType,
        $displayBladeType
    ) {

        // survey
        $survey = Survey::updateOrCreate([
            'name' => 'scenarioSurvey-'.$domainName,
            'active' => true,
        ]);

        $order = 0;
        // seed questions
        $order++;
        Question::updateOrCreate([
            'question_type_id' => $selectQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => 'Which describes your role the best?',
                'options' => [
                    'Modeler',
                    'Researcher',
                    'Other',
                ],
                'validation' => ['required'],
                'sectionName' => 'WhichRoleDescribesYouBest',
                'placeholder' => 'Select an option from this list',
                'titleBold' => true,
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $selectQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => 'In which sector is your role?',
                'options' => [
                    'Industry',
                    'Academic',
                    'Government',
                    'Nonprofit / NGO',
                    'Consultancy'
                ],
                'validation' => ['required'],
                'sectionName' => 'WhichSectorIsYourRole',
                'placeholder' => 'Select an option from this list',
                'titleBold' => true,

            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([  
            'question_type_id' => $displayBladeType->id,
            'answerable' => false,
            'question' => [
                'bladeName' => 'surveys.bladeDisplays.surveyScenario.survey-gallery-'.$domainName,
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $radioSelectType->id,
            'answerable' => true,
            'question' => [
                'title' => 'Do you recognize the challenge portrayed in this scenario in your work?',
                'titleBold' => true,
                'sectionName' => 'AgreementChallengeScenario',
                'validation' => ['required'],
                'options' => [
                    'Strongly disagree',
                    'Disagree',
                    'Somewhat disagree',
                    'Somewhat agree',
                    'Agree',
                    'Strongly Agree',
                ],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $textQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => "Please list examples of such challenges or explain why you don't recognize any",
                'titleBold' => true,
                'sectionName' => 'ChallengesExamples',
                'textBlock' => true,
                'placeholder' => 'Please type your answer here',
                'validation' => ['required', 'min:20'],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $textQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => 'How do you approach a similar challenge in your work?',
                'titleBold' => true,
                'sectionName' => 'SimilarChallengeApproach',
                'textBlock' => true,
                'placeholder' => 'Please type your answer here',
                'validation' => ['required', 'min:20'],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $textQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => 'Please describe briefly what function the software tool fulfills in the scenario?',
                'titleBold' => true,
                'sectionName' => 'FunctionalDescription',
                'textBlock' => true,
                'placeholder' => 'Please type your answer here',
                'validation' => ['required', 'min:20'],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        
        $order++;
        Question::updateOrCreate([
            'question_type_id' => $radioSelectType->id,
            'answerable' => true,
            'question' => [
                'title' => 'Would you see yourself using this tool?',
                'titleBold' => true,
                'sectionName' => 'UsingTool',
                'validation' => ['required'],
                'options' => [
                    'Very Probably',
                    'Definitely Not',
                    'Probably Not',
                    'Possibly',
                    'Probably',
                    'Very Probably',
                    'Definitely',
                ],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $radioSelectType->id,
            'answerable' => true,
            'question' => [
                'title' => 'How often would you use the software tool described in the scenario?',
                'titleBold' => true,
                'sectionName' => 'UsingToolHowOften',
                'validation' => ['required'],
                'options' => [
                    'Daily',
                    'Weekly',
                    'Monthly',
                    'Yearly',
                    'Never',
                ],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        
        $order++;
        Question::updateOrCreate([
            'question_type_id' => $checkBoxType->id,
            'answerable' => true,
            'question' => [
                'title' => 'When would you see this data tool being beneficial in your process?',
                'titleBold' => true,
                'sectionName' => 'PhaseSelect',
                'validation' => ['required'],
                'options' => [
                    'Problem Identification',
                    'Literature Review',
                    'Setting Research Questions, Objectives, and Hypothesis',
                    'Choosing the Design Study',
                    'Deciding on the Sample Design',
                    'Collecting Data',
                    'Processing and Analyzing Data',
                    'Writing the Report'
                ],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);

        $order++;
        Question::updateOrCreate([
            'question_type_id' => $textQuestionType->id,
            'answerable' => true,
            'question' => [
                'title' => 'How would you change the software tool to make useful for your work?',
                'titleBold' => true,
                'sectionName' => 'ChangeScenario',
                'textBlock' => true,
                'placeholder' => 'Please type your answer here',
                'validation' => ['required', 'min:20'],
            ],
        ])->surveys()->attach($survey->id, ['order' => $order]);



        // $order++;
        // Question::updateOrCreate([
        //     'question_type_id' => $checkBoxType->id,
        //     'answerable' => true,
        //     'question' => [
        //         'title' => 'Do you want to be contacted to stay up to date with futher contributions? Then you must acknowledge...blabla...legal chatter',
        //         'titleBold' => true,
        //         'sectionName' => 'gdprAgreement',
        //         'validation' => ['required_with:EmailContact', 'nullable'],
        //         'options' => [
        //             'I agree',
        //         ],
        //     ],
        // ])->surveys()->attach($survey->id, ['order' => $order]);

        // $order++;
        // Question::updateOrCreate([
        //     'question_type_id' => $textQuestionType->id,
        //     'answerable' => true,
        //     'question' => [
        //         'title' => 'Leave your email in the box below:',
        //         'titleBold' => false,
        //         'sectionName' => 'EmailContact',
        //         'textBlock' => false,
        //         'placeholder' => 'your@email.domain',
        //         'validation' => ['required_with:gdprAgreement', 'email:rfc,dns,filter,spoof', 'nullable'],
        //     ],
        // ])->surveys()->attach($survey->id, ['order' => $order]);

    }
}
