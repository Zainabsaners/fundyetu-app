<x-dashboard-layout title="My Tickets">
    <x-slot name="header">My Tickets</x-slot>

    {{-- Add Ticket Button --}}
    <div class="mb-4 flex justify-end">
        <button @click="$dispatch('open-ticket-modal')"
                class="px-4 py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">
            + New Ticket
        </button>
    </div>

    {{-- Tickets Table --}}
    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-white uppercase" style="background-color: #1B2A4A;">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">#{{ $ticket->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $ticket->subject }}</div>
                                @if($ticket->attachment_path)
                                    <span class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        Attachment
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5
                                    @if($ticket->status === 'open') bg-yellow-100 text-yellow-700
                                    @elseif($ticket->reply) bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    @if($ticket->status === 'closed' && $ticket->reply)
                                        Replied
                                    @else
                                        {{ ucfirst($ticket->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                   class="text-xs font-medium text-[#CE5F26] hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">No tickets yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $tickets->links() }}
    </div>

    {{-- New Ticket Modal --}}
    <div x-data="{ open: false }"
         @open-ticket-modal.window="open = true"
         x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

        {{-- Modal --}}
        <div class="relative bg-white w-full max-w-lg shadow-xl overflow-hidden"
             @click.outside="open = false">
            {{-- Header --}}
            <div class="px-5 py-4" style="background-color: #1B2A4A;">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-white text-sm">New Ticket</h3>
                    <button @click="open = false" class="text-white/60 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <input type="text" name="subject" required
                           class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="4" required
                              class="w-full border border-gray-300 px-3 py-2 text-sm focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Screenshot (optional)</label>
                    <input type="file" name="attachment" accept="image/*,.pdf"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:text-sm file:font-medium file:border-0 file:text-white file:bg-[#CE5F26] hover:file:bg-[#b85422] cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Max 5MB. JPG, PNG, GIF, PDF</p>
                </div>
                <button type="submit"
                        class="w-full py-2 text-sm font-semibold text-white transition" style="background-color: #CE5F26;">
                    Submit Ticket
                </button>
            </form>
        </div>
    </div>
</x-dashboard-layout>
