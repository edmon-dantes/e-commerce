<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => RoleResource::collection($this->collection),
            'meta' => [
                // 'count' => $this->collection->count()
            ]
        ];
    }
}
