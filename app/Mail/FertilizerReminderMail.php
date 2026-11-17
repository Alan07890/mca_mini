<?php

namespace App\Mail;

use App\Models\FertilizerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FertilizerReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FertilizerApplication $application,
        public string $userName,
        public string $reminderType = 'due',
        public int $daysUntilDue = 0
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->reminderType === 'advance'
            ? 'CARDO-FER - Upcoming Fertilizer Application'
            : 'CARDO-FER - Fertilizer Application Due Today';

        return new Envelope(
            subject: $subject
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fertilizer-reminder',
            with: [
                'application' => $this->application,
                'userName' => $this->userName,
                'reminderType' => $this->reminderType,
                'daysUntilDue' => $this->daysUntilDue,
            ]
        );
    }
}