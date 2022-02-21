<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'discount' => $this->discount,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'status' => $this->status,
            'slug' => $this->slug,
            'section_id' => $this->section_id,
            'parent_id' => $this->parent_id,
            'section' => new SectionResource($this->whenLoaded('section')),
            'parent' => new CategoryResource($this->whenLoaded('parent')),
            // 'children' => CategoryResource::collection($this->whenLoaded('children')),
            'children_recursive' => CategoryResource::collection($this->whenLoaded('children_recursive')),
            'picture' => new PictureResource($this->whenLoaded('picture')),
        ];

        if (!!$this->id) {
            $result = array_merge($result, [
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
        }

        return $result;
    }

    /*
public function toArray($request)
{
$result = [
'id' => $this->id,
'name' => $this->name,
'description' => $this->description,
'active' => $this->active,
'products' => ProductResource::collection($this->whenLoaded('products')),
];

if (!!$this->id) {
$result = array_merge($result, [
'created_at' => $this->created_at->format('d-m-Y'),
'updated_at' => $this->updated_at->format('d-m-Y'),
]);
}

return $result;
}
 */
}
