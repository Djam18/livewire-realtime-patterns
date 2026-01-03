<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\{Computed, Lazy, On};

// LW4 Island: ActivityFeed
//
// #[Lazy] — the component is excluded from the initial server response.
// The browser receives a <livewire:activity-feed /> placeholder, then
// fires an XHR to hydrate this component independently of the page.
//
// #[Lazy(isolate: true)] — additionally, this component's updates are
// isolated: polling or Echo events here don't trigger parent re-renders.
//
// wire:poll with lazy: polling starts AFTER the island is hydrated,
// not from page load. Much better for performance.

#[Lazy(isolate: true)]
class ActivityFeed extends Component
{
    public int $limit = 20;

    #[Computed]
    public function activities(): array
    {
        // In prod: fetch from DB, latest $limit records
        return array_map(fn ($i) => [
            'id'      => $i,
            'user'    => "User {$i}",
            'action'  => ['created', 'updated', 'deleted'][($i % 3)],
            'subject' => "Record #{$i}",
            'at'      => now()->subMinutes($i * 3)->diffForHumans(),
        ], range(1, min($this->limit, 20)));
    }

    #[On('echo:activities,ActivityCreated')]
    public function refresh(): void
    {
        unset($this->activities);
    }

    public function render()
    {
        return view('livewire.activity-feed');
    }
}
