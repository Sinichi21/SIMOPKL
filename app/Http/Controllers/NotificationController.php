<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function handleOpenNotification($id)
    {
        $notification = DatabaseNotification::find($id);

        if ($notification) {
            $notification->markAsRead();

            // Thread notification
            if (isset($notification->data['thread'])) {
                $threadId = $notification->data['thread']['id'];

                // Query notifikasi lain milik current user yang memiliki thread_id yang sama lalu hapus
                $relatedNotifications = DatabaseNotification::whereJsonContains('data->thread->id', $threadId)
                    ->where('notifiable_id', auth()->user()->id) // Punya user sekarang
                    ->where('id', '!=', $id) // Opsional: Menghindari notifikasi yang sedang di-handle
                    ->get();

                $relatedNotifications->markAsRead();

                if (auth()->user()->role->title == 'admin') {
                    return redirect(route('admin.thread.show', ['thread' => $threadId]));
                }

                if (auth()->user()->role->title == 'awardee') {
                    return redirect(route('thread.show', ['thread' => $threadId]));
                }
            }

            // Complaint notification
            if (isset($notification->data['complaint'])) {
                $complaintId = $notification->data['complaint']['id'];

                if (auth()->user()->role->title == 'admin') {
                    return redirect(route('admin.complaint.show', ['complaint' => $complaintId]));
                }

                if (auth()->user()->role->title == 'awardee') {
                    return redirect(route('complaint.show', ['complaint' => $complaintId]));
                }
            }
        }

        return redirect()->back()->with('error', 'Notification not found.');
    }
}
