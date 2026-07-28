<x-dashboard-layout title="Ticket #{{ $ticket->id }}">
    <x-slot name="header">Ticket #{{ $ticket->id }}</x-slot>

    <div class="max-w-2xl space-y-6">
        <a href="{{ route('tickets.index') }}" class="text-sm text-[#CE5F26] hover:underline inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tickets
        </a>

        {{-- User Message --}}
        <div class="bg-white border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100" style="background-color: #1B2A4A;">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-white">{{ $ticket->subject }}</p>
                    <span class="text-xs font-medium px-2 py-0.5
                        @if($ticket->status === 'open') bg-yellow-100 text-yellow-700
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
            </div>
            <div class="px-5 py-4">
                <p class="text-xs text-gray-400 mb-2">You &middot; {{ $ticket->created_at->format('M d, Y g:i A') }}</p>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->message }}</p>
                @if($ticket->attachment_path)
                    <div class="mt-3 pt-3 border-t border-gray-100">
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
        </div>

        {{-- Admin Reply --}}
        @if($ticket->reply)
            <div class="border border-gray-200 overflow-hidden ml-6" style="background-color: #f9fafb;">
                <div class="px-5 py-3 border-b border-gray-100" style="background-color: #CE5F26;">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-white">Admin Response</p>
                        <p class="text-xs text-white/70">{{ $ticket->replied_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->reply }}</p>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-sm text-gray-400">
                Waiting for admin response...
            </div>
        @endif
    </div>
</x-dashboard-layout>
