<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'categories' => Category::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20', 'unique:' . User::class],
            'birth_year' => ['required', 'integer', 'min:1900', 'max:' . date('Y')],
            'campaign_title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'duration' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'id_number' => $request->id_number,
            'birth_year' => $request->birth_year,
            'kyc_status' => 'unverified',
        ]);

        event(new Registered($user));

        $user->sendEmailVerificationNotification();

        $campaign = Campaign::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->campaign_title,
            'slug' => str($request->campaign_title)->slug() . '-' . now()->timestamp,
            'target_amount' => $request->target_amount,
            'raised_amount' => 0,
            'status' => \App\Enums\CampaignStatus::Draft,
            'expiry_date' => Carbon::now()->addDays((int) $request->duration),
        ]);

        Auth::login($user);

        return redirect(route('pending.approval'));
    }
}
