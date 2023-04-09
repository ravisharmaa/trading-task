<?php

namespace App\Mail;

use App\Http\ValueObjects\MailMessageValueObject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendStatisticsEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly MailMessageValueObject $mailMessageValueObject
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailMessageValueObject->getCompany()
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.statics',
            with: [
                'start_date' => $this->mailMessageValueObject->getStartDate(),
                'end_date' => $this->mailMessageValueObject->getEndDate(),
//                'prices' => $this->mailMessageValueObject->getPrices()
            ]
        );
    }
}
