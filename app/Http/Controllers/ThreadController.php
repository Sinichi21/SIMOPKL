<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\ThreadChat;
use App\Models\User;
use App\Notifications\NewThreadChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\File;
use App\Mail\NewThreadMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ThreadController extends Controller
{
    public function awardeeShow(Thread $thread)
    {
        $user = Auth::user();

        if ($user->id == $thread->complaint->awardee->user->id) {
            return view('thread.show')->with('thread', $thread);
        }

        abort(403);
    }

    public function adminShow(Thread $thread)
    {
        return view('admin.thread.show')->with('thread', $thread);
    }

    public function storeChat(Request $request)
    {
        $request->validate([
            'threadId' => ['required', 'numeric', 'exists:threads,id'],
            'chat' => ['required', 'string'],
            'medias.*' => [File::types(['image/png', 'image/jpeg', 'application/pdf'])->min('1kb')->max('10mb')]
        ]);

        $user = Auth::user();
        $thread = Thread::findOrFail($request->threadId);

        // Kalau thread bukan punya user atau user bukan admin
        if ($user->id != $thread->complaint->awardee->user->id && $user->role->title != 'admin') {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ]);
        }

        $threadChat = Auth::user()->chats()->create([
            'chat' => $request->chat,
            'thread_id' => $request->threadId,
            'by' => $request->by == 'awardee' ? 'awardee' : 'admin'
        ]);

        // create thread chat medias
        if ($request->hasFile('medias')) {
            foreach ($request->file('medias') as $media) {
                // Simpan ke server
                $path = $media->store('thread-media', 'public');

                // Create record
                $threadChat->medias()->create([
                    'url' => $path,
                    'filename' => basename($path)
                ]);
            }
        }

        // testing
        $testingAdminEmails = ['admin@bpi.ui.ac.id', 'gusdek@gmail.com', 'avatarsolution.crm+1@gmail.com'];

        // if ($request->by == 'awardee') {
        //     $adminUsers = User::whereHas('role', function ($query) {
        //         $query->where('title', 'admin');
        //     })->get();

        //     // Kirim email ke admin
        //     foreach ($adminUsers as $admin) {
        //         if ($admin->email == $testingAdminEmail) {
        //             Mail::to($admin->email)->send(new NewThreadMail($thread));
        //         }
        //         Notification::send($adminUsers, new NewThreadChat($thread, 'admin'));
        //     }

        //     // Log email pengiriman
        //     Log::info('Email notifications sent to admin users.', [
        //         'thread_id' => $thread->id,
        //         'admin_user_ids' => $adminUsers->pluck('id')->toArray()
        //     ]);
        // }

        $adminUsers = User::whereHas('role', function ($query) {
            $query->where('title', 'admin');
        })->get();

        foreach ($adminUsers as $admin) {
            if (in_array($admin->email, $testingAdminEmails)) {
                Mail::to($admin->email)->send(new NewThreadMail($thread));
            }
        }

        Notification::send($adminUsers, new NewThreadChat($thread, 'admin'));

        Log::info('Email notifications sent to admin users.', [
            'thread_id' => $thread->id,
            'admin_user_ids' => $adminUsers->pluck('id')->toArray()
        ]);

        if ($request->by == 'admin') {
            $awardee = $thread->complaint->awardee->user;

            Notification::send($awardee, new NewThreadChat($thread, 'awardee'));
        }

        return response()->json([
            'success' => true
        ]);
    }
}
