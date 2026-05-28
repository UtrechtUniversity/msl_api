<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestSharedStorageReadCommand extends Command
{
    protected $signature = 'app:test-shared-storage-read';

    protected $description = 'Test reading file from shared_storage disk';

    public function handle()
    {
        $this->info('Reading file from shared disk');

        if(!Storage::disk('shared_storage')->exists('test/test.txt')) {
            $this->fail('Failed to read file!');
        }

        if('Hello, world!' !== Storage::disk('shared_storage')->get('test/test.txt')) {
            $this->fail('Unexpected file content!');
        }

        $this->info('File read successfully!');
    }
}
