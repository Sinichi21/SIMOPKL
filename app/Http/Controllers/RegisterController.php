<?php

namespace App\Http\Controllers;

use App\Models\Awardee;
use App\Models\Faculty;
use App\Models\Role;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\UserRegisterMail;
use App\Mail\UserRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function index()
    {
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();
        $years = range(2021, date('Y'));

        return view('landing-page.register', compact('faculties', 'studyPrograms', 'years'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'username' => ['required', 'string', 'min:1', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'max:255', 'confirmed'],
            'nim' => ['required', 'numeric', 'digits_between:1,20'],
            'degree' => ['required', 'string', Rule::in(array('S1', 'S2', 'S3'))],
            'phoneNumber' => ['required'],
            'studyProgramId' => ['required', 'numeric', 'exists:study_programs,id'],
            'year' => ['required', 'numeric'],
            'bukti_pendaftaran' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'siak_ktm' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
        ]);

        $defaultRole = Role::where('title', 'default')->first();

        $buktiPendaftaranPath = $request->file('transkrip_nilai')->store('uploads/transkrip_nilai', 'public');

        // Create user
        $newUser = User::create([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role_id' => $defaultRole->id,
            'status' => $validatedData['status'],
        ]);

        // Create awardee
        Awardee::create([
            'fullname' => $validatedData['fullname'],
            'username' => $validatedData['username'],
            'nim' => $validatedData['nim'],
            'degree' => $validatedData['degree'],
            'phone_number' => $validatedData['phoneNumber'],
            'user_id' => $newUser->id,
            'study_program_id' => $validatedData['studyProgramId'],
            'year' => $validatedData['year'],
            'bukti_pendaftaran' => $buktiPendaftaranPath,
            'siak_ktm' => $siakKtmPath,
        ]);

        // testing
        $testingAdminEmail = 'admin@bpi.ui.ac.id';

        $admins = User::where('role_id', 3)->get();
        $studyProgram = studyProgram::with('faculty')->find($validatedData['studyProgramId']);
        $facultyName = $studyProgram->faculty->name ?? 'Fakultas tidak ditemukan';
        $studyProgramName = $studyProgram->name ?? 'Program studi tidak ditemukan';

        foreach($admins as $admin) {
            if ($admin->email == $testingAdminEmail) {
                Log::info('Kirim email ke: ' . $admin->email);
                Mail::to($admin->email)
                //    ->cc('bpi.universitasindonesia@gmail.com')
                ->send(new UserRegisteredMail(
                    $request->fullname,
                    $request->email,
                    $request->degree,
                    $facultyName,
                    $studyProgramName
                    ));
            Log::info('Email terkirim ke: ' . $admin->email);
            }
        }

        Mail::to($newUser->email)->send(new UserRegisterMail($request->fullname));

        return redirect()->back()->with('success', 'Registrasi berhasil. Silahkan cek email berkala untuk informasi aktivasi akun');
    }
}
