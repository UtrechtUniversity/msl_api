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
            // 'domain' => $domain,
            'allQuestions' => $this->getSortedQuestions($surveyId),
            // 'surveyId' => $surveyId,
        ]);
    }

    /**
     * Show the contribute survey scenario page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contributeSurveyScenarioProcess(Request $request, $surveyId): RedirectResponse
    {
        $sortedQuestions = $this->getSortedQuestions($surveyId);

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

    private function getSortedQuestions($surveyId)
    {
        $allQuestions = Survey::where('id', $surveyId)->first()->questions;
        // the ->orderBy() has no effect for some reason
        // this is why it is done this way
        $allQuestionsSorted = [];
        foreach ($allQuestions as $question) {
            $allQuestionsSorted[$question->pivot->order] = $question;
        }

        return $allQuestionsSorted;
    }
}
