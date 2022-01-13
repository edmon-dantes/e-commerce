<?php

namespace App\Providers;

use App\Classes\CartStorage;
use Carbon\Laravel\ServiceProvider;
use Darryldecode\Cart\Cart;

class CartStorageProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('cartstorage', function ($app) {
            $storage = new CartStorage();
            $events = $app['events'];
            $instanceName = 'cart_2';
            $session_key = '888888888888888';

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
