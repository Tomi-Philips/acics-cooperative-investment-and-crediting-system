<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The user's email.
     *
     * @var string
     */
    public $email;

    /**
     * The reset link.
     *
     * @var string
     */
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $email
     * @param string $resetLink
     * @return void
     */
    public function __construct($token, $email, $resetLink)
    {
        $this->token = $token;
        $this->email = $email;
        $this->resetLink = $resetLink;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'ACICS - Password Reset Request',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}