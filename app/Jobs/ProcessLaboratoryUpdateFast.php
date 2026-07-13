<?php

namespace App\Jobs;

use App\Models\LaboratoryUpdateFast;
use App\Services\FastHarvestingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryUpdateFast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public LaboratoryUpdateFast $laboratoryUpdateFast;

    /**
     * Create a new job instance.
     */
    public function __construct(LaboratoryUpdateFast $laboratoryUpdateFast)
    {
        $this->laboratoryUpdateFast = $laboratoryUpdateFast;
    }

    /**
     * Execute the job.
     */
    public function handle(FastHarvestingService $service): void
    {
        $service->processFacilityUpdate($this->laboratoryUpdateFast);
    }
}
