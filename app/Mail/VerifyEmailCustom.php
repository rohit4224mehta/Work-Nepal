<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class VerifyEmailCustom extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $verificationUrl;

    public function __construct($user, $verificationUrl)
    {
        $this->user = $user;
        $this->verificationUrl = $verificationUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('rohit4224mehta@gmail.com', 'WorkNepal'),
            replyTo: [
                new Address('rohit4224mehta@gmail.com', '"WorkNepal Support"'),  // Quoted for RFC compliance (spaces in name)
            ],
            subject: 'Verify Your WorkNepal Email Address',
            to: [
                new Address($this->user->email, $this->user->name ?? 'User'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.verify',
            with: [
                'name' => $this->user->name ?? 'User',
                'url' => $this->verificationUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}