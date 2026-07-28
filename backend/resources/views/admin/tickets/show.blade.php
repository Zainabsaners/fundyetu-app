<x-admin-layout title="Ticket #{{ $ticket->id }}">
    <x-slot name="header">Ticket #{{ $ticket->id }}</x-slot>

    <div class="max-w-2xl space-y-6">
        {{-- User Info --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">{{ $ticket->subject }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">
                    by {{ $ticket->user->name }} &middot; {{ $ticket->created_at->format('M d, Y g:i A') }}
                    &middot; <span class="font-medium
                        @if($ticket->status === 'open') text-yellow-600
                        @else text-green-600 @endif">{{ ucfirst($ticket->status) }}</span>
                </p>
            </div>
            <div class="px-5 py-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</div>
            @if($ticket->attachment_path)
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                    <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-sm text-[#CE5F26] hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        View Attachment
                    </a>
                </div>
            @endif
        </div>

        {{-- Admin Reply --}}
        @if($ticket->reply)
            <div class="bg-gray-50 border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100" style="background-color: #1B2A4A;">
                    <p class="text-xs font-semibold text-white">Admin Reply &middot; {{ $ticket->replied_at->format('M d, Y g:i A') }}</p>
                </div>
                <div class="px-5 py-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->reply }}</div>
            </div>
        @endif

        {{-- Reply Form --}}
        @if($ticket->status === 'open')
            <div class="bg-white border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100" style="background-color: #1B2A4A;">
                    <p class="text-xs font-semibold text-white">Reply</p>
                </div>
                <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}" class="p-5 space-y-3">
                    @csrf
                    <textarea name="reply" rows="5" placeholder="Type your reply..." required
                              class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none resize-none"></textarea>
                    <button type="submit"
                            class="px-5 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">
                        Send Reply &amp; Close Ticket
                    </button>
                </form>
            </div>
        @else
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500">Ticket is closed.</span>
                <form method="POST" action="{{ route('admin.tickets.reopen', $ticket) }}">
                    @csrf
                    <button type="submit" class="text-sm text-[#CE5F26] hover:underline">Reopen</button>
                </form>
            </div>
        @endif
    </div>
</x-admin-layout>
