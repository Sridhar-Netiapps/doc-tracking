<?php

namespace App;
use App\Models\AuditLog;
use Auth;

trait AuditLogTrait
{
    public function auditlogs($module , $operation , $note , $link)
    {
       AuditLog::create([
            'user_id' => Auth::user()->id,
            'module' => $module,
            'operation' => $operation,
            'note' => $note,
            'link' =>$link
        ]);
       
    }
}
