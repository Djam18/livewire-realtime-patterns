<?php

namespace App\Http\Livewire;

use Livewire\Component;

// Skeleton screens: show placeholder shapes while data is loading.
// React pattern: conditional rendering with <Skeleton /> components.
// Livewire pattern: wire:loading.class / wire:target for targeted loading states.
//
// Three strategies:
// 1. wire:loading.class — add/remove CSS class on a single element
// 2. wire:loading / wire:loading.remove — show/hide separate elements
// 3. wire:target="specificAction" — only show skeleton for one action, not all

class SkeletonDemo extends Component
{
    public array $posts = [];
    public bool $loaded = false;

    // Simulate async data load with delay
    public function loadPosts(): void
    {
        sleep(1); // Simulate 1s network latency

        $this->posts = [
            ['id' => 1, 'title' => 'Getting started with Livewire 2.x',   'author' => 'Caleb Porzio', 'views' => 3_240],
            ['id' => 2, 'title' => 'Alpine.js deep dive: magic properties', 'author' => 'Hugo Sainte-Marie', 'views' => 1_890],
            ['id' => 3, 'title' => 'TALL stack in production: lessons learned', 'author' => 'Tony Messias', 'views' => 4_102],
            ['id' => 4, 'title' => 'Optimistic UI patterns in Livewire',    'author' => 'Dan Harrin',   'views' => 2_567],
        ];

        $this->loaded = true;
    }

    public function render()
    {
        return view('livewire.skeleton-demo');
    }
}
