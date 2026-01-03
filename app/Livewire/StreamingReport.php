<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\{Lazy, Locked};

// LW4 Island: StreamingReport — demonstrates wire:stream.
//
// wire:stream lets the server push partial updates to a component
// before the full Livewire response is sent. Perfect for:
//   - AI-generated content (token-by-token streaming like ChatGPT)
//   - Long-running report generation (stream rows as they're computed)
//   - Progress indicators that update in real-time
//
// How it works:
//   1. Client triggers an action (generateReport)
//   2. Server starts streaming via $this->stream('key', 'partial value')
//   3. Browser receives streamed chunks and updates the DOM incrementally
//   4. Full Livewire response arrives when the action completes
//
// This replaces the old pattern of:
//   - Polling an endpoint every second
//   - Using SSE with a separate route
//   - WebSocket with a separate channel
//
// wire:stream is integrated into the Livewire protocol — no extra infra.

#[Lazy]
class StreamingReport extends Component
{
    public string $report  = '';
    public bool   $loading = false;

    #[Locked]
    public string $reportType = 'summary';

    public function generate(): void
    {
        $this->loading = true;
        $this->report  = '';

        // Simulate streaming AI/report output
        $chunks = [
            "## Monthly Summary Report\n\n",
            "**Total Revenue:** $142,800 (+12.3% MoM)\n",
            "**New Customers:** 847 (+8.1% MoM)\n",
            "**Churn Rate:** 2.1% (-0.3pp MoM)\n\n",
            "### Key Insights\n\n",
            "- Q4 pipeline is 34% stronger than Q3 at same stage.\n",
            "- Enterprise segment growing 2x faster than SMB.\n",
            "- Top acquisition channel: organic search (43%).\n",
        ];

        foreach ($chunks as $chunk) {
            // wire:stream pushes this chunk to the browser immediately.
            // The 'report' key matches wire:stream="report" in the Blade template.
            $this->stream(
                to:      'report',
                content: $chunk,
                replace: false,  // append mode
            );
            usleep(120_000);  // 120ms — simulates generation time
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.streaming-report');
    }
}
