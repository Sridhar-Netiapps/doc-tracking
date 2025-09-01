<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'sender', 'to', 'cc', 'bcc', 'subject', 'message', 'status', 'sent_at', 'created_by'
    ];

    protected $dates = ['sent_at'];
}
