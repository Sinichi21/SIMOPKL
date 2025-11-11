<?php

namespace App\Http\Controllers;

use App\Exports\ComplaintsExport;
use App\Models\Awardee;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Faq;
use App\Models\User;
use App\Notifications\NewComplaint;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserComplaintMail;

class ComplaintConctroller extends Controller
{
    public function awardeeIndex()
    {
        $user = Auth::user();

        // Superadmin tidak bisa masuk ke halaman pengaduan awardee
        if ($user->admin) {
            abort(403);
        }

        $complaints = Complaint::where('awardee_id', $user->awardee->id)->get();
        $complaintTypes = ComplaintType::all();

        return view('complaint.index')
            ->with('complaints', $complaints)
            ->with('complaintTypes', $complaintTypes);
    }

    public function adminIndex()
    {
        $complaints = Complaint::orderBy('created_at', 'desc')->get();
        $complaintTypes = ComplaintType::all();

        return view('admin.complaint.index')
            ->with('complaints', $complaints)
            ->with('complaintTypes', $complaintTypes);
    }

    public function create()
    {
        $complaintTypes = ComplaintType::all();
        return view('complaint.create')
            ->with('awardee', Auth::user()->awardee)
            ->with('complaintTypes', $complaintTypes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'awardeeId' => ['required', 'numeric', 'exists:awardees,id'],
            'complaintTypeId' => ['required', 'numeric', 'exists:complaint_types,id'],
            'content' => ['required', 'string'],
            'medias.*' => [File::types(['image/png', 'image/jpeg', 'application/pdf'])->min('1kb')->max('10mb')]
        ]);

        $awardee = Awardee::findOrFail($request->awardeeId);
        $complaintType = ComplaintType::findOrFail($request->complaintTypeId);

        $complaint = Complaint::create([
            'complaint_id' => now()->format('YmdHis'),
            'content' => $request->content,
            'awardee_id' => $request->awardeeId,
            'complaint_type_id' => $request->complaintTypeId,
            'fullname' => $awardee->fullname,
            'bpi_number' => $awardee->bpi_number,
            'faculty' => $awardee->studyProgram->faculty->name,
            'study_program' => $awardee->studyProgram->name,
            'email' => $awardee->user->email
        ]);

        // create complaint medias
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $media) {
                // Simpan ke server
                $path = $media->store('complaint-media', 'public');

                // Create record
                $complaint->complaintMedias()->create([
                    'url' => $path
                ]);
            }
        }

        // Create new thread
        $complaint->thread()->create();

        // Send notifications to admin
        $adminUsers = User::whereHas('role', function ($query) {
            $query->where('title', 'admin');
        })->get();

        Notification::send($adminUsers, new NewComplaint($complaint, 'admin'));

        // Send email to admin
        $admins = User::where('role_id', 3)->get();

        // foreach($admins as $admin) {
        //     Log::info('Kirim email ke: ' . $admin->email);
        //     Mail::to($admin->email)
        //     ->send(new UserComplaintMail(
        //         $awardee->fullname,
        //         $awardee->studyProgram->faculty->name,
        //         $awardee->bpi_number,
        //         $request->complaintTypeId
        //         ));
        //     Log::info('Email terkirim ke: ' . $admin->email);
        // }

        foreach($admins as $admin) {
            try {
                Mail::to($admin->email)
                    ->send(new UserComplaintMail(
                        $awardee->fullname,
                        $awardee->studyProgram->faculty->name,
                        $awardee->bpi_number,
                        $complaintType->title
                     ));
                Log::info('Email terkirim ke: ' . $admin->email);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email ke: ' . $admin->email . '. Error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'msg' => 'Aduan berhasil dibuat'
        ], 201);
    }

    public function show(Complaint $complaint)
    {
        $user = Auth::user();

        if ($complaint->awardee->id == $user->awardee->id) {
            return view('complaint.show')->with('complaint', $complaint);
        }

        abort(403);
    }

    public function adminShow(Complaint $complaint)
    {
        $user = Auth::user();

        if ($user->role->title == 'admin') {
            return view('admin.complaint.show')->with('complaint', $complaint);
        }

        abort(403);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:complaints,id']
        ]);

        $complaint = Complaint::findOrFail($request->id);

        $user = Auth::user();

        // hapus jika user owned complaint or user is admin
        if ($user->awardee->id ?? '' == $complaint->awardee->id || $user->role->title == 'admin') {

            // Hapus media complaint

            foreach ($complaint->complaintMedias as $media) {
                // Remove file from storage
                Storage::disk('public')->delete($media->url);
                // Delete the media record from database
                $media->delete();
            }

            // Hapus media thread
            foreach ($complaint->thread->chats as $chat) {
                foreach ($chat->medias as $media) {
                    $media->delete();
                }
                $chat->delete();
            }
            // Hapus thread
            $complaint->thread->delete();

            $complaint->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Aduan berhasil dihapus'
            ], 201);
        }

        return response()->json([
            'success' => false,
            'error' => 'Unauthorized'
        ], 401);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date']
        ]);

        $complaints = Complaint::where('created_at', '>=', $request->dateFrom)
            ->where('created_at', '<=', Carbon::createFromFormat('Y-m-d', $request->dateTo)->addDay())
            ->get();

        $data = array(
            'dateFrom' => $request->dateFrom,
            'dateTo' => $request->dateTo,
            'complaints' => $complaints,
        );

        $pdf = Pdf::loadView('admin.complaint.reportPdf', $data)->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function excel(Request $request)
    {
        $request->validate([
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date']
        ]);

        return Excel::download(new ComplaintsExport($request->dateFrom, $request->dateTo . ' 23:59:59'), 'complaints.xlsx');
    }
}
