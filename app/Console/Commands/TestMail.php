<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-mail {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test e-mail to to address. Returns boolean success';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            Mail::raw('MSL data catalogue test email', function (Message $message) {
                $message->to($this->argument('to'))->subject('MSL data catalogue test email');
            });
        } catch(\Exception $e) {
            $this->line('false');
            exit;
        }

        $this->line('true');
    }
}
