<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'slug' => $this->slug,
            'photo' => new PhotoResource($this->whenLoaded('photo')),
        ];

        if (!!$this->id) {
            $result = array_merge($result, [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
        }

        return $result;
    }
}