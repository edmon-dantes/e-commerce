<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SectionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => SectionResource::collection($this->collection),
            'meta' => [],
        ];
    }
}
