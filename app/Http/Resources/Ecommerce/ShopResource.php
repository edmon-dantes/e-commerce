<?php

namespace App\Http\Resources\Ecommerce;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'rating' => $this->rating,
            'active' => $this->active,
            'user' => new UserResource($this->whenLoaded('user')),
        ];

        if (!!$this->id) {
            $result = array_merge($result, [
                'created_at' => $this->created_at->format('d-m-Y'),
                'updated_at' => $this->updated_at->format('d-m-Y'),
            ]);
        }

        return $result;
    }
}
