<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\UserCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserCreatedNotification
{
    public function __construct()
    {
        //
    }

    public function handle(UserCreated $event)
    {
        Mail::to($event->user->email)->send(new UserCreatedMail($event->user));
    }
}
