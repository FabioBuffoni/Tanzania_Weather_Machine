<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Contracts\SmsService::class, function ($app) {
            $config = config('services.twilio');
            return new \App\Services\TwilioSmsService(
                $config['sid'],
                $config['token'],
                $config['from'],
                $config['messaging_service_sid'] ?? null
            );
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
