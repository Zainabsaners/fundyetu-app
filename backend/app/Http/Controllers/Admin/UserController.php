<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Notifications\AccountDeleted;
use App\Notifications\KycApproved;
use App\Notifications\UserApproved;
use App\Notifications\UserDeactivated;
use App\Notifications\UserRejected;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index(Request $request)
    {
        $query = User::with('roles')
            ->withCount(['campaigns as campaigns_count', 'campaigns as active_campaigns_count' => function ($q) {
                $q->where('status', 'active');
            }])
            ->withSum('campaigns as total_raised', 'raised_amount')
            ->selectSub(function ($q) {
                $q->selectRaw('COUNT(DISTINCT donations.donor_email)')
                  ->from('donations')
                  ->join('campaigns', 'campaigns.id', '=', 'donations.campaign_id')
                  ->whereColumn('campaigns.user_id', 'users.id')
                  ->where('donations.status', 'completed');
            }, 'total_donors');

        if ($request->filled('kyc_status')) {
            $query->where('kyc_status', $request->kyc_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return response()->json($user->only([
            'id', 'name', 'email', 'phone', 'kyc_status',
            'id_number', 'birth_year', 'address',
            'bank_name', 'bank_account_number', 'bank_account_name',
            'mpesa_phone', 'withdrawal_method',
            'id_front_path', 'id_back_path', 'address_proof_path',
            'profile_photo_path', 'sms_credits',
            'email_verified_at', 'phone_verified_at', 'created_at',
        ]));
    }

    public function approve(User $user)
    {
        $user->update(['kyc_status' => 'verified']);
        $user->notify(new KycApproved($user));

        return back()->with('success', 'KYC approved. User notified via email.');
    }

    public function reject(Request $request, User $user)
    {
        $reason = $request->input('reason');
        $user->update(['kyc_status' => 'rejected']);
        $user->notify(new UserRejected($user, $reason));

        return back()->with('success', 'KYC rejected.');
    }

    public function activate(User $user)
    {
        $user->update([
            'is_approved' => true,
            'sms_credits' => 250,
        ]);
        $user->assignRole('fundraiser');
        $user->notify(new UserApproved($user));

        return back()->with('success', 'User activated. They can now access the dashboard.');
    }

    public function deactivate(Request $request, User $user)
    {
        $reason = $request->input('reason');
        $user->update(['is_approved' => false]);
        $user->removeRole('fundraiser');
        $user->notify(new UserDeactivated($user, $reason));

        return back()->with('success', 'User deactivated. Dashboard access revoked.');
    }

    public function details(User $user)
    {
        $user->load('roles');

        $campaigns = $user->campaigns()->with('category')->latest()->paginate(10);
        $totalRaised = $user->campaigns()->sum('raised_amount');
        $activeCampaigns = $user->campaigns()->where('status', 'active')->count();
        $totalCampaigns = $user->campaigns()->count();

        return view('admin.users.show', compact('user', 'campaigns', 'totalRaised', 'activeCampaigns', 'totalCampaigns'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'id_number' => 'nullable|string|max:30',
            'birth_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'address' => 'nullable|string|max:500',
            'kyc_status' => 'nullable|string|in:unverified,pending,verified,rejected',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        $reason = $request->input('reason');

        try {
            $user->notify(new AccountDeleted($user, $reason));
        } catch (\Throwable $e) {
            // Continue with deletion even if notification fails
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
