<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'attributes' => $this->attributes,
            'product' => new ProductResource($this->associatedModel->load('pictures')),
            'sub_total' => $this->getPriceSum(),
            'price_with_condition' => $this->getPriceWithConditions(),
            'total' => $this->getPriceSumWithConditions()
        ];

        return $result;
    }
}
