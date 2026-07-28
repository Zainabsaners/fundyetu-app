<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index(Request $request)
    {
        $query = Donation::with(['campaign', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'completed');
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%")
                  ->orWhere('payment_ref', 'like', "%{$search}%");
            });
        }

        $donations = $query->latest()->get();

        $paymentMethods = Donation::select('payment_method')
            ->distinct()
            ->whereNotNull('payment_method')
            ->pluck('payment_method');

        return view('admin.donations.index', compact('donations', 'paymentMethods'));
    }
}
