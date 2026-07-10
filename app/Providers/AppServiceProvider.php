<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Review;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\StoreSetting;
use App\Models\ShippingMethod;
use App\Models\Currency;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\AuditLogPolicy;
use App\Policies\UserPolicy;
use App\Policies\StoreSettingPolicy;
use App\Policies\ShippingMethodPolicy;
use App\Policies\CurrencyPolicy;
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
        Gate::policy(AuditLog::class, AuditLogPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(StoreSetting::class, StoreSettingPolicy::class);
        Gate::policy(ShippingMethod::class, ShippingMethodPolicy::class);
        Gate::policy(Currency::class, CurrencyPolicy::class);

        // Admins can bypass all policy checks
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('Admin')) {
                return true;
            }
        });
    }
}
