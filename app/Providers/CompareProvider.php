<?php

namespace App\Providers;

use App\Classes\CompareStorage;
use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class CompareProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('comparestorage', function ($app) {
            $storage = new CompareStorage();
            $events = $app['events'];
            $instanceName = 'cart_4';
            $session_key = '88uuiioo99888';

            return new Cart(
                $storage,
                $events,
                $instanceName,
                $session_key,
                config('shopping_cart')
            );
        });
    }

    public function boot()
    {
    }
}
