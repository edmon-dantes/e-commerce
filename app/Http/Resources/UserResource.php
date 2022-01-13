<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'mothers_lastname' => $this->mothers_lastname,
            'username' => $this->username,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'slug' => $this->slug,
            'fullname' => $this->fullname,
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
