<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PhotoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => ImageResource::collection($this->collection),
            'meta' => []
        ];
    }
}
