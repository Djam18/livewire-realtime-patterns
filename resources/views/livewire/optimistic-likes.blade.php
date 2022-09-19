{{--
    Optimistic UI: Alpine manages *local* state for instant feedback.
    Livewire manages *server* state for persistence.
    Alpine's x-data shadows Livewire properties — updates instantly, syncs later.

    In React: useState for optimistic + useEffect to revert on error.
    In Livewire: Alpine for optimistic + @like-synced.window to confirm/revert.
--}}
<div
    x-data="{
        liked:  $wire.entangle('isLiked'),
        count:  $wire.entangle('likeCount'),
        pending: false,

        toggle() {
            // Optimistic: flip immediately, no waiting
            this.liked  = !this.liked;
            this.count += this.liked ? 1 : -1;
            this.pending = true;

            // Livewire will confirm (or revert) via 'like-synced' event
            $wire.toggleLike();
        }
    }"
    @like-synced.window="
        count   = $event.detail.count;
        liked   = $event.detail.liked;
        pending = false;
    "
    class="inline-flex items-center gap-2"
>
    <button
        @click.prevent="toggle()"
        :disabled="pending"
        class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium transition-all
               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500"
        :class="{
            'bg-pink-100 text-pink-700 hover:bg-pink-200': liked,
            'bg-gray-100 text-gray-600 hover:bg-gray-200': !liked,
            'opacity-75 cursor-wait': pending,
        }"
    >
        {{-- Heart icon — filled when liked, outline when not --}}
        <svg
            class="w-4 h-4 transition-transform"
            :class="{ 'scale-125': liked }"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <template x-if="liked">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                         2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09
                         C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5
                         c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </template>
            <template x-if="!liked">
                <path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3
                         3 5.42 2 8.5 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35
                         l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55
                         l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5
                         c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5
                         c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
            </template>
        </svg>

        <span x-text="count"></span>

        {{-- Pending spinner --}}
        <svg x-show="pending" class="w-3 h-3 animate-spin ml-1" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
    </button>
</div>
