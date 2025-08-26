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
    public function surveyForm($surveyId): View
    {
        $survey = Survey::where('id', $surveyId)->first();

        if($survey->active){
            return view('surveys.contribute-survey-scenario', [
                'allQuestions' => $survey->questions,
                'surveyId' => $survey->id
            ]);
        } else {
            return view('/')->with('modals', [
                'type' => 'error',
                'message' => 'This survey is no longer active! Thank you for your interest. Please contact us for more information or feedback.']
            );
        }
    }

    /**
     * Process the survey
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function surveyProcess(Request $request, $surveyId): RedirectResponse
    {
        $survey = Survey::where('id', $surveyId)->first();

        $request->validate($survey->getValidationRules());

        $responseSurvey = Response::create([
            'survey_id' => $surveyId,
            'email' => $request->input('email'),
        ]);

        foreach ($survey->questions as $question) {
            if ($question->hasValidation) {
                    Answer::create([
                        'response_id' => $responseSurvey->id,
                        'question_id' => $question->id,
                        'answer' => json_encode(
                                        $request->input($question->question->sectionName),
                                        JSON_THROW_ON_ERROR,
                                    )
                    ]);
            }
        }

        return redirect('/')->with('modals', [
            'type' => 'success',
            'message' => 'Thanks for your input!']
        );
    }

}
