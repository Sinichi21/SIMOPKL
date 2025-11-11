<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\Component;

class Layout extends Component
{
    public $notifications;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->notifications = DatabaseNotification::where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->whereJsonContains('data->role', 'admin')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.layout');
    }
}
