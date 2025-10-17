<?php

namespace App\Jobs;

use App\Models\LaboratoryOrganization;
use App\Models\LaboratoryOrganizationUpdateGroupRor;
use App\Models\LaboratoryOrganizationUpdateRor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryOrganizationUpdateGroupRor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $laboratoryOrganizationUpdateGroupRor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LaboratoryOrganizationUpdateGroupRor $laboratoryOrganizationUpdateGroupRor)
    {
        $this->laboratoryOrganizationUpdateGroupRor = $laboratoryOrganizationUpdateGroupRor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $organizations = LaboratoryOrganization::whereNotNull('external_identifier')->where('external_identifier', '<>', '')->get();

        foreach ($organizations as $organization) {
            $laboratoryOrganizationUpdateRor = LaboratoryOrganizationUpdateRor::create([
                'laboratory_organization_update_group_ror_id' => $this->laboratoryOrganizationUpdateGroupRor->id,
                'laboratory_organization_id' => $organization->id,
            ]);

            ProcessLaboratoryOrganizationUpdateRor::dispatch($laboratoryOrganizationUpdateRor);
        }
    }
}
