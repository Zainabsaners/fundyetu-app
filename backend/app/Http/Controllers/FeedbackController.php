<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedbacks.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();

        Testimonial::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'content' => $validated['comment'] ?? 'Great platform!',
            'rating' => $validated['rating'],
            'initials' => strtoupper(substr($user->name, 0, 1)),
            'is_active' => false,
        ]);

        return back()->with('success', 'Thank you for your feedback! It will be visible after admin approval.');
    }
}
