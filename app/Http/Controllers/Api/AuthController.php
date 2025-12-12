<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Awardee;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Mail\UserRegisterMail;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'nim' => ['required', 'numeric'],
            'degree' => ['required', Rule::in(['S1', 'S2', 'S3'])],
            'phoneNumber' => ['required'],
            'studyProgramId' => ['required', 'exists:study_programs,id'],
            'year' => ['required', 'numeric'],
        ]);

        try {
            // 2. Create User
            $defaultRole = Role::where('title', 'default')->first();

            $newUser = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']), // wajib hash
                'role_id' => $defaultRole->id,
            ]);
            // 3. Create Awardee
            $awardee = Awardee::create([
                'fullname' => $validatedData['fullname'],
                'username' => $validatedData['username'],
                'nim' => $validatedData['nim'],
                'degree' => $validatedData['degree'],
                'phone_number' => $validatedData['phoneNumber'],
                'user_id' => $newUser->id,
                'study_program_id' => $validatedData['studyProgramId'],
                'year' => $validatedData['year'],
            ]);
            // 4. Notify Admins
            $admins = User::where('role_id', 3)->get();
            $studyProgram = StudyProgram::with('faculty')->find($validatedData['studyProgramId']);

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new UserRegisteredMail(
                    $validatedData['fullname'],
                    $validatedData['email'],
                    $validatedData['degree'],
                    $studyProgram->faculty->name,
                    $studyProgram->name
                ));
            }
            // 5. Send Email to User
            Mail::to($newUser->email)->send(new UserRegisterMail($validatedData['fullname']));

            // 6. Return API JSON Response
            return response()->json([
                'message' => 'Registrasi berhasil. Cek email untuk informasi aktivasi akun.',
                'user' => $newUser,
                'awardee' => $awardee
            ], 201);

        } catch (\Exception $e) {
            Log::error('Register API Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // Login user and generate JWT token
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255']
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if (!$user->role || $user->role->title === 'default') {
            return response()->json([
                'status' => false,
                'message' => 'Akun belum diaktivasi'
            ], 403);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password tidak sesuai'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    // Logout user (invalidate token)
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    // Get current authenticated user profile
     public function profile()
    {
        $user = auth('api')->user();
        $awardee = auth('api')->user()->awardee;

        if (!$awardee) {
            return response()->json([
                'message' => 'Awardee tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'user' => $user,
        ]);
    }
}
