<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SurveyComponent extends Component
{
    
    private $allQuestions;
    /**
     * Create a new component instance.
     */
    public function __construct($allQuestions)
    {
        $this->allQuestions = $allQuestions;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.survey-component',[
            'allQuestions' => $this->allQuestions
        ]);
    }
}
