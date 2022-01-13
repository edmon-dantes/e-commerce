<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'color' => $this->color,
            'price' => $this->price,
            'discount' => $this->discount,
            'weight' => $this->weight,
            'description' => $this->description,
            'details' => $this->details,
            'wash_care' => $this->wash_care,
            'fabric' => $this->fabric,
            'pattern' => $this->pattern,
            'sleeve' => $this->sleeve,
            'fit' => $this->fit,
            'occassion' => $this->occassion,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'section_id' => $this->section_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'slug' => $this->slug,
            'fullname' => $this->fullname,
            'section' => new SectionResource($this->whenLoaded('section')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'attributes' => ProductsAttributeResource::collection($this->whenLoaded('attributes')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
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
