<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
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
        Http::macro('nyt', function () {
            return $this
                ->baseUrl('https://api.nytimes.com/svc/books/v3')
                ->withQueryParameters([
                    'api-key' => config('services.nyt.key'),
                ]);
        });
    }
}
