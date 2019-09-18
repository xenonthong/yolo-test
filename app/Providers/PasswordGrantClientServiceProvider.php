<?php

namespace App\Providers;

use App\Oauth\PasswordGrantClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class PasswordGrantClientServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PasswordGrantClient::class, function () {
            return new PasswordGrantClient(new Client());
        });
    }
}
