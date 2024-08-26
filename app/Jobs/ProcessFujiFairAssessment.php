<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Laboratory;
use App\Mappers\Helpers\KeywordHelper;
use App\Models\LaboratoryKeyword;
use App\Models\FujiFairAssessment;
use App\fuji\Fuji;


class ProcessFujiFairAssessment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $fujiFairAssessment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(FujiFairAssessment $fujiFairAssessment)
    {
        $this->fujiFairAssessment = $fujiFairAssessment;                
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()    
    {        
        $fuji =  new Fuji();
        
        $result = $fuji->evaluateRequest($this->fujiFairAssessment->doi);
        
        $this->fujiFairAssessment->processed = true;
        $this->fujiFairAssessment->response_code = $result->response_code;
        $this->fujiFairAssessment->response_full = $result->response_body;
        if(isset($result->response_body['summary']['score_percent']['FAIR'])) {
            $this->fujiFairAssessment->score_percent = $result->response_body['summary']['score_percent']['FAIR'];
        }
        
        $this->fujiFairAssessment->save();
    }    
}
