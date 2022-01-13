<?php

namespace App\Http\Resources\Ecommerce;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->pivot->name,
            'description' => $this->description,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->price,
        ];
    }
}
