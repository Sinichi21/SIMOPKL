<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\RegistrationDocument;
use App\Models\Mitra;
use App\Models\Periode;
use App\Models\Register;
use App\Models\Awardee;
use App\Models\PklTimeline;
use App\Models\User;
use App\Models\StudyProgram;
use App\Models\Faculty;
use Carbon\Carbon;

class ApiPklController extends Controller
{
    public function getDocument()
    {
        $documents = Document::with('documentType')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'documents' => $documents
        ], 200);
    }

    public function documentTypes()
    {
        $documentTypes = DocumentType::where('required', 0)->select('id', 'name')->get();

        return response()->json([
            'document_types' => $documentTypes
        ], 200);
    }

    public function docomentStore(Request $request)
    {
        $validated = $request->validate([
            'document_type_id' => ['required'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $letter = Document::create([
            'user_id' => Auth::id(),
            'document_type_id' => $validated['document_type_id'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan surat berhasil dikirim',
            'data' => $letter
        ], 201);
    }

    // Admin Approve Document
    public function documentApprove(Request $request, $id)
    {
        $validated = $request->validate([
            'document' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,png', 'max:10240'],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);
        $document = Document::findOrFail($id);

        $document->status = 'Disetujui';
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents/approved', 'public');

            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
            $document->file_path = $path;
        } else {
            $path = $document->file_path; // Keep the existing file path if no new file is uploaded
        }

        $document->admin_note = $request->input('admin_note');;
        $document->save();

        return response()->json([
            'message' => 'Dokumen berhasil diperbarui',
            'data' => $document,
        ], 200);
    }

    // Admin Reject Document
    public function documentReject(Request $request, Document $document)
    {
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:500'],
        ]);

        $document->update([
            'status' => 'Ditolak',
            'admin_note' => $validated['admin_note'],
        ]);

        return response()->json([
            'message' => 'Surat ditolak',
            'data' => $document
        ]);
    }

    public function documentIndex()
    {
        $document = Document::all();

        return response()->json([
            'document' => $document
        ], 200);
    }

    public function documentShow($id)
    {
        $document = Document::findOrFail($id);

        return response()->json([
            'document' => $document
        ], 200);
    }

    //Registration

    //Registration Index
    public function registrationIndex()
    {
        $awardee = Auth::user()->awardee;

        $mitras = Mitra::where('status', 1)->get();
        $periods = Periode::where('status', 1)->get();

        if (!$awardee) {
            return response()->json(['message' => 'Awardee tidak ditemukan'], 404);
        }

        // Ambil pendaftaran yang aktif (pending atau disetujui)
        $register = $awardee->registers()
            ->whereIn('status', ['pending', 'Diterima'])
            ->latest()
            ->first();

        if (!$register) {
            return response()->json([
                'progress' => 0,
                'message' => 'Belum ada pendaftaran aktif'
            ], 200);
        }

        // Jika status pendaftaran ditolak
        if ($register->status === 'Ditolak') {
            return response()->json([
                'progress' => 0,
                'status_registrasi' => $register->status,
                'message' => 'Pendaftaran Anda ditolak. Silakan ajukan pendaftaran baru.'
            ], 200);
        }

        // Ambil dokumen milik pendaftaran aktif
        $documents = RegistrationDocument::where('registration_id', $register->id)
            ->with('documentType')
            ->get();

        return response()->json([
            'progress' => $register->calculateProgress(),
            'status_registrasi' => $register->status,
            'documents' => $documents,
            'mitras' => $mitras,
            'periods' => $periods,
            'message' => 'Silahkan lengkapi pendaftaran Anda'
        ], 200);
    }


    //Registration Form Store 
    public function registrationFormStore(Request $request) 
    {
        $user = Auth::user();
        $awardee = auth('api')->user()->awardee;

        $validated = $request->validate([
            'periode_id' => ['required', 'exists:periodes,id'],
            'mitra_id' => ['required', 'exists:mitras,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        // Ambil periode yang sedang diajukan
        $periode = Periode::find($validated['periode_id']);

        if ($awardee->hasActiveRegistration($validated['periode_id']) && $periode->status == 1) {
            return response()->json([
                'message' => 'Anda sudah melakukan pendaftaran PKL pada periode aktif ini. Tunggu keputusan atau gunakan periode lain.',
                'status' => 'blocked'
            ], 403);
        }

        $registration = Register::create([
            'registration_number' => now()->format('YmdHis'),
            'awardee_id' => $awardee->id,
            'fullname' => $awardee->fullname,
            'nim' => $awardee->nim,
            'faculty' => $awardee->studyProgram->faculty->id,
            'study_program' => $awardee->studyProgram->id,
            'email' => $awardee->user->email,
            'periode_id' => $validated['periode_id'],
            'mitra_id' => $validated['mitra_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Form registrasi berhasil disimpan',
            'data' => $registration
        ], 201);
    }

    //Registration Document Store
    public function RegistrationDocumentStore(Request $request) 
    {
        $awardee = Auth::user()->awardee;

        if (!$awardee) {
            return response()->json(['message' => 'Awardee tidak ditemukan'], 404);
        }

        $register = $awardee->registers()->latest()->first();

        if (!$register) {
            return response()->json(['message' => 'Belum ada data pendaftaran. Isi form registrasi dulu.'], 400);
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1024'],
            'document_type_id' => ['required'],
        ]);

        // Cek apakah sudah ada dokumen sebelumnya untuk registration ini
        $existingDoc = RegistrationDocument::where('registration_id', $register->id)
            ->where('document_type_id', $validated['document_type_id'])
            ->first();

        $file = $request->file('file')->store('documents/registration', 'public');

        // Jika dokumen sebelumnya *ditolak*, maka update row lama
        if ($existingDoc && $existingDoc->status === 'Ditolak') {

            // Hapus file lama jika ada
            if ($existingDoc->file_path && Storage::disk('public')->exists($existingDoc->file_path)) {
                Storage::disk('public')->delete($existingDoc->file_path);
            }

            // Update dokumen lama
            $existingDoc->update([
                'file_path' => $file,
                'status' => 'pending'
            ]);

            return response()->json([
                'message' => 'Dokumen diperbarui karena dokumen sebelumnya ditolak. Status kembali ke pending.',
                'document' => $existingDoc
            ], 200);
        }

        // Jika tidak ada dokumen sebelumnya, buat yang baru
        $document = RegistrationDocument::create([
            'registration_id' => $register->id,
            'document_type_id' => $validated['document_type_id'],
            'file_path' => $file
        ]);

        return response()->json([
            'message' => 'Dokumen registrasi berhasil disimpan',
            'document' => $document
        ], 201);
    }

    //Implementation

    //Implementation Index
    public function implementationIndex()
    {
        // Ambil data registrasi user
        $awardee = Auth::user()->awardee;
        $register = Register::where('awardee_id', $awardee->id)
            ->where('status', 'Diterima')
            ->latest()
            ->first();

        if (!$register) {
            return response()->json([
                'message' => 'Data registrasi tidak ditemukan'
            ], 404);
        }

        // Ambil semua dokumen terkait sekaligus
        $documents = RegistrationDocument::where('registration_id', $register->id)
            ->whereIn('document_type_id', [7, 13, 14])
            ->get()
            ->groupBy('document_type_id');

        // Ambil tiap dokumen
        $form_3a = $documents->get(7)?->first();
        $form_4b = $documents->get(13)?->first();
        $form_5a = $documents->get(14)?->first();

        $start = Carbon::parse($register->start_date);
        $end   = Carbon::parse($register->end_date);
        $today = Carbon::today();

        $totalWeeks = $start->diffInWeeks($end) + 1;

        if ($today->lt($start)) {
            $currentWeek = 0;
            $timeProgress = 0;
        } elseif ($today->gt($end)) {
            $currentWeek = $totalWeeks;
            $timeProgress = 100;
        } else {
            $currentWeek = $start->diffInWeeks($today) + 1;

            $totalDays = $start->diffInDays($end);
            $passedDays = $start->diffInDays($today);

            $timeProgress = round(($passedDays / $totalDays) * 100);
        }

        return response()->json([
            'start_date'       => $start->toDateString(),
            'end_date'         => $end->toDateString(),
            'current_week'     => $currentWeek,
            'total_weeks'      => $totalWeeks,

            'progress_time'    => $timeProgress,      // progres waktu
            'form_3a'          => $form_3a ?? null,
            'form_4b'          => $form_4b ?? null,
            'form_5a'          => $form_5a ?? null,
            
        ], 200);
    }

    public function getDocumentById($id)
    {
        $awardee = Auth::user()->awardee;

        if (!$awardee) {
            return response()->json(['message' => 'Awardee tidak ditemukan'], 404);
        }

        $register = $awardee->registers()->latest()->first();

        if (!$register) {
            return response()->json(['message' => 'Belum ada data pendaftaran. Isi form registrasi dulu.'], 400);
        }

        $document = RegistrationDocument::where('registration_id', $register->id)
            ->where('document_type_id', $id)
            ->with('documentType')
            ->first();

        return response()->json([
            'document' => $document
        ], 200);
    }

    public function getPeriods()
    {
        $periods = Periode::where('status', 1)
            ->get();

        return response()->json([
            'periods' => $periods
        ], 200);
    }

    public function getMitra()
    {
        $mitra = Mitra::where('status', 1)
            ->get();

        return response()->json([
            'mitra' => $mitra
        ], 200);
    }

    public function monevIndex()
    {
        // Ambil data registrasi user
        $awardee = Auth::user()->awardee;
        $register = Register::where('awardee_id', $awardee->id)
            ->where('status', 'Diterima')
            ->latest()
            ->first();

        if (!$register) {
            return response()->json([
                'message' => 'Data registrasi tidak ditemukan'
            ], 404);
        }

        // Ambil semua dokumen terkait sekaligus
        $documents = RegistrationDocument::where('registration_id', $register->id)
            ->whereIn('document_type_id', [9, 15, 16, 17])
            ->get()
            ->groupBy('document_type_id');

        // Ambil tiap dokumen
        $form_6b = $documents->get(9)?->first();
        $form_7a = $documents->get(15)?->first();
        $daftar_hadir_observasi = $documents->get(16)?->first();
        $draft_laporan_pengabdian = $documents->get(17)?->first();

        // Ambil timeline monev
        $timeline = $register->periode
        ->timelines()
        ->where('type', 'monev')
        ->orderBy('start_date')
        ->get();

        return response()->json([
            'timeline'       => $timeline ?? null,

            'form_6b'          => $form_6b ?? null,
            'form_7a'          => $form_7a ?? null,
            'daftar_hadir_observasi'          => $daftar_hadir_observasi ?? null,
            'draft_laporan_pengabdian'          => $draft_laporan_pengabdian ?? null,

        ], 200);
    }

    public function examFinalIndex()
    {
        // Ambil data registrasi user
        $awardee = Auth::user()->awardee;
        $register = Register::where('awardee_id', $awardee->id)
            ->where('status', 'Diterima')
            ->latest()
            ->first();

        if (!$register) {
            return response()->json([
                'message' => 'Data registrasi tidak ditemukan'
            ], 404);
        }

        // Ambil semua dokumen terkait sekaligus
        $documents = RegistrationDocument::where('registration_id', $register->id)
            ->whereIn('document_type_id', [20, 21, 22, 23, 24, 25])
            ->get()
            ->groupBy('document_type_id');

        // Ambil tiap dokumen
        $form_revisi_penguji = $documents->get(20)?->first();
        $nilai_ujian_pkl = $documents->get(21)?->first();
        $jurnal_final = $documents->get(22)?->first();
        $laporan_akhir = $documents->get(23)?->first();
        $berita_acara_ujian = $documents->get(24)?->first();
        $nilai_akhir = $documents->get(25)?->first();
        
        return response()->json([
            'form_revisi_penguji'          => $form_revisi_penguji ?? null,
            'nilai_ujian_pkl'          => $nilai_ujian_pkl ?? null,
            'jurnal_final'          => $jurnal_final ?? null,
            'laporan_akhir'          => $laporan_akhir ?? null,
            'berita_acara_ujian'          => $berita_acara_ujian ?? null,
            'nilai_Huruf'          => $register->value,
            'nilai_akhir'          => $nilai_akhir ?? null,
        ], 200);
    }

    public function examDraftIndex()
    {
        // Ambil data registrasi user
        $awardee = Auth::user()->awardee;
        $register = Register::where('awardee_id', $awardee->id)
            ->where('status', 'Diterima')
            ->latest()
            ->first();

        if (!$register) {
            return response()->json([
                'message' => 'Data registrasi tidak ditemukan'
            ], 404);
        }

        // Ambil semua dokumen terkait sekaligus
        $documents = RegistrationDocument::where('registration_id', $register->id)
            ->whereIn('document_type_id', [18, 17, 19])
            ->get()
            ->groupBy('document_type_id');

        // Ambil tiap dokumen
        $draft_jurnal_jupita = $documents->get(18)?->first();
        $draft_laporan_pengabdian = $documents->get(17)?->first();
        $permohonan_berita_acara = $documents->get(19)?->first();

        return response()->json([
            'draft_jurnal_jupita'          => $draft_jurnal_jupita ?? null,
            'draft_laporan_pengabdian'          => $draft_laporan_pengabdian ?? null,
            'permohonan_berita_acara'          => $permohonan_berita_acara ?? null,
        ], 200);
    }

    //Awardee Index
    public function awardeeIndexProgress()
    {
        $awardees = Awardee::whereHas('user', function ($q) {
            $q->where('status', 1);
        })
        ->with([
            'user',
            'studyProgram',
            'registers' => fn ($q) => $q->latest()
        ])
        ->get();

        $result = [];

        foreach ($awardees as $awardee) {
            $register = $awardee->registers->first();

            // =========================
            // BELUM DAFTAR
            // =========================
            if (!$register) {
                $result[] = [
                    'awardee_id' => $awardee->id,
                    'fullname'   => $awardee->fullname,
                    'nim'        => $awardee->nim,
                    'prodi'     => $awardee->studyProgram->name,
                    'status'     => 'belum_daftar',
                    'progress'   => 0
                ];
                continue;
            }

            // =========================
            // HITUNG PROGRES WAKTU
            // =========================
            $start = Carbon::parse($register->start_date);
            $end   = Carbon::parse($register->end_date);
            $today = Carbon::today();

            $totalDays  = max($start->diffInDays($end), 1);
            $passedDays = $start->diffInDays($today);

            $progress = match (true) {
                $today->lt($start) => 0,
                $today->gt($end)   => 100,
                default            => round(($passedDays / $totalDays) * 100)
            };

            // =========================
            // STATUS TAHAP PKL
            // =========================
            if ($register->status === 'pending') {
                $stage = 'pendaftaran';
            } elseif ($today->gt($end)) {
                $stage = 'selesai';
            } else {
                $docs = $register->registrationDocuments;

                // Cek apakah sudah ada dokumen monev atau ujian
                $hasMonev = $docs->whereIn('document_type_id', [9])->isNotEmpty();
                $hasUjian = $docs->whereIn('document_type_id', [18])->isNotEmpty();

                if ($hasUjian) {
                    $stage = 'ujian';
                } elseif ($hasMonev) {
                    $stage = 'monev';
                } else {
                    $stage = 'pelaksanaan';
                }
            }

            $result[] = [
                'awardee_id'  => $awardee->id,
                'fullname'    => $awardee->fullname,
                'nim'         => $awardee->nim,
                'register_id' => $register->id,
                'prodi'       => $awardee->studyProgram->name,
                'status'      => $stage,
                'progress'    => $progress,
                'start_date'  => $start->toDateString(),
                'end_date'    => $end->toDateString(),
            ];
        }

        return response()->json([
            'total_awardee' => count($result),
            'data' => $result
        ], 200);
    }

    // Admin Approve Document
    public function documentRegistrationApprove($id)
    {
        $document = RegistrationDocument::findOrFail($id);

        $document->update([
            'status' => 'Disetujui'
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil disetujui',
            'data' => $document
        ]);
    }

    // Admin Reject Document
    public function documentRegistrationReject($id)
    {
        $document = RegistrationDocument::findOrFail($id);

        $document->update([
            'status' => 'Ditolak'
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil ditolak',
            'data' => $document
        ]);
    }


    // Awardee Show
    public function awardeeShow($id)
    {
        $awardee = Awardee::with(['studyProgram', 'registers.registrationDocuments', 'registers.registrationDocuments.documentType', 'registers.mitra'])
            ->findOrFail($id);

        $register = $awardee->registers->first();

        if (!$register) {
            return response()->json([
                'awardee' => $awardee,
                'status' => 'belum_daftar',
                'progress' => 0
            ]);
        }

        $start = Carbon::parse($register->start_date);
        $end   = Carbon::parse($register->end_date);
        $today = Carbon::today();

        $totalDays  = max($start->diffInDays($end), 1);
        $passedDays = $start->diffInDays($today);

        $progress = match (true) {
            $today->lt($start) => 0,
            $today->gt($end)   => 100,
            default            => round(($passedDays / $totalDays) * 100)
        };

        $docs = $register->registrationDocuments;

        $stage = match (true) {
            $register->status === 'pending' => 'pendaftaran',
            $today->gt($end) => 'selesai',
            $docs->whereIn('document_type_id', [18])->isNotEmpty() => 'ujian',
            $docs->whereIn('document_type_id', [9])->isNotEmpty() => 'monev',
            default => 'pelaksanaan'
        };

        return response()->json([
            'awardee' => $awardee,
            'status' => $stage,
            'progress' => $progress
        ]);
    }


    // Awardee Detail Show
    public function awardeeDetailShow($id)
    {
        $awardee = Awardee::with(['studyProgram', 'registers.registrationDocuments', 'registers.registrationDocuments.documentType', 'registers.mitra'])
            ->findOrFail($id);
        $register = $awardee->registers->first();

        // =========================
            // HITUNG PROGRES WAKTU
            // =========================
            $start = Carbon::parse($register->start_date);
            $end   = Carbon::parse($register->end_date);
            $today = Carbon::today();

            $totalDays  = max($start->diffInDays($end), 1);
            $passedDays = $start->diffInDays($today);

            $progress = match (true) {
                $today->lt($start) => 0,
                $today->gt($end)   => 100,
                default            => round(($passedDays / $totalDays) * 100)
            };

            // =========================
            // STATUS TAHAP PKL
            // =========================
            if ($register->status === 'pending') {
                $stage = 'pendaftaran';
            } elseif ($today->gt($end)) {
                $stage = 'selesai';
            } else {
                // cek monev
                $hasMonev = RegistrationDocument::where('registration_id', $register->id)
                    ->whereIn('document_type_id', [9]) // contoh monev
                    ->exists();

                // cek ujian
                $hasUjian = RegistrationDocument::where('registration_id', $register->id)
                    ->whereIn('document_type_id', [18]) // contoh ujian
                    ->exists();

                if ($hasUjian) {
                    $stage = 'ujian';
                } elseif ($hasMonev) {
                    $stage = 'monev';
                } else {
                    $stage = 'pelaksanaan';
                }
            }

            $progressData = [
                'status'      => $stage,
                'progress'    => $progress,
                'start_date'  => $start->toDateString(),
                'end_date'    => $end->toDateString(),
            ];

        return response()->json([
            'awardee' => $awardee,
            'progressData' => $progressData,
        ], 200);
    }

    //Register Show
    public function awardeeMarkAsComplete($register)
    {
        $register = Register::with('mitra', 'periode', 'awardee', 'awardee.studyProgram')->findOrFail($register);

        return response()->json([
            'register' => $register
        ], 200);
    }

    // Admin Approve Registration Store
    public function adminUpdateRegisterStatus($id)
    {
        $register = Register::findOrFail($id);
        $register->status = 'Diterima';

        $register->save();

        return response()->json([
            'message' => 'Registrasi berhasil disetujui',
            'data' => $register,
        ], 200);
    }

    // Admin Reject Registration Store
    public function adminUpdateRegisterStatusReject($id)
    {
        $register = Register::findOrFail($id);
        $register->status = 'Ditolak';

        $register->save();

        return response()->json([
            'message' => 'Registrasi berhasil ditolak',
            'data' => $register,
        ], 200);
    }

    function studyProgramList()
    {
        $studyPrograms = StudyProgram::with('faculty')->get();

        return response()->json([
            'study_programs' => $studyPrograms
        ], 200);
    }

    public function awardeeDashboard()
    {
        $awardee = Auth::user()->awardee;

        if (!$awardee) {
            return response()->json([
                'message' => 'Awardee tidak ditemukan'
            ], 404);
        }

        // =========================
        // REGISTRASI AKTIF
        // =========================
        $register = $awardee->registers()
            ->whereIn('status', ['pending', 'Disetujui'])
            ->latest()
            ->with(['registrationDocuments', 'periode', 'mitra'])
            ->first();
        
        // =========================
        // PERIODE AKTIF (UNTUK BELUM DAFTAR)
        // =========================
        $activePeriode = Periode::where('status', 'aktif')
            ->with('timelines')
            ->first();

        // =========================
        // DEFAULT RESPONSE (BELUM DAFTAR)
        // =========================

        if (!$register) {

            $timeline = $activePeriode
                ? $activePeriode->timelines
                    ->sortBy('start_date')
                    ->values()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'title' => $item->title,
                            'type' => $item->type,
                            'start_date' => $item->start_date,
                            'end_date' => $item->end_date,
                            'status' => Carbon::parse($item->start_date)->isPast()
                                ? 'passed'
                                : 'upcoming'
                        ];
                    })
                : [];

            return response()->json([
                'progress' => [
                    'status' => 'belum_daftar',
                    'percentage' => 0,
                    'current_week' => 0,
                    'total_weeks' => 0
                ],
                'periode' => $activePeriode ? [
                    'id' => $activePeriode->id,
                    'name' => $activePeriode->name,
                    'start_date' => $activePeriode->start_date,
                    'end_date' => $activePeriode->end_date,
                ] : null,
                'mitra' => Mitra::select('id', 'partner_name', 'address')->get(),
                'timeline' => $timeline
            ]);
        }

        // =========================
        // HITUNG PROGRES WAKTU PKL
        // =========================
        $start = Carbon::parse($register->start_date);
        $end   = Carbon::parse($register->end_date);
        $today = Carbon::today();

        $totalWeeks = max($start->diffInWeeks($end) + 1, 1);

        if ($today->lt($start)) {
            $currentWeek = 0;
            $percentage = 0;
        } elseif ($today->gt($end)) {
            $currentWeek = $totalWeeks;
            $percentage = 100;
        } else {
            $currentWeek = $start->diffInWeeks($today) + 1;
            $totalDays  = max($start->diffInDays($end), 1);
            $passedDays = $start->diffInDays($today);
            $percentage = round(($passedDays / $totalDays) * 100);
        }

        // =========================
        // STATUS TAHAP PKL
        // =========================
        $docs = $register->registrationDocuments;

        $status = match (true) {
            $register->status === 'pending' => 'pendaftaran',
            $docs->whereIn('document_type_id', [18])->isNotEmpty() => 'ujian',
            $docs->whereIn('document_type_id', [9])->isNotEmpty() => 'monev',
            $today->gt($end) => 'selesai',
            default => 'pelaksanaan'
        };

        // =========================
        // TIMELINE (TERDEKAT DI ATAS)
        // =========================
        $timeline = $register->periode
        ->timelines()
        ->orderBy('start_date', 'asc')
        ->get()
        ->map(function ($item) use ($today) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'type' => $item->type,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'status' => Carbon::parse($item->start_date)->lt($today)
                    ? 'passed'
                    : 'upcoming'
            ];
        });

        return response()->json([
            'progress' => [
                'status' => $status,
                'percentage' => $percentage,
                'current_week' => $currentWeek,
                'total_weeks' => $totalWeeks
            ],
            'mitra' => Mitra::select('id','name','address')->get(),
            'timeline' => $timeline
        ]);
    }

    public function adminDashboard()
    {
        // =========================
        // DOKUMEN STATISTIK (GABUNGAN)
        // =========================
        $doc1 = Document::select(
            DB::raw("SUM(status = 'Disetujui') as approved"),
            DB::raw("SUM(status = 'Ditolak') as rejected"),
            DB::raw("SUM(status = 'pending') as pending"),
            DB::raw("COUNT(*) as total")
        )->first();

        $doc2 = RegistrationDocument::select(
            DB::raw("SUM(status = 'Disetujui') as approved"),
            DB::raw("SUM(status = 'Ditolak') as rejected"),
            DB::raw("SUM(status = 'pending') as pending"),
            DB::raw("COUNT(*) as total")
        )->first();

        // =========================
        // TOTAL MAHASISWA AKTIF
        // =========================
        $totalAwardee = Awardee::whereHas('user', fn ($q) => $q->where('status', 1))->count();

        // =========================
        // AKTIVITAS TERAKHIR (GABUNGAN)
        // =========================
        $latestDocuments = collect()

            // dari documents
            ->merge(
                Document::with(['user'])
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(function ($d) {
                        return [
                            'source'     => 'document',
                            'user'       => $d->user->name ?? '-',
                            'title'      => 'Dokumen Umum',
                            'status'     => $d->status,
                            'updated_at'=> $d->updated_at,
                        ];
                    })
            )

            // dari registration_documents
            ->merge(
                RegistrationDocument::with(['registration.awardee', 'documentType'])
                    ->latest()
                    ->limit(5)
                    ->get()
                    ->map(function ($d) {
                        return [
                            'source'     => 'registration_document',
                            'user'       => $d->registration->awardee->fullname ?? '-',
                            'title'      => $d->documentType->name ?? 'Dokumen PKL',
                            'status'     => $d->status,
                            'updated_at'=> $d->updated_at,
                        ];
                    })
            )
            ->sortByDesc('updated_at')
            ->take(5)
            ->values();

        // =========================
        // STATISTIK BULANAN
        // =========================
        $monthly = Register::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->map(fn ($m) => [
                'month' => Carbon::create()->month($m->month)->format('F'),
                'total_registration' => $m->total
            ]);

        return response()->json([
            'documents' => [
                'approved' => $doc1->approved + $doc2->approved,
                'rejected' => $doc1->rejected + $doc2->rejected,
                'pending'  => $doc1->pending  + $doc2->pending,
                'total'    => $doc1->total    + $doc2->total,
            ],
            'total_awardee' => $totalAwardee,
            'latest_activity' => $latestDocuments,
            'monthly_statistics' => $monthly
        ], 200);
    }
}

