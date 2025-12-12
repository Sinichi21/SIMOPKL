<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\RegistrationDocument;
use Illuminate\Support\Facades\Auth;
use App\Models\Mitra;
use App\Models\Periode;
use App\Models\Register;
use App\Models\Awardee;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\StudyProgram;
use App\Models\Faculty;

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

        if (!$awardee) {
            return response()->json(['message' => 'Awardee tidak ditemukan'], 404);
        }

        // Ambil pendaftaran yang aktif (pending atau disetujui)
        $register = $awardee->registers()
            ->whereIn('status', ['pending', 'Disetujui'])
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

}

