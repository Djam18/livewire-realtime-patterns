<?php

namespace App\Http\Livewire;

use Livewire\Component;

// $dispatch: Livewire 2 name is $emit, Livewire 3 renames to $dispatch.
// Dispatch browser events → Alpine or other JS can listen.
// dispatch() ALSO sends to Livewire components via $listeners.
//
// Pattern: NotificationBell dispatches 'notification-cleared',
// NotificationList listens with $listeners = ['notification-cleared'].
// Pure Livewire event bus — no JavaScript needed.

class NotificationBell extends Component
{
    public int $count = 3;

    protected $listeners = [
        'notification-added'   => 'increment',
        'notifications-cleared' => 'reset',
    ];

    public function increment(): void
    {
        $this->count++;
    }

    public function reset(): void
    {
        $this->count = 0;
    }

    public function markAllRead(): void
    {
        $this->count = 0;
        // Dispatch browser event for Alpine to show animation
        $this->dispatchBrowserEvent('notifications-read');
        // Tell sibling Livewire components
        $this->emit('notifications-cleared');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
