<?php

namespace App\View\Components;

use Closure;
use App\Models\Surveys\Survey;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SurveyComponent extends Component
{

    private $questionConfig;
    private $allImages;
    private $allDescriptions;
    /**
     * Create a new component instance.
     */
    public function __construct($questionConfig, $domain)
    {
        $this->questionConfig = $questionConfig;

        foreach (File::files(public_path('images/surveys/scenario/'.$domain)) as $entry) {
            if($entry->getExtension() == 'png'){
                $this->allImages [] = 'images/surveys/scenario/'.$domain."/".$entry->getFilename();
            } else if ($entry->getExtension() == 'json'){
                $json = file_get_contents(public_path('images/surveys/scenario/'.$domain."/".$entry->getFilename()));
                $this->allDescriptions = json_decode($json, true)['descriptions'];
            }
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.survey-component',[
            'questionConfig' => $this->questionConfig,
            'allImages' => $this->allImages,
            'allDescriptions' => $this->allDescriptions
        ]);
    }
}
