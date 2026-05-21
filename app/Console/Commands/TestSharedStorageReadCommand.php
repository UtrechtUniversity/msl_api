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
        if(Storage::disk('shared_storage')->exists('test/test.txt')) {
            $content = Storage::disk('shared_storage')->get('test/test.txt');
            if($content === 'Hello, world!') {
                $this->info('File read successfully!');
                return 0;
            }
        }
        $this->error('Failed to read file!');
        return 1;
    }
}
