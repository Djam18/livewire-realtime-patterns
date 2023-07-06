<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

// Laravel Echo + Pusher: real WebSocket push.
// Server broadcasts an event → Pusher relays → Echo client receives →
// Livewire component re-renders via $listeners.
//
// LIVEWIRE 3 MIGRATION — Jul 2023
// getListeners() with Echo channels now uses #[On] where possible.
// Dynamic Echo channels (user-specific) still use getListeners() —
// #[On] doesn't support dynamic values, so mixed approach is correct.
// LW3 docs confirm: use getListeners() for Echo channels.

class RealtimeNotifications extends Component
{
    public array $notifications = [];
    public int $unreadCount = 0;

    // Echo channels still need getListeners() — #[On] can't be dynamic
    protected function getListeners(): array
    {
        return [
            'echo-private:users.' . (auth()->id() ?? 1) . ',NewNotification' => 'onNewNotification',
            'echo:public-updates,SystemAlert' => 'onSystemAlert',
        ];
    }

    public function onNewNotification(array $data): void
    {
        array_unshift($this->notifications, [
            'id'      => $data['id'] ?? uniqid(),
            'title'   => $data['title'],
            'message' => $data['message'],
            'type'    => $data['type'] ?? 'info',
            'read'    => false,
            'time'    => now()->diffForHumans(),
        ]);

        $this->unreadCount++;

        // Keep last 10 notifications
        if (count($this->notifications) > 10) {
            array_pop($this->notifications);
        }
    }

    public function onSystemAlert(array $data): void
    {
        $this->onNewNotification(array_merge($data, ['type' => 'warning']));
    }

    public function markRead(string $id): void
    {
        foreach ($this->notifications as &$notif) {
            if ($notif['id'] === $id) {
                $notif['read'] = true;
                $this->unreadCount = max(0, $this->unreadCount - 1);
                break;
            }
        }
    }

    public function markAllRead(): void
    {
        foreach ($this->notifications as &$notif) {
            $notif['read'] = true;
        }
        $this->unreadCount = 0;
    }

    // Simulate receiving a notification (for demo)
    public function simulateNotification(): void
    {
        $this->onNewNotification([
            'id'      => uniqid(),
            'title'   => 'New payment received',
            'message' => 'Invoice #' . rand(100, 999) . ' was paid — $' . rand(100, 2000),
            'type'    => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.realtime-notifications');
    }
}
