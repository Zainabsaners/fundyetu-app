<?php

namespace App\Http\Controllers;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\CampaignComment;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\CampaignPendingVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::where('status', CampaignStatus::Active)
            ->with(['user', 'category', 'media']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('story', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'newest');
        
        $query->orderBy(match ($sort) {
            'most_raised' => 'raised_amount',
            'expiring' => 'expiry_date',
            default => 'created_at',
        }, $sort === 'oldest' ? 'asc' : 'desc');

        $campaigns = $query->paginate(12);
        $categories = Category::ordered()->get();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    public function create()
    {
        $categories = Category::ordered()->get();

        return view('campaigns.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->boolean('save_draft')) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'target_amount' => 'nullable|numeric|min:0',
                'story' => 'nullable|string',
                'expiry_date' => 'nullable|date',
                'video_url' => 'nullable|url',
                'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
                'is_treasurer_controlled' => 'nullable|boolean',
                'treasurer_name' => 'nullable|string|max:255',
                'treasurer_phone' => 'nullable|string|max:20',
                'treasurer_id_number' => 'nullable|string|max:50',
            ]);

            $validated['user_id'] = auth()->id();
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
            $validated['status'] = CampaignStatus::Draft;
            $validated['target_amount'] = $validated['target_amount'] ?? 0;
            $validated['is_treasurer_controlled'] = $request->boolean('is_treasurer_controlled');

            $campaign = Campaign::create($validated);

            if ($request->hasFile('video_file')) {
                $campaign->addMedia($request->file('video_file'))->toMediaCollection('video');
            }

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $campaign->addMedia($image)->toMediaCollection('gallery');
                }
            }

            return redirect()->route('campaigns.create', ['_step' => $request->integer('_step')])
                ->with('toast', 'Campaign saved as draft!');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'target_amount' => 'required|numeric|min:100',
            'story' => 'nullable|string',
            'expiry_date' => 'nullable|date|after:today',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_treasurer_controlled' => 'nullable|boolean',
            'treasurer_name' => 'required_if:is_treasurer_controlled,1|nullable|string|max:255',
            'treasurer_phone' => 'required_if:is_treasurer_controlled,1|nullable|string|max:20',
            'treasurer_id_number' => 'required_if:is_treasurer_controlled,1|nullable|string|max:50',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['status'] = CampaignStatus::Draft;
        $validated['is_treasurer_controlled'] = $request->boolean('is_treasurer_controlled');

        $campaign = Campaign::create($validated);

        if ($request->hasFile('video_file')) {
            $campaign->addMedia($request->file('video_file'))->toMediaCollection('video');
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $campaign->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('campaigns.edit', $campaign)
            ->with('success', 'Campaign created! Add more details if needed.');
    }

    public function myCampaigns()
    {
        Campaign::where('status', CampaignStatus::Active)
            ->where('expiry_date', '<=', now())
            ->update(['status' => CampaignStatus::Closed]);

        $campaigns = auth()->user()->campaigns()
            ->with(['category', 'media'])
            ->latest()
            ->get();

        return view('campaigns.my-campaigns', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        if ($campaign->status !== CampaignStatus::Active) {
            if (!auth()->check() || (int) auth()->id() !== (int) $campaign->user_id) {
                abort(404);
            }
        }

        $campaign->load(['user', 'category', 'donations' => function ($q) {
            $q->where('status', 'completed')->latest()->limit(10);
        }, 'patrons', 'media', 'comments.user', 'comments.replies.user']);

        $enabledMethods = [
            'mpesa' => Setting::get('mpesa_enabled', '1') === '1',
            'airtel' => Setting::get('airtel_enabled', '1') === '1',
            'card' => Setting::get('card_enabled', '0') === '1',
            'paypal' => Setting::get('paypal_enabled', '0') === '1',
        ];

        return view('campaigns.show', compact('campaign', 'enabledMethods'));
    }

    public function comment(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:campaign_comments,id',
        ]);

        $campaign->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Comment posted!');
    }

    public function edit(Campaign $campaign)
    {
        Log::info('Edit method reached', ['campaign_id' => $campaign->id, 'user_id' => auth()->id(), 'campaign_user_id' => $campaign->user_id]);
        $categories = Category::ordered()->get();

        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {

        if ($request->boolean('save_draft')) {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'target_amount' => 'nullable|numeric|min:0',
                'story' => 'nullable|string',
                'expiry_date' => 'nullable|date',
                'video_url' => 'nullable|url',
                'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
                'is_treasurer_controlled' => 'nullable|boolean',
                'treasurer_name' => 'nullable|string|max:255',
                'treasurer_phone' => 'nullable|string|max:20',
                'treasurer_id_number' => 'nullable|string|max:50',
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

            return redirect()->route('campaigns.edit', ['campaign' => $campaign, '_step' => $request->integer('_step')])
                ->with('toast', 'Campaign saved as draft!');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'target_amount' => 'required|numeric|min:100',
            'story' => 'nullable|string',
            'expiry_date' => 'nullable|date|after:today',
            'video_url' => 'nullable|url',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:51200',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'delete_gallery' => 'nullable|array',
            'delete_gallery.*' => 'integer|exists:media,id',
            'is_treasurer_controlled' => 'nullable|boolean',
            'treasurer_name' => 'required_if:is_treasurer_controlled,1|nullable|string|max:255',
            'treasurer_phone' => 'required_if:is_treasurer_controlled,1|nullable|string|max:20',
            'treasurer_id_number' => 'required_if:is_treasurer_controlled,1|nullable|string|max:50',
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

        if ($request->filled('delete_gallery')) {
            $campaign->media()
                ->whereIn('id', $request->delete_gallery)
                ->where('collection_name', 'gallery')
                ->get()
                ->each->delete();
        }

        return redirect()->route('campaigns.edit', ['campaign' => $campaign, '_step' => $request->integer('_step')])
            ->with('toast', 'Campaign updated!');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.my')
            ->with('toast', 'Campaign deleted.');
    }

    public function publish(Campaign $campaign)
    {
        if (auth()->user()->kyc_status !== 'verified') {
            return redirect()->route('campaigns.edit', $campaign)
                ->with('toast', 'You must complete KYC verification before submitting a campaign.');
        }

        $campaign->update([
            'status' => CampaignStatus::PendingVerification,
        ]);

        $admins = User::role(['admin', 'super_admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new CampaignPendingVerification($campaign));
        }

        return redirect()->route('campaigns.my')
            ->with('toast', 'Campaign submitted for verification!');
    }



    /**
 * API: List all active campaigns (JSON)
 */
public function apiIndex(Request $request)
{
    $query = Campaign::where('status', CampaignStatus::Active)
        ->with(['user', 'category', 'media']);

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('story', 'like', "%{$search}%");
        });
    }

    $sort = $request->get('sort', 'newest');
    $query->orderBy(match ($sort) {
        'most_raised' => 'raised_amount',
        'expiring'    => 'expiry_date',
        default       => 'created_at',
    }, $sort === 'oldest' ? 'asc' : 'desc');

    $campaigns = $query->paginate(12);

    return response()->json([
        'status' => 'success',
        'data'   => $campaigns->items(),
        'meta'   => [
            'current_page' => $campaigns->currentPage(),
            'last_page'    => $campaigns->lastPage(),
            'per_page'     => $campaigns->perPage(),
            'total'        => $campaigns->total(),
        ],
    ]);
}

/**
 * API: Single campaign details (JSON)
 */
public function apiShow(Campaign $campaign)
{
    if ($campaign->status !== CampaignStatus::Active) {
        return response()->json(['status' => 'error', 'message' => 'Campaign not found'], 404);
    }

    $campaign->load(['user', 'category', 'media']);

    return response()->json([
        'status' => 'success',
        'data'   => $campaign,
    ]);
}
}
