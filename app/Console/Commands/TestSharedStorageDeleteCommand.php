<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestSharedStorageDeleteCommand extends Command
{
    protected $signature = 'app:test-shared-storage-delete';

    protected $description = 'Test deleting file and directory from shared_storage disk';

    public function handle(): void
    {
        if(!Storage::disk('shared_storage')->exists('test/test.txt')) {
            $this->fail('File does not exist!');
        }

        Storage::disk('shared_storage')->delete('test/test.txt');
        if(Storage::disk('shared_storage')->exists('test/test.txt')) {
            $this->fail('Failed to delete file!');
        }

        $this->info('File deleted successfully!');

        Storage::disk('shared_storage')->deleteDirectory('test');

        if(Storage::disk('shared_storage')->exists('test')) {
            $this->fail('Failed to delete directory!');
        }

        $this->info('Directory deleted successfully!');
    }
}
