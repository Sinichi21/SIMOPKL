<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

Route::get('/forgot-password', function () {
    return view('auth.request');
})->middleware('isGuest')->name('password.request');

// Route::get('/home', function () {
//     return view('auth.adminLogin');
// })->name('home');


Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    // Cari pengguna berdasarkan email dan is_registered = true
    $user = User::where('email', $request->email)
                ->where('is_registered', true)
                ->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak terdaftar atau telah dihapus.']);
    }

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('isGuest')->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset', ['token' => $token]);
})->middleware('isGuest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    // Cari pengguna berdasarkan email dan is_registered = true
    $user = User::where('email', $request->email)
                ->where('is_registered', true)
                ->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email tidak terdaftar atau telah dihapus.']);
    }

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        $user = User::where('email', $request->email)->first();

        // Periksa peran pengguna dan arahkan sesuai
        if ($user->role_id == 3 ) {
            return redirect()->route('admin.login')->with('status', __($status));
        } elseif ($user->role_id == 2 ) {
            return redirect()->route('login')->with('status', __($status));
        } else {
            // Arahkan default jika peran tidak sesuai dengan admin atau awardee
            return redirect()->route('login')->with('status', __($status));
        }
    } else {
        return back()->withErrors(['email' => [__($status)]]);
    }
})->middleware('isGuest')->name('password.update');

Route::prefix('admin')->group(function () {
    Route::post('/User/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        // Cari pengguna berdasarkan email dan is_registered = true
        $user = User::where('email', $request->email)
        ->where('is_registered', true)
        ->first();

    if (!$user) {
    return back()->withErrors(['email' => 'Email tidak terdaftar atau telah dihapus.']);
    }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    })->middleware('isAdmin')->name('admin.password.email');

    Route::get('/User/forgot-password', function () {
        return view('auth.forgot-password');
    })->middleware('isAdmin')->name('admin.password.request');


});
