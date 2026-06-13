<?php

namespace App\Providers;

use App\Models\Setting;
use App\Services\CacheService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CacheService::class, fn () => new CacheService);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        View::composer('new-store.*', function ($view) {
            try {
                $cache = app(CacheService::class);
                $settings = $cache->remember('settings.all', function () {
                    return Setting::all()->pluck('value', 'key');
                }, null);

                $logoUrl = $settings->get('logo');
                $logoSrc = $logoUrl
                    ? asset('storage/'.$logoUrl)
                    : asset('new-store/images/Logo.svg');

                $view->with(compact('settings', 'logoSrc'));
            } catch (\Throwable) {
                $view->with('settings', collect());
                $view->with('logoSrc', asset('new-store/images/Logo.svg'));

            }
        });
    }
}
