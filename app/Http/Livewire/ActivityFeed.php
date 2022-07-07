<?php

namespace App\Http\Livewire;

use Livewire\Component;

// wire:poll: the simplest real-time pattern.
// Livewire re-renders the component on a fixed interval.
// wire:poll.3s → HTTP POST every 3 seconds → PHP re-queries → re-renders.
// No WebSockets. No JavaScript. Just periodic server re-fetches.
//
// React equivalent: useEffect + setInterval + fetch. All manual.
// Livewire: wire:poll="3000" on the component div. Done.
//
// When to use: activity feeds, order status, notification counts.
// When NOT to use: chat, collaborative editing — use WebSockets instead.

class ActivityFeed extends Component
{
    public int $pollInterval = 3000; // ms
    public bool $paused = false;
    public int $newCount = 0;
    private static int $counter = 0;

    // Simulated feed — in a real app: Activity::latest()->limit(20)->get()
    public function getFeedProperty(): array
    {
        return [
            ['id' => 1, 'user' => 'Taylor Otwell', 'action' => 'pushed to main', 'repo' => 'laravel/framework', 'time' => '2 min ago', 'type' => 'push'],
            ['id' => 2, 'user' => 'Caleb Porzio', 'action' => 'opened PR #4821', 'repo' => 'livewire/livewire', 'time' => '5 min ago', 'type' => 'pr'],
            ['id' => 3, 'user' => 'Adam Wathan', 'action' => 'released v3.2.0', 'repo' => 'tailwindlabs/tailwindcss', 'time' => '12 min ago', 'type' => 'release'],
            ['id' => 4, 'user' => 'Nuno Maduro', 'action' => 'merged PR #892', 'repo' => 'pestphp/pest', 'time' => '18 min ago', 'type' => 'merge'],
            ['id' => 5, 'user' => 'Freek Van der Herten', 'action' => 'tagged v1.4.2', 'repo' => 'spatie/laravel-query-builder', 'time' => '25 min ago', 'type' => 'tag'],
        ];
    }

    public function togglePause(): void
    {
        $this->paused = !$this->paused;
    }

    public function render()
    {
        return view('livewire.activity-feed');
    }
}
