<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Services\CartsService;

class CartController extends Controller
{
    const MODEL_WITH = [];

    protected $cart;

    public function __construct()
    {
        $instance = match (request()->input('instance')) {
            'cart' => 'cartstorage',
            'wishlist' => 'wishliststorage',
            'compare' => 'comparestorage',
            default => 'cartstorage'
        };

        $this->cart = app($instance)->session(request()->input('token', 'null'));
    }

    public function index(CartRequest $request)
    {
        $additional = ['collections' => []];

        return (new CartCollection($this->cart))->additional($additional);
    }

    public function store(CartRequest $request, CartsService $service)
    {
        $cartItem = $service->store($request, $this->cart, $request->input('data.product.id'));

        $additional = ['meta' => ['message' => 'Successfully created.']];

        return (new CartResource($cartItem))->additional($additional);
    }

    public function update(CartRequest $request, $cart_item, CartsService $service)
    {
        $cartItem = $service->update($request, $this->cart, $cart_item);

        $additional = ['meta' => ['message' => 'Successfully updated.']];

        return (new CartResource($cartItem))->additional($additional);
    }

    public function destroy(CartRequest $request, $cart_item, CartsService $service)
    {
        $cartItem = $service->destroy($this->cart, $cart_item);

        $additional = ['meta' => ['message' => 'Successfully deleted.']];

        return (new CartResource($cartItem))->additional($additional);
    }

    public function destroy_all(CartRequest $request, CartsService $service)
    {
        $this->cart = $service->destroy_all($this->cart);

        $additional = ['meta' => ['message' => 'Successfully cleaned.']];

        return (new CartCollection($this->cart))->additional($additional);
    }
}
