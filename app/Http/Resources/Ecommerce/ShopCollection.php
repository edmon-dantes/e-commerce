<?php

namespace App\Http\Resources\Ecommerce;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => ShopResource::collection($this->collection),
            'meta' => [
                'count' => $this->collection->count()
            ]
        ];
    }
}
