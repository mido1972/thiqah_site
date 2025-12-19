<?php

namespace App\Providers;

use App\Support\Settings;
use App\View\Composers\WebsiteLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // ✅ Share settings globally with all views (safe even if DB is down due to try/catch in Settings helper)
        View::share('siteSettings', Settings::all());

        // ✅ Provide website views with (menus/seo/lang/social) so child views can use variables too
        View::composer('website.*', WebsiteLayoutComposer::class);
    }
}
