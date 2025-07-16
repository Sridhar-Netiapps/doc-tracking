<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\IntimationResponseMail;

use Mail;

class IntimationResponseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Intimation Response Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
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

    public static function sendThrottled($email, $mailData)
    {
       // $key = 'send-email:' . $email;
        $key = 'send-email:' . implode(',', $email);

        if (RateLimiter::tooManyAttempts($key, 2)) {  // Allow only 5 emails per minute
            return response()->json(['message' => 'Too many emails sent. Please try again later.'], 429);
        }

        RateLimiter::hit($key, 30); // Store rate limit for 60 seconds

        // Send email after passing throttle check
        Mail::to($email)->send(new IntimationResponseMail($mailData));
    }
}
