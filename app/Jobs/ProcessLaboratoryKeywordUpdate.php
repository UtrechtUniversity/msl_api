<?php

namespace App\Jobs;

use App\Mappers\Helpers\KeywordHelper;
use App\Models\Laboratory;
use App\Models\LaboratoryKeyword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLaboratoryKeywordUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $laboratory;

    protected $keywordHelper;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Laboratory $laboratory)
    {
        $this->laboratory = $laboratory;

        $this->keywordHelper = new KeywordHelper;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $keywords = $this->keywordHelper->extractFromText($this->laboratory->description, true);

        foreach ($keywords as $keyword) {
            LaboratoryKeyword::create([
                'laboratory_id' => $this->laboratory->id,
                'value' => $keyword->value,
                'uri' => $keyword->uri,
            ]);
        }

    }
}
