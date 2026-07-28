<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Policies\CampaignPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Campaign::class, CampaignPolicy::class);

        if (! file_exists(public_path('storage'))) {
            try {
                symlink(
                    storage_path('app/public'),
                    public_path('storage')
                );
            } catch (\Throwable $e) {
                //
            }
        }
    }
}
