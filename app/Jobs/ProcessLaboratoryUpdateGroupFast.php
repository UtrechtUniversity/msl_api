<?php

namespace App\Jobs;

use App\Models\LaboratoryUpdateGroupFast;
use App\Services\FastHarvestingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryUpdateGroupFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast;

    /**
     * Create a new job instance.
     */
    public function __construct(LaboratoryUpdateGroupFast $laboratoryUpdateGroupFast)
    {
        $this->laboratoryUpdateGroupFast = $laboratoryUpdateGroupFast;
    }

    /**
     * Execute the job.
     */
    public function handle(FastHarvestingService $service): void
    {
        $service->removeExistingData();
        $service->retrieveFastData($this->laboratoryUpdateGroupFast);
    }


}
