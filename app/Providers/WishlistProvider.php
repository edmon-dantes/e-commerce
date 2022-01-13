<?php

namespace App\Providers;

use App\Classes\WishlistStorage;
use Darryldecode\Cart\Cart;
use Illuminate\Support\ServiceProvider;

class WishlistProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('wishliststorage', function ($app) {
            $storage = new WishlistStorage();
            $events = $app['events'];
            $instanceName = 'cart_3';
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
