<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'sender', 'to', 'cc', 'bcc', 'subject', 'message', 'status', 'sent_at'
    ];

    protected $dates = ['sent_at'];
}
