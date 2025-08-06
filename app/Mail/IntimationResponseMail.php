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
    public $mailData;
    public $csvContent;
    public $fileName;

    public function __construct($mailData , $csvContent , $fileName)
    {
        $this->mailData = $mailData;
        $this->csvContent = $csvContent;
        $this->fileName = $fileName;
    }

    /**
     * Get the message envelope.
     */
    
    /**
     * Override build only to attach raw memory file
     */
    public function build()
    { 
        $newName = $this->fileName;
        
       
                if($newName != ''){
                     return $this->subject('Intimation Response Mail')
                        ->view('emails.welcome')
                        ->attach($this->csvContent, [
                            'as' => 'Intimation.xlsx',
                            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]);
                }
                else{
                     return $this->subject('Intimation Response Mail')
                        ->view('emails.welcome');
                }
                

    }

    public static function sendThrottled($reciepients , $mailData , $csvContent , $fileName )
    {
         $key = 'send-email:' . implode(',', $reciepients);


        if (RateLimiter::tooManyAttempts($key, 2)) {  // Allow only 5 emails per minute
            return response()->json(['message' => 'Too many emails sent. Please try again later.'], 429);
        }

        RateLimiter::hit($key, 30); // Store rate limit for 60 seconds

        // Send email after passing throttle check
        Mail::to($reciepients)->send(new IntimationResponseMail($mailData , $csvContent , $fileName));
    }
}
