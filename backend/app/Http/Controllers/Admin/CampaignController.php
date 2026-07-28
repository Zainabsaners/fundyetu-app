<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CampaignStatus;
use App\Mail\CampaignStatusMail;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Withdrawal;
use App\Notifications\CampaignRejected;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['user', 'category', 'media']);
        $donations = $campaign->donations()->where('status', 'completed')->latest()->paginate(10);
        $withdrawals = $campaign->withdrawals()->latest()->paginate(20);
        $totalWithdrawn = $campaign->withdrawals()
            ->whereIn('status', ['disbursed', 'admin_approved'])
            ->sum('amount');
        $balance = $campaign->balance;

        return view('admin.campaigns.show', compact('campaign', 'donations', 'withdrawals', 'totalWithdrawn', 'balance'));
    }

    public function index(Request $request)
    {
        $query = Campaign::with(['user', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $campaigns = $query->latest()->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function verify(Campaign $campaign)
    {
        $campaign->update([
            'status' => CampaignStatus::Active,
            'verified_at' => now(),
        ]);

        Mail::to($campaign->user->email)->send(new CampaignStatusMail($campaign, 'approved'));

        return back()->with('success', 'Campaign approved. Fundraiser notified via email.');
    }

    public function reject(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $campaign->update([
            'status' => CampaignStatus::Draft,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $campaign->user->notify(new CampaignRejected($campaign, $validated['rejection_reason']));

        return back()->with('success', 'Campaign rejected. Fundraiser notified via email.');
    }

    public function close(Campaign $campaign)
    {
        $campaign->update(['status' => CampaignStatus::Closed]);

        return back()->with('success', 'Campaign closed successfully.');
    }

    public function updateStatus(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:draft,pending_verification,active,paused,completed,cancelled,closed',
        ]);

        $campaign->update(['status' => CampaignStatus::from($validated['status'])]);

        return back()->with('success', 'Campaign status updated to ' . str_replace('_', ' ', $validated['status']) . '.');
    }

    public function edit(Campaign $campaign)
    {
        $categories = \App\Models\Category::ordered()->get();
        return view('admin.campaigns.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'story' => 'nullable|string',
            'target_amount' => 'required|numeric|min:100',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
            'expiry_date' => 'nullable|date',
            'is_treasurer_controlled' => 'nullable|boolean',
            'treasurer_name' => 'nullable|string|max:255',
            'treasurer_phone' => 'nullable|string|max:20',
            'treasurer_id_number' => 'nullable|string|max:30',
        ]);

        $validated['is_treasurer_controlled'] = $request->boolean('is_treasurer_controlled');

        $campaign->update($validated);

        if ($request->hasFile('video_file')) {
            $campaign->clearMediaCollection('video');
            $campaign->addMedia($request->file('video_file'))->toMediaCollection('video');
        }

        if ($request->boolean('delete_video')) {
            $campaign->clearMediaCollection('video');
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $campaign->addMedia($image)->toMediaCollection('gallery');
            }
        }

        if ($request->has('delete_gallery')) {
            $mediaIds = $request->input('delete_gallery');
            $campaign->media()
                ->whereIn('id', $mediaIds)
                ->where('collection_name', 'gallery')
                ->get()
                ->each(fn($media) => $media->delete());
        }

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function extend(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'expiry_date' => 'required|date|after:today',
        ]);

        $campaign->update(['expiry_date' => $validated['expiry_date']]);

        return back()->with('success', 'Campaign deadline extended to ' . $validated['expiry_date'] . '.');
    }
}
