<?php

namespace App\Http\Livewire;

use Livewire\Component;

// dispatchBrowserEvent() sends a CustomEvent to the browser.
// Alpine can listen: @event-name.window="doSomething"
// JavaScript can listen: window.addEventListener('event-name', ...)
// This bridges Livewire (PHP/server) with Alpine (client-side JS).

class BrowserEventDemo extends Component
{
    public string $status = 'idle';
    public int $progress = 0;

    public function startProcess(): void
    {
        $this->status = 'running';
        $this->progress = 0;

        // Dispatch to Alpine/JS for visual feedback
        $this->dispatchBrowserEvent('process-started', [
            'message' => 'Process started on server',
            'timestamp' => now()->toISOString(),
        ]);
    }

    public function simulateProgress(): void
    {
        $this->progress = min(100, $this->progress + 20);

        $this->dispatchBrowserEvent('progress-update', [
            'progress' => $this->progress,
            'complete' => $this->progress >= 100,
        ]);

        if ($this->progress >= 100) {
            $this->status = 'done';
        }
    }

    public function render()
    {
        return view('livewire.browser-event-demo');
    }
}
