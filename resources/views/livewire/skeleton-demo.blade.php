{{--
    Skeleton screens in Livewire — three patterns:

    1. wire:loading / wire:loading.remove — toggle visibility on any Livewire action
    2. wire:target="loadPosts" — only triggers for this specific action
    3. Animate with Tailwind's animate-pulse for the shimmer effect

    React equivalent: useState(loading) + conditional <Skeleton /> render.
    Livewire is cleaner: no state variable needed, just directives.
--}}
<div class="max-w-lg space-y-4">
    <button
        wire:click="loadPosts"
        wire:loading.attr="disabled"
        wire:target="loadPosts"
        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700
               disabled:opacity-50 disabled:cursor-wait"
    >
        <span wire:loading.remove wire:target="loadPosts">Load Posts</span>
        <span wire:loading wire:target="loadPosts" class="flex items-center gap-2">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Loading…
        </span>
    </button>

    {{-- Skeleton cards — shown while loading, hidden after --}}
    <div wire:loading wire:target="loadPosts" class="space-y-3 animate-pulse">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                    </div>
                    <div class="h-3 bg-gray-200 rounded w-12"></div>
                </div>
            </div>
        @endfor
    </div>

    {{-- Real data — shown after load --}}
    @if($loaded)
        <div class="space-y-3" wire:loading.remove wire:target="loadPosts">
            @foreach($posts as $post)
                <div
                    wire:key="post-{{ $post['id'] }}"
                    class="bg-white rounded-xl p-4 shadow-sm border border-gray-100
                           hover:border-indigo-200 transition-colors"
                >
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full
                                    flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($post['author'], 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $post['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $post['author'] }}</p>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">
                            {{ number_format($post['views']) }} views
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(!$loaded)
        <div wire:loading.remove wire:target="loadPosts"
             class="text-center py-8 text-gray-400 text-sm">
            Click "Load Posts" to see skeleton screens in action.
        </div>
    @endif
</div>
