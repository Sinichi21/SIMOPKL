<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\Awardee;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Complaindt;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\File;
use App\Mail\UserApproveMail;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Exports\UsersExport;
use App\Mail\UserRejectedMail;
use Illuminate\Support\Facades\DB;

// use App\Events\UserCreated;

class UserController extends Controller
{
    // Landing Page Index Awardee
    public function landingPageIndex(Request $request)
    {
        $faculties = Faculty::all();
        $years = range(2021, date('Y'));

        $usersQuery = User::join('awardees', 'users.id', '=', 'awardees.user_id')
            ->where('users.status', 1)
            ->where('users.is_registered', 1)
            ->orderBy('awardees.fullname', 'asc')
            ->select('users.*');

        if ($request->filled('search')) {
            $search = $request->search;
            $usersQuery->whereHas('awardee', function ($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $year = $request->year;
            $usersQuery->whereHas('awardee', function ($q) use ($year) {
                $q->where('year', $year);
            });
        }

        if ($request->filled('faculty')) {
            $faculty = $request->faculty;
            $usersQuery->whereHas('awardee.studyProgram', function ($q) use ($faculty) {
                $q->where('faculty_id', $faculty);
            });
        }

        $users = $usersQuery->paginate(10);

        $users->appends($request->except('page'));

        return view('landing-page.awardee.index', compact('users', 'faculties', 'years'));
    }

    //Landing Page Show Detail
    public function landingPageShow(User $user)
    {
        return view('landing-page.awardee.show', compact('user'));
    }

    //User Section
    public function index(Request $request)
    {
        $facultyId = $request->get('faculty_id');
        $studyProgramId = $request->get('study_program_id');

        $users = User::whereHas('Role', function ($query) {
            $query->whereIn('title', ['admin', 'awardee']);
        })
            ->whereHas('awardee')
            ->where('is_registered', 1)
            ->when($facultyId, function ($query) use ($facultyId) {
                return $query->whereHas('awardee.studyProgram.faculty', function ($query) use ($facultyId) {
                    $query->where('id', $facultyId);
                });
            })
            ->when($studyProgramId, function ($query) use ($studyProgramId) {
                return $query->whereHas('awardee.studyProgram', function ($query) use ($studyProgramId) {
                    $query->where('id', $studyProgramId);
                });
            })
            ->with('awardee.studyProgram.faculty')
            ->orderBy('id', 'desc')
            ->get();

        $totalUsers = User::where('role_id', '!=', 1)->whereHas('awardee')->where('is_registered', '=', 1)->count();
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();
        $totalAwardees = User::whereHas('role', function ($query) {
            $query->whereIn('title', ['awardee']);
        })->where('is_registered', '=', 1)->count();
        $totalAdminAwardees = User::join('awardees', 'users.id', '=', 'awardees.user_id')
            ->where('users.role_id', 3)
            ->where('users.is_registered', '=', 1)
            ->select('users.*')
            ->count();

        return view('admin.user.index')->with([
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalAwardees' => $totalAwardees,
            'totalAdminAwardees' => $totalAdminAwardees,
            'faculties' => $faculties,
            'studyPrograms' => $studyPrograms,
            'facultyId' => $facultyId,
            'studyProgramId' => $studyProgramId
        ]);
    }

    public function create()
    {
        $awardees = Awardee::all();
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();
        $years = range(2021, date('Y'));
        return view('admin.user.create')->with([
            'faculties' => $faculties,
            'studyPrograms' => $studyPrograms,
            'awardees' => $awardees,
            'years' => $years,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email', 'min:1', 'max:255',
                Rule::unique('users', 'email')->where('is_registered', '=', true)
            ],
            'username' => ['required', 'string', 'min:1', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'max:255', 'confirmed'],
            'bpiNumber' => ['required', 'numeric', 'digits_between:1,20'],
            'degree' => ['required', 'string', Rule::in(['S1', 'S2', 'S3'])],
            'phoneNumber' => ['required'],
            'studyProgramId' => ['required', 'numeric', 'exists:study_programs,id'],
            'year' => ['required', 'numeric'],
            'bukti_pendaftaran' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'siak_ktm' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
        ]);

        // // Check if the user has been soft deleted
        // $user = User    ::onlyTrashed()->where('email', $request->input('email'))->first();

        // if ($user) {
        //     // Restore the user
        //     $user->restore();

            // // Update the user with new details if needed
            // $user->update([
            //     'password' => Hash::make($validatedData['password']),
            //     'role_id' => Role::where('title', 'awardee')->first()->id,
            //     'status' => $validatedData['status'],
            // ]);

            // Update or create the awardee associated with the user
            // $user->awardee()->updateOrCreate(
            //     ['user_id' => $user->id],
            //     [
            //         'fullname' => $validatedData['fullname'],
            //         'username' => $validatedData['username'],
            //         'bpi_number' => $validatedData['bpiNumber'],
            //         'degree' => $validatedData['degree'],
            //         'phone_number' => $validatedData['phoneNumber'],
            //         'study_program_id' => $validatedData['studyProgramId'],
            //         'year' => $validatedData['year']
            //     ]
            // );
        // } else {
            // Create a new user
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => 2, // Set role_id to 2 by default
                'status' => $validatedData['status'],
            ]);

            $buktiPendaftaranPath = $request->file('bukti_pendaftaran')->store('uploads/bukti_pendaftaran', 'public');
            $siakKtmPath = $request->file('siak_ktm')->store('uploads/siak_ktm', 'public');

            // Create a new awardee associated with the user
            Awardee::create([
                'fullname' => $validatedData['fullname'],
                'username' => $validatedData['username'],
                'bpi_number' => $validatedData['bpiNumber'],
                'degree' => $validatedData['degree'],
                'phone_number' => $validatedData['phoneNumber'],
                'user_id' => $user->id,
                'study_program_id' => $validatedData['studyProgramId'],
                'year' => $validatedData['year'],
                'bukti_pendaftaran' => $buktiPendaftaranPath,
                'siak_ktm' => $siakKtmPath,
            ]);
        // }

        // Generate token reset password
        $token = Password::createToken($user);

        // Kirim email notifikasi
        Mail::to($user->email)->send(new UserCreatedMail($validatedData['email'], $validatedData['password'], $validatedData['fullname'], $token));

        Log::info('User baru ditambahkan', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil ditambahkan'
        ], 201);
    }


    public function show(User $user)
    {
        return view('admin.user.show')->with('user', $user);
    }

    public function showApprove(User $user)
    {
        return view('admin.user.show_approve')->with('user', $user);
    }

    public function profile(User $user)
    {
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();

        if (Auth::user()->id == $user->id) return view('awardee.awardeeProfile', compact('user', 'faculties', 'studyPrograms'));

        abort(403);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'userId' => ['required', 'numeric', 'exists:users,id'],
            'fullname' => ['required', 'string', 'min:1', 'max:255'],
            'username' => ['required', 'string', 'min:1', 'max:255'],
            'degree' => ['required', 'string', Rule::in(['s1', 's2', 's3'])],
            'phoneNumber' => ['required'],
            'studyProgramId' => ['required', 'numeric', 'exists:study_programs,id'],
            'year' => ['required', 'numeric'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
            'ppImg' => [File::types(['image/png', 'image/jpeg'])->min('1kb')->max('10mb')]
        ]);

        $user = User::findOrFail($request->userId);
        $StudyProgram = StudyProgram::find($request->studyProgramId);

        if (Auth::user()->id != $user->id) abort(403);

        $user->awardee->fullname = $request->fullname;
        $user->awardee->username = $request->username;
        $user->awardee->degree = $request->degree;
        $user->awardee->phone_number = $request->phoneNumber;
        $user->awardee->study_program_id = $request->studyProgramId;
        $user->awardee->year = $request->year;
        $user->status = $request->status;

        // Simpan profile picture jika ada
        if ($request->hasFile('ppImg')) {
            $file = $request->file('ppImg');
            $path = $file->store('profile-pictures', 'public');
            $user->pp_url = $path;
        }

        // Perbarui detail complaint jika status complaint tidak close dan complaint tidak null
        if ($user->awardee->complaint && $user->awardee->complaint->status != 'close') {
            $user->awardee->complaint->fullname = $request->fullname;
            $user->awardee->complaint->faculty = $StudyProgram->faculty->name;
            $user->awardee->complaint->study_program = $StudyProgram->name;
            $user->awardee->complaint->degree = $request->degree;
            $user->awardee->complaint->username = $request->username;
        }

        $user->save();
        $user->awardee->save();
        if ($user->awardee->complaint) {
            $user->awardee->complaint->save();
        }

        return back()->with('success', 'Berhasil menyimpan profil');
    }

    public function resetPassword(User $user)
    {
        if (auth()->user()->id != $user->id) {
            return abort(403);
        }

        return view('awardee.awardeeResetPassword')->with('user', $user);
    }

    public function resetPasswordStore(Request $request)
    {
        $request->validate([
            'userId' => ['required', 'numeric', 'exists:users,id'],
            'oldPassword' => ['required', 'string', 'min:1', 'max:255'],
            'newPassword' => ['required', 'string', 'min:1', 'max:255', 'confirmed'],
        ]);

        $user = User::findOrFail($request->userId);

        // Cek apakah user is authorized
        if (auth()->user()->id != $user->id) {
            return abort(403);
        }

        // Cek password lama
        if (!Hash::check($request->oldPassword, $user->password)) {
            return back()->withErrors(['error' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        $link = '<a href="' . route('awardee.profile', ['user' => $user->id]) . '">Klik untuk kembali</a>';
        return back()->with('success', 'Ganti password berhasil. ' . $link);
    }

    public function edit(User $user)
    {
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();
        $years = range(2021, date('Y'));

        return view('admin.user.edit')->with([
            'faculties' => $faculties,
            'studyPrograms' => $studyPrograms,
            'user' => $user,
            'years' => $years,
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'numeric', 'exists:users,id'],
            'fullname' => ['required', 'string', 'min:1', 'max:255'],
            'email' => ['required', 'email'],
            'username' => ['sometimes', 'string', 'min:1', 'max:255'],
            'bpiNumber' => ['sometimes', 'numeric', 'digits_between:1,20'],
            'degree' => ['sometimes', 'string', Rule::in(['S1', 'S2', 'S3'])],
            'phoneNumber' => ['sometimes'],
            'studyProgramId' => ['sometimes', 'numeric', 'exists:study_programs,id'],
            'year' => ['sometimes'],
            'bukti_pendaftaran' => ['sometimes', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:1024'],
            'siak_ktm' => ['sometimes', 'file', 'mimes:pdf,jpg,png,jpeg', 'max:1024'],
            'status' => ['required', 'integer', Rule::in([0, 1])],
        ]);

        // Ambil data user
        $user = User::findOrFail($request->id);

        // Ambil data admin, awardee studyProgram
        $admin = Admin::where('user_id', $user->id)->first();
        $awardee = Awardee::where('user_id', $user->id)->first();
        $StudyProgram = StudyProgram::find($validatedData['studyProgramId']);

        // Perbarui detail user
        $user->email = $validatedData['email'];
        $user->status = $validatedData['status'];
        $user->save();

        // Perbarui detail admin jika data admin tidak null
        if ($admin) {
            $admin->fullname = $validatedData['fullname'];
            $admin->save();
        }

        // Perbarui detail awardee jika data awardee tidak null
        if ($awardee) {
            $awardee->fullname = $validatedData['fullname'];
            $awardee->username = $validatedData['username'];
            $awardee->bpi_number = $validatedData['bpiNumber'];
            $awardee->degree = $validatedData['degree'];
            $awardee->phone_number = $validatedData['phoneNumber'];
            $awardee->study_program_id = $validatedData['studyProgramId'];
            $awardee->year = $validatedData['year'];

            // Proses penyimpanan berkas bukti pendaftaran
            if ($request->hasFile('bukti_pendaftaran')) {
                if ($awardee->bukti_pendaftaran && Storage::disk('public')->exists($awardee->bukti_pendaftaran)) {
                    Storage::disk('public')->delete($awardee->bukti_pendaftaran);
                }
                $awardee->bukti_pendaftaran = $request->file('bukti_pendaftaran')->store('uploads/bukti_pendaftaran', 'public');
            }

            // Proses penyimpanan berkas siak_ktm
            if ($request->hasFile('siak_ktm')) {
                if ($awardee->siak_ktm && Storage::disk('public')->exists($awardee->siak_ktm)) {
                    Storage::disk('public')->delete($awardee->siak_ktm);
                }
                $awardee->siak_ktm = $request->file('siak_ktm')->store('uploads/siak_ktm', 'public');
            }

            $awardee->save();
        }

        // Perbarui detail complaint jika status complaint tidak close
        if ($awardee->complaint && $awardee->complaint->status != 'close') {
            $awardee->complaint->fullname = $validatedData['fullname'];
            $awardee->complaint->bpi_number = $validatedData['bpiNumber'];
            $awardee->complaint->faculty = $StudyProgram->faculty->name;
            $awardee->complaint->study_program = $StudyProgram->name;
            $awardee->complaint->email = $validatedData['email'];
            $awardee->complaint->degree = $validatedData['degree'];
            $awardee->complaint->username = $validatedData['username'];
            $awardee->complaint->save();
        }

        // Log::info('User diupdate', [
        //     'user_id' => $user->id,
        //     'email' => $user->email,
        //     'status' => $user->status
        // ]);

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil diedit'
        ], 201);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users,id']
        ]);

        $user = User::findOrFail($request->id);
        // $awardee = Awardee::where('user_id', $user->id);

        $user->is_registered = 0;
        // foreach ($user->awardee->complaints as $complaint) {
        //     $complaint->status = 'close';
        //     $complaint->save();
        // }

        $user->save();

        Log::warning('User dinonaktifkan', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil dihapus'
        ]);
    }

    public function reject(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'note' => 'required|string|max:255',
            ]);

            $user = User::findOrFail($request->user_id);
            $note = $request->note;
            $awardee = $user->awardee;

            Log::info('Proses penolakan akun dimulai', [
                'user_id' => $user->id,
                'email' => $user->email,
                'note' => $note,
            ]);

            Mail::to($user->email)->send(new UserRejectedMail($awardee, $note));

            DB::table('awardees')->where('user_id', $user->id)->delete();
            DB::table('users')->where('id', $user->id)->delete();

            Log::info('Email penolakan dikirim ke user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return response()->json(['msg' => 'User berhasil di-reject, email terkirim, dan data dihapus dari list approve.']);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat penolakan akun', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'note' => $request->note,
            ]);

            return response()->json(['msg' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users,id']
        ]);

        $user = User::findOrFail($request->id);

        $currStatus = $user->role_id;

        // Toggle faq status based on current status
        if ($currStatus == 2) {
            $user->role_id = 3;
        } elseif($currStatus == 3) {
            $user->role_id = 2;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'msg' => 'Status berhasil diupdate'
        ]);
    }

    public function approveIndex(Request $request)
    {
        $facultyId = $request->get('faculty_id');
        $studyProgramId = $request->get('study_program_id');

        $users = User::whereHas('Role', function ($query) {
                $query->whereIn('title', ['default']);
            })
            ->where('is_registered', 1)
            ->when($facultyId, function ($query) use ($facultyId) {
                return $query->whereHas('awardee.studyProgram.faculty', function ($query) use ($facultyId) {
                    $query->where('id', $facultyId);
                });
            })
            ->when($studyProgramId, function ($query) use ($studyProgramId) {
                return $query->whereHas('awardee.studyProgram', function ($query) use ($studyProgramId) {
                    $query->where('id', $studyProgramId);
                });
            })
            ->with('awardee.studyProgram.faculty')
            ->get();

        $totalUsers = $users->count();
        $faculties = Faculty::all();
        $studyPrograms = StudyProgram::all();

        return view('admin.user.approve', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'faculties' => $faculties,
            'studyPrograms' => $studyPrograms,
            'facultyId' => $facultyId,
            'studyProgramId' => $studyProgramId
        ]);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:users,id']
        ]);

        $user = User::findOrFail($request->id);

        Mail::to($user->email)->send(new UserApproveMail($user->awardee->fullname));

        $user->update(['role_id' => 2]);

        Log::info('User berhasil disetujui', ['user_id' => $user->id, 'email' => $user->email]);

        return response()->json([
            'success' => true,
            'msg' => 'User berhasil diapprove'
        ]);
    }

    public function exportExcel(Request $request)
    {
        $users = User::whereHas('role', function ($query) {
                $query->whereIn('title', ['admin', 'awardee']);
            })
            ->whereHas('awardee')
            ->where('is_registered', 1)
            ->orderBy('id', 'asc')
            ->get();

        return Excel::download(new UsersExport($users), 'users.xlsx');
    }

}
