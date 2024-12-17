<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Laboratory;
use App\Models\LaboratoryUpdateGroupFast;
use App\Models\LaboratoryUpdateFast;


class ProcessLaboratoryUpdateGroupFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $laboratoryUpdateGroupFast;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast)
    {
        $this->laboratoryUpdateGroupFast = $laboratoryUpdateGroupFast;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        // Create an update job for each lab that should be present within FAST
        $labs = Laboratory::whereNotNull('fast_id')->get();
                
        foreach ($labs as $lab) {            
            $laboratoryUpdateFast = LaboratoryUpdateFast::create([
                'laboratory_update_group_fast_id' => $this->laboratoryUpdateGroupFast->id,
                'laboratory_id' => $lab->id
            ]);
            
            ProcessLaboratoryUpdateFast::dispatch($laboratoryUpdateFast);
        }
                
    }    

    
}
