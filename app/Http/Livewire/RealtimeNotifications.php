<?php

namespace App\Http\Livewire;

use Livewire\Component;

// Laravel Echo + Pusher: real WebSocket push.
// Server broadcasts an event → Pusher relays → Echo client receives →
// Livewire component re-renders via $listeners.
//
// In React/Firebase: onSnapshot() fires on DB change.
// In Livewire/Echo: getListeners() returns channel subscriptions.
//
// Flow:
// 1. Something happens server-side (new order, payment received)
// 2. PHP: broadcast(new OrderReceived($order));
// 3. Pusher relays to all subscribed clients
// 4. Livewire Echo listener fires -> PHP method called -> re-render
//
// "C'est comme Firebase onSnapshot mais cote serveur!
//  Et ca marche avec n'importe quelle DB, pas juste Firestore."

class RealtimeNotifications extends Component
{
    public array $notifications = [];
    public int $unreadCount = 0;

    // Echo channel listeners — declared as getListeners() for dynamic channels
    protected function getListeners(): array
    {
        return [
            // Listen to private user channel via Echo
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
