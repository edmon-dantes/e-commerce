<?php

namespace App\Classes;

use App\Models\Cart;
use Darryldecode\Cart\CartCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompareStorage
{
    protected $type = 'compare';

    public function has($key)
    {
        if (is_null($key) || $key === 'null') {
            throw new NotFoundHttpException('Token does not exist.');
        }

        if (auth()->check() && !!$cart = Cart::where(['type' => $this->type, 'user_id' => auth()->id(), 'status' => 1])->latest()->first()) {
            return $cart;
        }

        $cart = Cart::where(['token' => $key, 'type' => $this->type, 'status' => 1])->first();
        if (!!$cart && auth()->check()) {
            $cart->update(['user_id' => auth()->id()]);
        }

        return $cart;
    }

    public function get($key)
    {
        if (!!$cart = $this->has($key)) {
            if (str_contains($key, 'conditions')) { // agregado por un error de la libreria cunado usas usuario logueado
                return $cart->conditions; // return empty array
            }
            return new CartCollection($cart->cart_data);
        }
        return [];
    }

    public function put($key, $value)
    {
        if (!!$cart = $this->has($key)) {
            $cart->update(['cart_data' => $value]);
            return;
        }

        $attrs = ['token' => $key, 'type' => $this->type, 'cart_data' => $value, 'status' => 1];
        if (auth()->check()) {
            $attrs['user_id'] = auth()->id();
        }

        Cart::create($attrs);
    }
}
