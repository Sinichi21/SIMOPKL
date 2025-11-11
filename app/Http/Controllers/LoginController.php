<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\SocialMediaLink;

class LoginController extends Controller
{
    public function index()
    {
        return view('landing-page.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'password' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $user = User::where('email', $credentials['email'])
            ->first();

        if (!$user) {
            return back()->withErrors(['err' => 'Pengguna tidak ditemukan']);
        }

        if ($user->role->title == 'default') {
            return back()->withErrors(['err' => 'Akun belum diaktivasi']);
        }

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Return 'sukses';
            return redirect()->intended(route('index'));
        }

        // return 'gagal';
        return back()->withErrors([
            'err' => 'Email atau password tidak sesuai',
        ])->onlyInput('email');
    }

    public function adminLoginIndex()
    {
        return view('admin.auth.adminLogin');
    }

    public function authenticateAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'min:1', 'max:255'],
            'password' => ['required', 'string', 'min:1', 'max:255']
        ]);

        $adminRole = Role::where('title', 'admin')->first();

        $user = User::where('email', $credentials['email'])
            ->where('role_id', $adminRole->id)
            ->first();

        if (!$user) {
            return back()->withErrors(['err' => 'Pengguna tidak ditemukan']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.index'));
        }

        // return 'gagal';
        return back()->withErrors([
            'err' => 'Email atau password tidak sesuai',
        ]);
    }
}
