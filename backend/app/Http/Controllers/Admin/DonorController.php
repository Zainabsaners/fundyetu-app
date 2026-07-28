<?php

namespace App\Http\Controllers\Admin;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super_admin']);
    }

    public function index(Request $request)
    {
        $query = Donation::select(
                'donor_phone',
                DB::raw('MAX(donor_name) as donor_name'),
                DB::raw('MAX(donor_email) as donor_email'),
                DB::raw('COUNT(*) as total_donations'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('MIN(created_at) as first_donation'),
                DB::raw('MAX(created_at) as last_donation')
            )
            ->where('status', 'completed')
            ->whereNotNull('donor_phone')
            ->where('donor_phone', '!=', '')
            ->groupBy('donor_phone');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->having('donor_name', 'like', "%{$search}%")
                  ->orHaving('donor_phone', 'like', "%{$search}%");
        }

        if ($request->filled('min_amount')) {
            $query->having('total_amount', '>=', (float) $request->min_amount);
        }

        $donors = $query->orderBy('total_amount', 'desc')
            ->get();

        return view('admin.donors.index', compact('donors'));
    }
}
