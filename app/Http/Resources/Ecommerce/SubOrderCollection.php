<?php

namespace App\Http\Resources\Ecommerce;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubOrderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
