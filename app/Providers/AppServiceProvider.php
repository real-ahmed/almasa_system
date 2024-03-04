<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\GeneralSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $general = Cache::get('GeneralSetting');
        if (!$general) {
            $general = GeneralSetting::first();/// call from DB
            Cache::put('GeneralSetting', $general);
        }
        $viewShare['general'] = $general;
        view()->share($viewShare);
    }
}
