<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index()
    {
        $testimonials = Testimonial::with('user')->latest()->get();
        return view('admin.feedbacks.index', compact('testimonials'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => true]);
        return back()->with('success', 'Feedback approved.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['is_active' => false]);
        return back()->with('success', 'Feedback rejected.');
    }
}
