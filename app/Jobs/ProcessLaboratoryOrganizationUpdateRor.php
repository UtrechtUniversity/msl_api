<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LaboratoryOrganizationUpdateRor;
use App\Ror\Ror;

class ProcessLaboratoryOrganizationUpdateRor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $laboratoryOrganizationUpdateRor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryOrganizationUpdateRor $laboratoryOrganizationUpdateRor)
    {
        $this->laboratoryOrganizationUpdateRor = $laboratoryOrganizationUpdateRor;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        $laboratoryOrganization = $this->laboratoryOrganizationUpdateRor->laboratoryOrganization;
        
        $ror = new Ror();
        
        // rors are defined in fast including an url we need just the identifier
        $rorIdentifier = substr($laboratoryOrganization->external_identifier, strrpos($laboratoryOrganization->external_identifier, '/') + 1);
        
        $result = $ror->singleRecordRequest($rorIdentifier);
        
        if($result->response_code == 200) {
            $data = $result->response_body;
            
            if(isset($data['country']['country_name'])) {
                $laboratoryOrganization->ror_country = $data['country']['country_name'];
            }
            
            if(isset($data['country']['country_code'])) {
                $laboratoryOrganization->ror_country_code = $data['country']['country_code'];
            }
            
            if(isset($data['links'][0])) {
                $laboratoryOrganization->ror_website = $data['links'][0];
            }
            
            $laboratoryOrganization->save();
            
            $this->laboratoryOrganizationUpdateRor->response_code = $result->response_code;
            $this->laboratoryOrganizationUpdateRor->source_organization_data = $data;
            $this->laboratoryOrganizationUpdateRor->save();
            
        } else {
            $this->laboratoryOrganizationUpdateRor->response_code = $result->response_code;
            $this->laboratoryOrganizationUpdateRor->save();
        }
                                
    }    
}
