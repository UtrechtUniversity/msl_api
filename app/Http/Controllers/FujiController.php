<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FujiFairAssessment;
use App\Jobs\ProcessFujiFairAssessment;
use App\Exports\FujiExport;


class FujiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addDois()
    {       
        return view('fuji-add-dois');
    }
    
    public function processDois(Request $request) 
    {
        $request->validate([
            'group-identifier' => 'required',
            'dois' => 'required'
        ]);
                
        $groupIdentifier = $request->input('group-identifier');
        $dois = explode(PHP_EOL, $request->input('dois'));
        
        //remove current assessments with same group-identifier
        DB::table('fuji_fair_assessments')->where('group_identifier', $groupIdentifier)->delete();
        
        foreach ($dois as $doi) {
            $fujiFairAssessment = FujiFairAssessment::create([
                'group_identifier' => $groupIdentifier,
                'doi' => $doi
            ]);
            
            ProcessFujiFairAssessment::dispatch($fujiFairAssessment);
        }        
        
        $request->session()->flash('status', 'Fuji assessments added to queue');
        return redirect()->route('home');
    }
    
    public function viewAssessmentGroups() {
        
        $groupings = DB::table('fuji_fair_assessments')
            ->selectRaw('group_identifier, avg(score_percent) as avg_percent, count(group_identifier) as count')
            ->groupBy('group_identifier')
            ->get();
        
        return view('fuji-group-overview', ['groups' => $groupings]);
    }
    
    public function viewAssessmentGroup($groupIdentifier) {        
        $assessments = FujiFairAssessment::where('group_identifier', $groupIdentifier)->get();
        
        return view('fuji-group', ['assessments' => $assessments]);        
    }        
    
    public function viewAssessment($assessmentId) {
        $assessment = FujiFairAssessment::where('id', $assessmentId)->first();
        
        return view('fuji-assessment', ['assessment' => $assessment]);
    }
    
    public function downloadFujiReport() {
        
        return Excel::download(new FujiExport(), 'fuji-report.xlsx');
    }
    
    
}
