<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FileCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => FileResource::collection($this->collection),
            'meta' => [
                'count' => $this->collection->count()
            ]
        ];
    }
}
