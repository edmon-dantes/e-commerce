<?php

namespace App\Http\Resources\Ecommerce;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'data' => OrderResource::collection($this->collection),
            'meta' => [
                'count' => $this->collection->count()
            ]
        ];
    }
}
