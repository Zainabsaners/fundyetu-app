<x-admin-layout title="Users">
    <x-slot name="header">Users</x-slot>

    <div class="bg-white border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2">
            <a href="{{ route('admin.users.index') }}"
                class="px-3 py-1.5 text-sm font-medium transition @if(!request('kyc_status')) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">
                All ({{ App\Models\User::count() }})
            </a>
            @foreach(['unverified' => 'Not Submitted', 'pending' => 'KYC Pending Review', 'verified' => 'Approved', 'rejected' => 'Rejected'] as $val => $label)
                <a href="{{ route('admin.users.index', array_merge(request()->only('search'), ['kyc_status' => $val])) }}"
                   class="px-3 py-1.5 text-sm font-medium transition @if(request('kyc_status') === $val) bg-[#1B2A4A] text-white @else bg-gray-100 text-gray-600 hover:bg-gray-200 @endif">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table id="adminUsersTable" class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="text-center px-3 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200 w-12">No.</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Name</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Role</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Activated</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">KYC</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">ID Number</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Phone</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Campaigns</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Active</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Total Raised</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Donors</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Registered</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider border border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-3 py-3 text-center text-gray-500 border border-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border border-gray-200 max-w-xs">
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold rounded
                                    @if($user->hasRole('super_admin')) bg-red-50 text-red-700
                                    @elseif($user->hasRole('admin')) bg-blue-50 text-blue-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $user->getRoleNames()->first() ?? 'user' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center border border-gray-200">
                                @if($user->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-green-50 text-green-700 rounded">Yes</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-gray-100 text-gray-500 rounded">No</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border border-gray-200">
                                <a href="{{ route('admin.users.details', ['user' => $user, 'tab' => 'kyc']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full transition
                                    @if($user->kyc_status === 'verified') bg-green-50 text-green-700 hover:bg-green-100
                                    @elseif($user->kyc_status === 'rejected') bg-red-50 text-red-700 hover:bg-red-100
                                    @elseif($user->kyc_status === 'pending') bg-yellow-50 text-yellow-700 hover:bg-yellow-100
                                    @else bg-gray-100 text-gray-500 hover:bg-gray-200 @endif">
                                    @if($user->kyc_status === 'verified')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Verified
                                    @elseif($user->kyc_status === 'rejected')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Rejected
                                    @elseif($user->kyc_status === 'pending')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        KYC Pending Review
                                    @else
                                        Not Submitted
                                    @endif
                                </a>
                            </td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600 font-mono text-xs">{{ $user->id_number ?? '—' }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-gray-600 font-mono text-xs whitespace-nowrap">{{ $user->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200 text-xs font-medium text-gray-800">{{ number_format($user->campaigns_count) }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200 text-xs font-medium {{ $user->active_campaigns_count > 0 ? 'text-green-600' : 'text-gray-400' }}">{{ number_format($user->active_campaigns_count) }}</td>
                            <td class="px-4 py-3 text-right border border-gray-200 text-xs font-semibold text-gray-900">KES {{ number_format($user->total_raised ?? 0, 0) }}</td>
                            <td class="px-4 py-3 text-center border border-gray-200 text-xs font-medium text-gray-800">{{ number_format($user->total_donors ?? 0) }}</td>
                            <td class="px-4 py-3 border border-gray-200 text-xs text-gray-500 whitespace-nowrap">{{ $user->created_at->format('d M Y g:i A') }}</td>
                            <td class="px-4 py-3 text-left border border-gray-200 whitespace-nowrap">
                                <div class="flex items-center justify-start gap-1.5">
                                    <a href="{{ route('admin.users.details', $user) }}"
                                       class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #1B2A4A;">View</a>
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #6B7280;">Edit</a>
                                    @if(!$user->is_approved)
                                        <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Activate this user? They will get dashboard access.')">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #7AA36A;">Activate</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete this user permanently?')">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-medium px-2 py-0.5 text-white rounded transition" style="background-color: #DC2626;">Delete</button>
                                    </form>
                                    <button onclick="showKYC({{ $user->id }})"
                                            class="text-[11px] font-medium px-2 py-0.5 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded transition">KYC</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- KYC Review Modal --}}
    <div id="kycModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50" x-data>
        <div class="bg-white w-full max-w-lg mx-4 overflow-hidden shadow-xl" @@click.stop>
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">KYC Application Review</h3>
                <button onclick="closeKYC()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="kycContent" class="p-6 space-y-4">
                <div class="flex items-center justify-center py-8">
                    <svg class="w-8 h-8 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
            </div>
            <div id="kycFooter" class="px-6 py-4 border-t border-gray-100 flex justify-end gap-2">
                <form id="approveForm" method="POST" class="hidden">
                    @csrf
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition shadow-sm">Approve</button>
                </form>
                <form id="rejectForm" method="POST" class="hidden">
                    @csrf
                    <button type="submit" class="px-5 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition shadow-sm">Reject</button>
                </form>
                <button onclick="closeKYC()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Close</button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#adminUsersTable').DataTable({
                paging: true,
                pageLength: 12,
                lengthChange: false,
                info: true,
                ordering: true,
                order: [],
                language: {
                    search: '',
                    searchPlaceholder: 'Search users...',
                    emptyTable: 'No users found'
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

        function showKYC(userId) {
            const modal = document.getElementById('kycModal');
            const content = document.getElementById('kycContent');
            const approveForm = document.getElementById('approveForm');
            const rejectForm = document.getElementById('rejectForm');

            approveForm.classList.add('hidden');
            rejectForm.classList.add('hidden');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            fetch(`/admin/users/${userId}/json`)
                .then(r => r.json())
                .then(user => {
                    const fileUrl = (path) => path ? `<a href="/storage/${path}" target="_blank" class="text-[#CE5F26] hover:underline text-xs font-medium">View File</a>` : '<span class="text-xs text-gray-400">Not uploaded</span>';

                    content.innerHTML = `
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Full Name</p>
                                <p class="text-gray-800 font-medium">${escapeHtml(user.name)}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Status</p>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full ${
                                    user.kyc_status === 'verified' ? 'bg-green-50 text-green-700' :
                                    user.kyc_status === 'rejected' ? 'bg-red-50 text-red-700' :
                                    user.kyc_status === 'pending' ? 'bg-yellow-50 text-yellow-700' :
                                    'bg-gray-100 text-gray-500'
                                }">${user.kyc_status === 'pending' ? 'KYC Pending Review' : capitalize(user.kyc_status)}</span>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Email</p>
                                <p class="text-gray-800">${escapeHtml(user.email)}</p>
                                ${user.email_verified_at ? '<p class="text-xs text-green-600 mt-0.5">✓ Verified</p>' : '<p class="text-xs text-yellow-600 mt-0.5">Not verified</p>'}
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Phone</p>
                                <p class="text-gray-800">${escapeHtml(user.phone || '—')}</p>
                                ${user.phone_verified_at ? '<p class="text-xs text-green-600 mt-0.5">✓ Verified</p>' : '<p class="text-xs text-yellow-600 mt-0.5">Not verified</p>'}
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">ID Number</p>
                                <p class="text-gray-800 font-mono">${escapeHtml(user.id_number || '—')}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Birth Year</p>
                                <p class="text-gray-800">${escapeHtml(user.birth_year || '—')}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Address</p>
                                <p class="text-gray-800">${escapeHtml(user.address || '—')}</p>
                            </div>
                            <div class="col-span-2 border-t border-gray-100 pt-3 mt-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Documents</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <p class="text-xs text-gray-500">ID Front</p>
                                        ${fileUrl(user.id_front_path)}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">ID Back</p>
                                        ${fileUrl(user.id_back_path)}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Address Proof</p>
                                        ${fileUrl(user.address_proof_path)}
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Profile Photo</p>
                                        ${fileUrl(user.profile_photo_path)}
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 border-t border-gray-100 pt-3 mt-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Payout Details</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Method</p>
                                        <p class="text-gray-800 font-medium">${escapeHtml((user.withdrawal_method || '—').toUpperCase())}</p>
                                    </div>
                                    ${user.withdrawal_method === 'mpesa' ? `
                                    <div>
                                        <p class="text-xs text-gray-500">MPESA Phone</p>
                                        <p class="text-gray-800">${escapeHtml(user.mpesa_phone || '—')}</p>
                                    </div>` : `
                                    <div>
                                        <p class="text-xs text-gray-500">Bank Name</p>
                                        <p class="text-gray-800">${escapeHtml(user.bank_name || '—')}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Account Name</p>
                                        <p class="text-gray-800">${escapeHtml(user.bank_account_name || '—')}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Account Number</p>
                                        <p class="text-gray-800 font-mono">${escapeHtml(user.bank_account_number || '—')}</p>
                                    </div>`}
                                </div>
                            </div>
                            <div class="col-span-2 border-t border-gray-100 pt-3 mt-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Registered</p>
                                <p class="text-gray-800">${new Date(user.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                            </div>
                        </div>
                    `;

                    approveForm.action = `/admin/users/${user.id}/approve`;
                    rejectForm.action = `/admin/users/${user.id}/reject`;

                    if (user.kyc_status === 'pending') {
                        approveForm.classList.remove('hidden');
                        rejectForm.classList.remove('hidden');
                    }
                })
                .catch(() => {
                    content.innerHTML = `<p class="text-sm text-red-600 text-center py-8">Failed to load user details.</p>`;
                });
        }

        function closeKYC() {
            document.getElementById('kycModal').classList.add('hidden');
            document.getElementById('kycModal').classList.remove('flex');
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function capitalize(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        document.getElementById('kycModal').addEventListener('click', function(e) {
            if (e.target === this) closeKYC();
        });
    </script>
</x-admin-layout>