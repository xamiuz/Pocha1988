<?php

namespace App\Providers;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Schema;
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
        View::composer('*', function ($view) {
            if (! $view->offsetExists('paymentSettings')) {
                try {
                    if (Schema::hasTable('payment_settings')) {
                        $view->with('paymentSettings', PaymentSetting::current());
                    }
                } catch (\Throwable $e) {
                    // Ignore during migration or console commands
                }
            }
        });
    }
}
