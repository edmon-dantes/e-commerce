<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Resources\Ecommerce\WishlistCollection;
use App\Http\Resources\Ecommerce\WishlistResource;
use App\Models\Product;
use App\Traits\CartUtils;
use App\Traits\JwtUtils;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use CartUtils, JwtUtils;

    protected $cart;
    protected $instanceName = 'wishliststorage';

    public function __construct()
    {
        $this->middleware(['jwt.invited']);

        $this->cart = app($this->instanceName)->session($this->getPayloadId());
    }

    public function index()
    {
        $attributes = ['collections' => (object)[]];
        return (new WishlistCollection($this->cart->getContent()))->additional(['meta' => $attributes]);
    }

    public function store(Request $request, Product $product)
    {
        $cartItem = $this->_store($this->cart, $product, 1);
        return $this->responseCart($cartItem, ['message' => 'Successfully added.']);
    }

    public function destroy(Product $product)
    {
        $cartItem = $this->_destroy($this->cart, $product);
        return $this->responseCart($cartItem, ['message' => 'Successfully deleted.']);
    }

    protected function responseCart($cartItem, $attributes = null)
    {
        $cartContent = $this->cart->getContent();
        $attributes = array_merge((array) $attributes, [
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => $cartContent->count(),
            'total' => $cartContent->count(),
            'cart_total_quantity' => $this->cart->getTotalQuantity(),
            'cart_sub_total' => $this->cart->getSubTotal(),
            'cart_grand_total' => $this->cart->getTotal(),
        ]);
        return (new WishlistResource($cartItem))->additional(['meta' => $attributes]);
    }
}
