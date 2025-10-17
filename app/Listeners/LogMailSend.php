<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogMailSend
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $from = $event->message->getFrom()[0]->getAddress();
        $to = $event->message->getTo()[0]->getAddress();
        $subject = $event->message->getSubject();
        $body = $event->message->getTextBody();

        $entry = "Mail send from: $from, to: $to, subject: $subject, body: $body";

        Log::channel('mail')->info($entry);
    }
}
