<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    public static function add($action, $module, $description)
    {
        ActivityLog::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }
}