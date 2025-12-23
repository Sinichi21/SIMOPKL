<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        ActivityLog::create([
        'user_id'    => Auth::id(),
        'action'     => 'Logout',
        'module'     => 'Authentication',
        'description'=> 'User logged out',
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
