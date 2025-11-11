<?php

namespace App\View\Components\Awardee;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\Component;

class TopNav extends Component
{
    public $notifications;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->notifications = DatabaseNotification::where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->whereJsonContains('data->role', 'awardee')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.awardee.top-nav');
    }
}
