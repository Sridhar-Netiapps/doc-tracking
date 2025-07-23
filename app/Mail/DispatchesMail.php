<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DispatchesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $html;
    public $subject;


    public function __construct($html, $subject)
    {
        $this->html = $html;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)->html($this->html);
    }
}