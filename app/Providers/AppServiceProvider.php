<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Review;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CurrencyService::class, fn() => new CurrencyService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        // Register Policies
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);

        // Admins can bypass all policy checks
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Admin')) {
                return true;
            }
        });
    }
}
