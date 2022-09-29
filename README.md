# Livewire Real-Time Patterns

Exploring every real-time pattern in Livewire 2.x — from simple polling to Laravel Echo/Pusher.
Built as a reference catalog while migrating from React/Firebase to TALL stack (2022).

## Pattern Catalog

| Pattern | Component | Use Case | Mechanism |
|---------|-----------|----------|-----------|
| `wire:poll` | `ActivityFeed` | Live feeds, dashboards | HTTP polling on interval |
| Browser events (`$dispatch`) | `NotificationBell` | Cross-component comms | `dispatchBrowserEvent()` + Alpine `@event.window` |
| Livewire events (`$emit`) | `ParentComponent` / `ChildItemList` | Parent-child communication | `$emit()` up + `$listeners` in parent |
| Laravel Echo + Pusher | `RealtimeNotifications` | Server-push notifications | WebSocket channel subscription |
| Optimistic UI | `OptimisticLikes` | Instant feedback | Alpine local state + `$wire.entangle()` |
| Skeleton screens | `SkeletonDemo` | Loading state UX | `wire:loading` / `wire:loading.remove` / `wire:target` |
| Multi-step wizard | `MultiStepWizard` | Form flow with progress | Per-step `$rules` validation |

## When to Use What

```
wire:poll.Xs     ← refresh interval doesn't matter much? Start here.
$emit / $dispatch ← components need to talk to each other? Use events.
Echo + Pusher    ← server has to push to clients? WebSocket time.
Alpine entangle  ← need instant UI + server persistence? Optimistic pattern.
wire:loading     ← any async action needs a loading state? Always.
```

## React vs Livewire Comparison

| React Pattern | Livewire Equivalent |
|---------------|---------------------|
| `useInterval` + `fetch` | `wire:poll.3000ms` directive |
| Custom events + `dispatchEvent` | `$dispatch('event-name', payload)` |
| Prop callbacks (`onSave`) | `$emit('event', data)` + `$listeners` |
| Firebase `onSnapshot` | Echo `getListeners()` channel |
| `useState` optimistic + revert | `$wire.entangle()` + `@event-synced.window` |
| `<Skeleton />` conditional render | `wire:loading` / `wire:loading.remove` |
| Multi-step with Context/Zustand | Single Livewire component, `$currentStep` |

## Key Insights (Sept 2022)

**1. wire:poll is underrated**
Coming from Firebase `onSnapshot`, I expected polling to feel primitive. It doesn't.
For dashboards refreshing every 5–30s, it's dead simple and works everywhere.

**2. Optimistic UI with Alpine + Livewire is clean**
`$wire.entangle('property')` gives Alpine a live reference to Livewire state.
Alpine updates instantly. Livewire persists. `dispatchBrowserEvent` confirms or reverts.
In React this needs useState + useOptimistic + error boundary. Here it's ~10 lines of Alpine.

**3. wire:loading is everything**
`wire:loading`, `wire:loading.remove`, `wire:loading.class`, `wire:target` — this API is brilliant.
No `isLoading` state variable needed. No conditional rendering boilerplate.
Just declarative directives on the elements that need to react to loading.

**4. Multi-step forms: single component wins**
React multi-step needs Context or Zustand to share form state across step components.
Livewire: one PHP class, all properties flat, per-step `$rules` array for validation.
The simplicity is almost suspicious.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# Add Pusher credentials to .env for Echo demos
# PUSHER_APP_ID=xxx
# PUSHER_APP_KEY=xxx
# PUSHER_APP_SECRET=xxx

php artisan serve
```

## Stack

- Laravel 9 + Livewire 2.10
- Alpine.js 3.x
- Tailwind CSS 3.x JIT
- Laravel Echo + Pusher PHP Server 7.x
