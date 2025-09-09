<?php

namespace App\Providers;

use App\Domain\Country\Cameroon;
use App\Domain\Country\Ethiopia;
use App\Domain\Country\Morocco;
use App\Domain\Country\Mozambique;
use App\Domain\Country\Uganda;
use App\Domain\Phone\PhoneNumberParser;
use Illuminate\Support\ServiceProvider;

class PhoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(PhoneNumberParser::class, function ($app) {
            $rules = [
                $app->make(Cameroon::class),
                $app->make(Ethiopia::class),
                $app->make(Morocco::class),
                $app->make(Mozambique::class),
                $app->make(Uganda::class),
            ];

            return new PhoneNumberParser($rules);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
