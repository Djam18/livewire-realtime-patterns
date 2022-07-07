{{--
  wire:poll refreshes this component on an interval.
  wire:poll="3000" → re-render every 3 seconds.
  wire:poll.visible → only poll when tab is visible (saves requests!)
  wire:poll.keep-alive → keep polling even when tab is hidden.
--}}
<div
    wire:poll.3000ms
    class="bg-white rounded-xl shadow max-w-lg"
>
    {{-- Header with live indicator --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-gray-800">Activity Feed</h2>
            {{-- Pulsing dot = wire:poll is active --}}
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400">Updates every 3s</span>
            <button wire:click="togglePause"
                class="text-xs px-2 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-50">
                {{ $paused ? 'Resume' : 'Pause' }}
            </button>
        </div>
    </div>

    {{-- Activity list --}}
    <ul class="divide-y divide-gray-50">
        @foreach($this->feed as $event)
            <li wire:key="event-{{ $event['id'] }}" class="px-6 py-3 flex items-start gap-3 hover:bg-gray-50 transition-colors">
                {{-- Type indicator --}}
                <span class="mt-0.5 text-lg">
                    @switch($event['type'])
                        @case('push') 📤 @break
                        @case('pr') 🔀 @break
                        @case('release') 🚀 @break
                        @case('merge') ✅ @break
                        @case('tag') 🏷️ @break
                        @default ⚡ @break
                    @endswitch
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800">
                        <strong>{{ $event['user'] }}</strong>
                        <span class="text-gray-600">{{ $event['action'] }}</span>
                    </p>
                    <p class="text-xs text-indigo-600 truncate font-mono">{{ $event['repo'] }}</p>
                </div>
                <span class="text-xs text-gray-400 flex-shrink-0">{{ $event['time'] }}</span>
            </li>
        @endforeach
    </ul>

    {{-- Footer --}}
    <div class="px-6 py-3 border-t border-gray-100 text-xs text-gray-400 text-center">
        wire:poll.3000ms — re-renders every 3 seconds via HTTP POST
    </div>
</div>
