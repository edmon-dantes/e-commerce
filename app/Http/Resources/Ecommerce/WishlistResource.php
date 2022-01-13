<?php

namespace App\Http\Resources\Ecommerce;

use App\Http\Resources\ProductResource;
use App\Traits\JwtUtils;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    use JwtUtils;

    protected $instanceName = 'wishliststorage';

    public function toArray($request)
    {
        $cart = app($this->instanceName)->session($this->getPayloadId());

        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'slug' => $this->associatedModel->slug,
            'product' => new ProductResource($this->associatedModel),
        ];

        if (!!$cartItem = $cart->get($this->id)) {
            $result = array_merge($result, [
                'cart_sub_total' => $cartItem->getPriceSum(),
                'cart_price_with_condition' => $cartItem->getPriceWithConditions(),
                'cart_total' => $cartItem->getPriceSumWithConditions(),
            ]);
        }

        return $result;
    }
}
