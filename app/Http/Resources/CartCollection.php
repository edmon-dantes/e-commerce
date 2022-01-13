<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    protected $cart;

    public function __construct($cart)
    {
        $this->cart = $cart;
    }

    public function toArray($request)
    {
        return [
            'data' => CartResource::collection($this->cart->getContent()),
            'meta' => [
                'total_quantity' => $this->cart->getTotalQuantity(),
                'sub_total' => $this->cart->getSubTotal(),
                'grand_total' => $this->cart->getTotal(),
            ],
        ];
    }
}
