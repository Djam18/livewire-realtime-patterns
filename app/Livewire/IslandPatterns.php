<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\{Computed, On, Lazy};

// Livewire 4 — Islands Architecture patterns.
//
// "Islands" in LW4 means components that are:
//   1. Excluded from the initial HTML response (server renders a placeholder)
//   2. Lazily hydrated when they enter the viewport (IntersectionObserver)
//   3. Isolated — parent re-renders don't cascade into island children
//
// This is "React Server Components for PHP" but implemented cleanly
// at the HTTP/wire protocol level rather than in a framework renderer.
//
// WHY ISLANDS?
//
// Traditional Livewire (LW2/LW3): every component on the page participates
// in every wire update cycle. A parent re-render cascades to children.
// With 10+ components this gets slow.
//
// Islands (LW4): each island has its own independent wire connection.
// The dashboard skeleton renders instantly (static HTML). Each widget
// loads independently — StockTicker doesn't block ActivityFeed.
//
// Perfect for dashboards where:
//   - Above-the-fold content is static (layout, nav)
//   - Below-the-fold content is dynamic (charts, feeds, notifications)
//
// wire:stream (new in LW4):
//   Server can stream partial updates to the component as they become available.
//   Like Server-Sent Events but integrated into the Livewire wire protocol.
//   Use case: AI chat responses, long-running reports, real-time analytics.

class IslandPatterns extends Component
{
    // This component itself is NOT lazy — it's the island host.
    // Each child island uses #[Lazy] independently.

    public string $activeDemo = 'activity';

    public function render()
    {
        return view('livewire.island-patterns');
    }
}
