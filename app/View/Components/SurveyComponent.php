<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SurveyComponent extends Component
{
    
    private $allQuestions;
    private $surveyName;
    
    /**
     * Create a new component instance.
     */
    public function __construct($allQuestions, $surveyName)
    {
        $this->allQuestions = $allQuestions;
        $this->surveyName = $surveyName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.survey-component',[
            'allQuestions' => $this->allQuestions,
            'surveyName' => $this->surveyName
        ]);
    }
}
