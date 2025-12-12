<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiUserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $awardee = $user->awardee;

        if (!$awardee) {
            return response()->json([
                'message' => 'Awardee tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'fullname' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255'],
            'degree' => ['sometimes', Rule::in(['s1', 's2', 's3'])],
            'phoneNumber' => ['sometimes'],
            'studyProgramId' => ['sometimes', 'exists:study_programs,id'],
            'year' => ['sometimes', 'numeric'],
            'status' => ['sometimes', Rule::in([0, 1])],
            'ppImg' => ['sometimes', File::image()->max('10mb')],
        ]);

        DB::beginTransaction();

        try {
            $awardee->fill([
                'fullname' => $validated['fullname'] ?? $awardee->fullname,
                'username' => $validated['username'] ?? $awardee->username,
                'degree' => $validated['degree'] ?? $awardee->degree,
                'phone_number' => $validated['phoneNumber'] ?? $awardee->phone_number,
                'study_program_id' => $validated['studyProgramId'] ?? $awardee->study_program_id,
                'year' => $validated['year'] ?? $awardee->year,
            ]);

            if (isset($validated['status'])) {
                $user->status = $validated['status'];
            }

            if ($request->hasFile('ppImg')) {
                $path = $request->file('ppImg')->store('profile-pictures', 'public');
                $user->pp_url = $path;
            }

            if (
                $awardee->complaint &&
                $awardee->complaint->status !== 'close'
            ) {
                if (isset($validated['fullname'])) {
                    $awardee->complaint->fullname = $validated['fullname'];
                }
                if (isset($validated['username'])) {
                    $awardee->complaint->username = $validated['username'];
                }
                if (isset($validated['degree'])) {
                    $awardee->complaint->degree = $validated['degree'];
                }
                if (isset($validated['studyProgramId'])) {
                    $studyProgram = StudyProgram::with('faculty')->find($validated['studyProgramId']);
                    $awardee->complaint->faculty = $studyProgram->faculty->name;
                    $awardee->complaint->study_program = $studyProgram->name;
                }
                $awardee->complaint->save();
            }

            $user->save();
            $awardee->save();

            DB::commit();

            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'data' => [
                    'user' => $user->only(['id', 'email', 'pp_url', 'status']),
                    'awardee' => $awardee
                ]
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}

