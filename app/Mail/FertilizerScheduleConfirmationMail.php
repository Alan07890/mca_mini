<?php

namespace App\Mail;

use App\Models\FertilizerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FertilizerScheduleConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FertilizerApplication $application,
        public string $userName
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CARDO-FER - Fertilizer Schedule Confirmed'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fertilizer-schedule-confirmation',
            with: [
                'application' => $this->application,
                'userName' => $this->userName,
            ]
        );
    }
}