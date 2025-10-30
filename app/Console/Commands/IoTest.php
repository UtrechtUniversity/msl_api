<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class IoTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:io-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'benchmark reading files from public directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('reading: '.base_path('public/original.json'));
        $startTime = microtime(true);
        $file = File::get(base_path('public/original.json'));
        $endTime = microtime(true);
        $timeElapsed = ($endTime - $startTime);
        $this->line('Reading time: '.number_format($timeElapsed, 2));

        $this->line('reading: '.base_path('public/interpreted.json'));
        $startTime = microtime(true);
        $file = File::get(base_path('public/interpreted.json'));
        $endTime = microtime(true);
        $timeElapsed = ($endTime - $startTime);
        $this->line('Reading time: '.number_format($timeElapsed, 2));
    }
}
