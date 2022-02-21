<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PermissionResource::collection($this->collection),
            'meta' => [
                // 'count' => $this->collection->count()
            ]
        ];
    }
}
