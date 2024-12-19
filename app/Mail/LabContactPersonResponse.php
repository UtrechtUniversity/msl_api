<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LabContactPersonResponse extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public string $receiver;
    /**
     * Create a new message instance.
     */
    public function __construct(array $data, string $receiver)
    {
        $this->data = $data;
        $this->receiver = $receiver;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {   

        if      ($this->receiver == 'user'){
            
            return new Envelope(
                subject: 'Laboratory Contact Person: confirmation email ',
            );
        } 
        elseif  ($this->receiver == 'server'){

            return new Envelope(
                subject: 'Laboratory Contact Person: ',
            );
        }

    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if      ($this->receiver == 'user'){

            return new Content(
                markdown: 'mails.labContactPersonResponseUser',
                with: [
                    'data' => $this->data
                ]
                
            );
        } 
        elseif  ($this->receiver == 'server'){

            return new Content(
                markdown: 'mails.labContactPersonResponseServer',
                with: [
                    'data' => $this->data
                ]
                
            );
        }

    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
