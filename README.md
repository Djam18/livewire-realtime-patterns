# Livewire Real-Time Patterns

Exploring every real-time pattern in Livewire 2.x — from simple polling to Laravel Echo/Pusher.

## Patterns Covered

| Pattern | Use Case | Mechanism |
|---------|----------|-----------|
| `wire:poll` | Activity feeds, live metrics | HTTP polling on interval |
| `$dispatch` | Cross-component communication | Browser events |
| `$emit` | Parent-child component events | Livewire event bus |
| Laravel Echo + Pusher | Server-push notifications | WebSocket |
| Optimistic UI | Instant feedback before server confirms | Alpine state + Livewire |
| Skeleton screens | Loading state UX | `wire:loading` |
| Multi-step wizard | Form flow with progress | Livewire state machine |

## The Aha Moment

Coming from Firebase onSnapshot (React), I expected real-time to be hard in PHP.

`wire:poll.3s` does HTTP polling. Crude but works for most cases.
Laravel Echo + Reverb does actual WebSocket push. For when it matters.

The insight: 90% of "real-time" features don't need WebSockets.
`wire:poll` with a sensible interval is fine. And the fallback to WebSocket when needed is seamless.
