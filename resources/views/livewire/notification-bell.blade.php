{{-- Alpine listens for the browser event dispatched by Livewire --}}
<div
    x-data="{ shake: false }"
    @notifications-read.window="shake = true; setTimeout(() => shake = false, 600)"
    class="relative inline-block"
>
    <button
        wire:click="markAllRead"
        :class="shake ? 'animate-bounce' : ''"
        class="relative p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($count > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full
                         flex items-center justify-center font-bold animate-pulse">
                {{ $count > 9 ? '9+' : $count }}
            </span>
        @endif
    </button>
</div>
