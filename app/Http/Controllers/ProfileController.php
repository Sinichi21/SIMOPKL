<?php

namespace App\Http\Controllers;

use App\Models\Awardee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;

class AdminProfileController extends Controller
{
    public function index()
    {
        return view('profile.userProfile')->with('user', Auth()->user());
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'adminId' => ['required', 'numeric', 'exists:admins,id'],
            'userId' => ['required', 'numeric', 'exists:users,id'],
            'fullname' => ['required', 'string', 'min:1', 'max:255'],
            'ppImg' => [File::types(['image/png', 'image/jpeg'])->min('1kb')->max('10mb')]
        ]);

        if ($request->password) {
            $request->validate([
                'password' => ['string', 'confirmed', 'min:1', 'max:255'],
            ]);
        }

        $admin = Admin::findOrFail($validated['adminId']);
        $user = User::findOrFail($validated['userId']);

        $user->password = Hash::make($request->password);

        $admin->fullname = $validated['fullname'];

        // Simpan profile picture jika ada
        if ($request->hasFile('ppImg')) {
            $file = $request->file('ppImg');
            $path = $file->store('profile-pictures', 'public');
            $admin->pp_url = $path;
        }

        $user->save();
        $admin->save();

        return back()->with('msg', 'Berhasil menyimpan profil');
    }
}
