<?php

namespace App\Http\Resources\Ecommerce;

use App\Traits\JwtUtils;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WishlistCollection extends ResourceCollection
{
    use JwtUtils;

    protected $instanceName = 'wishliststorage';

    public function toArray($request)
    {
        $cart = app($this->instanceName)->session($this->getPayloadId());

        return [
            'data' => WishlistResource::collection($this->collection),
            'meta' => [
                'count' => $this->collection->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $this->collection->count(),
                'total' => $this->collection->count(),
                'cart_total_quantity' => $cart->getTotalQuantity(),
                'cart_sub_total' => $cart->getSubTotal(),
                'cart_grand_total' => $cart->getTotal(),
            ]
        ];
    }
}
