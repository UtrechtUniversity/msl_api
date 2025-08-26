<?php


namespace App\Exports;

// use App\Survey;
use App\Models\Surveys\Survey;
use Dom\Implementation;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyExport implements FromCollection, WithHeadings, WithMapping
{
    public $targetSurvey;

    public function __construct(Survey $targetSurvey)
    {
        $this->targetSurvey = $targetSurvey;
    }

    public function collection()
    {
        $answers = [];
        foreach ($this->targetSurvey->responses as $response) {
            foreach ($response->answers as $answer){
                $answers[] = $answer;
            } 
        }
        return collect($answers);
    }

    public function headings(): array
    {
        return 
            [
                'survey_name',
                'survey_id',
                'survey_active',

                'question_id',
                'question_type_id',
                'question_title',
                'question_options',

                'response_id',
                'response_survey_id',
                'response_email',
                'response_created_at',
                'response_updated_at',

                'answer_id',
                'answer_answer',
                'answer_raw',

                'downloadDate'
            ];
    }


    public function map($answer): array
    {
        $options='none';
        $answerString = $answer->answer;

        if(property_exists($answer->question->question, "options")){
            $options=Implode(" | ", $answer->question->question->options);
            if(is_array($answer->answer)) {
                $answerString = [];
                foreach ($answer->answer as $value) {
                    $answerString[] = $answer->question->question->options[$value];
                }
                $answerString = implode(",", $answerString);
            } else {
                if($answer->answer == null || $answer->answer == "")
                {
                    $answerString = 'null';
                } 
                else 
                {  
                    $answerString = $answer->question->question->options[$answer->answer];
                }
                
            }
        }

        return
            [
                $answer->response->survey->name,
                $answer->response->survey->id,
                $answer->response->survey->active,

                $answer->question->id,
                $answer->question->question_type_id,
                $answer->question->question->title,
                $options,

                $answer->response->id,
                $answer->response->survey_id,
                $answer->response->email,
                $answer->response->created_at,
                $answer->response->updated_at,

                $answer->id,
                $answerString,
                $answer->answer,

                date('Y-m-d H:i:s')
            ];
    }



}
