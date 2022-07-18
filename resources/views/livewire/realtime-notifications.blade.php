<div class="max-w-md bg-white rounded-xl shadow">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-gray-800">Notifications</h2>
            @if($unreadCount > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 font-bold">
                    {{ $unreadCount }}
                </span>
            @endif
        </div>
        <div class="flex gap-2">
            {{-- Demo button — in prod this comes from Echo/Pusher --}}
            <button wire:click="simulateNotification"
                class="text-xs px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200">
                + Simulate
            </button>
            @if($unreadCount > 0)
                <button wire:click="markAllRead"
                    class="text-xs px-3 py-1.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50">
                    Mark all read
                </button>
            @endif
        </div>
    </div>

    @forelse($notifications as $notif)
        <div
            wire:key="notif-{{ $notif['id'] }}"
            wire:click="markRead('{{ $notif['id'] }}')"
            class="px-6 py-4 border-b border-gray-50 cursor-pointer transition-colors
                   {{ $notif['read'] ? 'bg-white' : 'bg-blue-50 hover:bg-blue-100' }}"
        >
            <div class="flex items-start gap-3">
                <span class="text-xl mt-0.5">
                    @switch($notif['type'])
                        @case('success') ✅ @break
                        @case('warning') ⚠️ @break
                        @case('error') ❌ @break
                        @default ℹ️ @break
                    @endswitch
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 {{ $notif['read'] ? 'font-normal' : '' }}">
                        {{ $notif['title'] }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $notif['message'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notif['time'] }}</p>
                </div>
                @if(!$notif['read'])
                    <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></span>
                @endif
            </div>
        </div>
    @empty
        <div class="px-6 py-12 text-center text-gray-400 text-sm">
            <p class="text-2xl mb-2">🔔</p>
            <p>No notifications yet.</p>
            <p class="text-xs mt-1">Echo + Pusher will push them in real-time.</p>
        </div>
    @endforelse
</div>
