<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendingDocsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function build()
    {
        return $this->subject('Document Tracking – New Pendency for branch action')
                    ->html($this->html);
    }
}