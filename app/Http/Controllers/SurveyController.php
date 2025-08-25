<?php

namespace App\Http\Controllers;

use App\Models\Surveys\Answer;
use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyController extends Controller
{
    /**
     * Show the contribute survey scenario page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeSurveyScenario($domain): View
    {
        $surveyId = Survey::where('name', 'scenarioSurvey-'.$domain)->first()->id;

        return view('surveys.contribute-survey-scenario', [
            'allQuestions' => Survey::where('id', $surveyId)->first()->questions
        ]);
    }

    /**
     * Show the contribute survey scenario page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeSurveyScenarioProcess(Request $request, $surveyId): RedirectResponse
    {
        $sortedQuestions = Survey::where('id', $surveyId)->first()->questions;

        $validationFields = [];

        foreach ($sortedQuestions as $question) {
            if (! empty($question->question->validation)) {
                $validationFields[$question->question->sectionName] = $question->question->validation;
            }
        }

        $request->validate($validationFields);

        $responseSurvey = Response::create([
            'survey_id' => $surveyId,
            'email' => $request->input('email'),
        ]);

        foreach ($sortedQuestions as $question) {
            // needs to check if the question instance has a validation field, since we have non-questions
            if (! empty($question->question->validation)) {
                if (is_array($request->input($question->question->sectionName))) {
                    foreach ($request->input($question->question->sectionName) as $input) {
                        Answer::create([
                            'response_id' => $responseSurvey->id,
                            'question_id' => $question->id,
                            'answer' => $input,
                        ]);
                    }
                } else {
                    Answer::create([
                        'response_id' => $responseSurvey->id,
                        'question_id' => $question->id,
                        'answer' => $request->input($question->question->sectionName),
                    ]);
                }
            }
        }

        return redirect('/')->with('modals', [
            'type' => 'success',
            'message' => 'Thanks for your input!']
        );
    }

}
