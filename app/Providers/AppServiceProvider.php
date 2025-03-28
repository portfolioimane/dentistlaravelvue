<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PayPalService;
use App\Services\EmailService;
use App\Services\TwilioService;
use App\Services\EmailSettingService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
          $this->app->singleton(PayPalService::class, function ($app) {
            return new PayPalService();
        });

              $this->app->singleton(EmailService::class, function ($app) {
            return new EmailService();
        });

          $this->app->singleton(TwilioService::class, function ($app) {
            return new TwilioService();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
