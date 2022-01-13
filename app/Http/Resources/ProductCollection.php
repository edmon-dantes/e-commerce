<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => ProductResource::collection($this->collection),
            'meta' => [
                // 'count' => $this->collection->count()
            ]
        ];
    }
}
