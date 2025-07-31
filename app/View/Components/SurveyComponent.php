<?php

namespace App\View\Components;

use Closure;
use App\Models\Surveys\Survey;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SurveyComponent extends Component
{

    private $questionConfig;
    /**
     * Create a new component instance.
     */
    public function __construct($questionConfig)
    {
        $this->questionConfig = $questionConfig;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // $allQuestions = Survey::where('id', $this->surveyID)->first()->questions;

        return view('components.survey-component',[
            'questionConfig' => $this->questionConfig
        ]);
    }
}
