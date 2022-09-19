<?php

namespace App\Http\Livewire;

use Livewire\Component;

// Optimistic UI: update the UI immediately, sync with server in background.
// React pattern: setState optimistically, then re-fetch or revert on error.
// Livewire pattern: Alpine handles the immediate UI state, Livewire does the real work.
// Key insight: Livewire actions are async by nature (AJAX). Alpine bridges the gap.
//
// The trick: wire:click.prevent fires Livewire action (slow, server round-trip).
// Alpine's @click.prevent fires synchronously (fast, DOM only).
// We layer them: Alpine updates count instantly, Livewire persists.

class OptimisticLikes extends Component
{
    public int $postId;
    public int $likeCount = 0;
    public bool $isLiked = false;

    public function mount(int $postId): void
    {
        $this->postId = $postId;
        // In a real app: load from DB
        $this->likeCount = fake()->numberBetween(10, 200);
        $this->isLiked = false;
    }

    public function toggleLike(): void
    {
        // Simulate slight delay (network + DB write)
        // In prod: Like::updateOrCreate([...])
        usleep(200000); // 200ms simulated latency

        $this->isLiked = !$this->isLiked;
        $this->likeCount += $this->isLiked ? 1 : -1;

        // Dispatch browser event so Alpine can sync its local state
        $this->dispatchBrowserEvent('like-synced', [
            'postId'   => $this->postId,
            'liked'    => $this->isLiked,
            'count'    => $this->likeCount,
        ]);
    }

    public function render()
    {
        return view('livewire.optimistic-likes');
    }
}
