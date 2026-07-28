<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AppSetup extends Command
{
    protected $signature = 'app:setup';
    protected $description = 'Run migrations and seeders once (idempotent)';

    public function handle()
    {
        // Check if setup has already run
        $lockFile = storage_path('app/setup.lock');

        if (File::exists($lockFile)) {
            $this->info('✅ Setup already ran. Skipping.');
            return 0;
        }

        $this->info('🔧 Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('🌱 Running seeders...');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--force' => true]);
        $this->info(Artisan::output());

        // Create lock file to prevent re-running
        File::put($lockFile, now()->toDateTimeString());

        $this->info('✅ Setup completed successfully.');

        return 0;
    }
}