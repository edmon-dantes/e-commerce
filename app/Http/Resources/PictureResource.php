<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PictureResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'file_name' => $this->file_name,
            'custom_properties' => $this->custom_properties,
            'order_column' => $this->order_column,
            'url' => $this->getUrl(),
        ];

        if ($this->hasGeneratedConversion('thumb-cropped')) {
            $result = array_merge($result, ['url_thumb_cropped' => $this->getUrl('thumb-cropped')]);
        }

        if (!!$this->id) {
            $result = array_merge($result, [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
        }

        return $result;
    }
}
