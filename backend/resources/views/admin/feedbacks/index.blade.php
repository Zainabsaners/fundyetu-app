<x-admin-layout title="Feedback">
    <x-slot name="header">User Feedback</x-slot>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="adminFeedbacksTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">User</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Rating</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Comment</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Status</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Date</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $fb)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200">
                                <div class="font-medium text-gray-800">{{ $fb->name }}</div>
                                @if($fb->user)
                                    <div class="text-xs text-gray-400">{{ $fb->user->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <div class="flex items-center justify-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $fb->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600 max-w-xs truncate">{{ $fb->content }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                <span class="text-xs font-medium px-2 py-0.5
                                    @if($fb->is_active) bg-green-50 text-green-700
                                    @else bg-yellow-50 text-yellow-700 @endif">
                                    {{ $fb->is_active ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ $fb->created_at->format('d M Y g:i A') }}</td>
                            <td class="px-4 py-3 text-left border border-gray-200 whitespace-nowrap">
                                <div class="flex items-center justify-start gap-1.5">
                                    @if($fb->is_active)
                                        <form method="POST" action="{{ route('admin.feedbacks.reject', $fb) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #DC2626;">Reject</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.feedbacks.approve', $fb) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #7AA36A;">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminFeedbacksTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
                language: {
                    search: '',
                    searchPlaceholder: 'Search feedback...',
                    emptyTable: 'No feedback found'
                },
                dom: '<"mb-3 flex items-center justify-between"<"flex items-center gap-2"l><f>>tip',
                initComplete: function () {
                    $('div.dataTables_filter input').addClass('pl-9 pr-3 py-1.5 text-sm border border-gray-200 focus:border-[#CE5F26] focus:ring-1 focus:ring-[#CE5F26] outline-none w-56');
                    $('div.dataTables_filter').before(
                        '<svg class="w-4 h-4 absolute ml-3 mt-2.5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>'
                    );
                    $('div.dataTables_filter').css('position', 'relative');
                }
            });
        });
    </script>
</x-admin-layout>
