<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestSharedStorageWriteCommand extends Command
{
    protected $signature = 'app:test-shared-storage-write';

    protected $description = 'Test writing file to shared_storage disk';

    public function handle(): void
    {
        $this->info('Creating directory on shared disk');
        Storage::disk('shared_storage')->makeDirectory('test');

        $this->info('Writing file to shared disk');
        Storage::disk('shared_storage')->put('test/test.txt', 'Hello, world!');

        if(Storage::disk('shared_storage')->exists('test/test.txt')) {
            $this->info('File written successfully!');
        } else {
            $this->fail('Failed to write file!');
        }
    }
}
