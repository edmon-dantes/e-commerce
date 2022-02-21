<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseFormRequest
{
    // protected function prepareForValidation(): void
    // {
    //     if (!$this->has('data.section_id') && $this->has('data.category_id')) {
    //         $category = Category::find((int)$this->input('data.category_id'));

    //         $originalInput = $this->input('data');
    //         $defaultRequest = ['section_id' => $category->section_id];

    //         $mergeRequest = array_merge($defaultRequest, $originalInput);

    //         $this->merge(['data' =>  $mergeRequest]);
    //     }
    // }

    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255'],
            'data.sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.details' => ['nullable', 'string', 'max:255'],
            'data.price' => ['required', 'numeric'],
            'data.stock' => ['required', 'numeric'],
            'data.discount' => ['required'],
            'data.fabric' => ['required'],
            'data.pattern' => ['required'],
            'data.sleeve' => ['required'],
            'data.fit' => ['required'],
            'data.occassion' => ['required'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.is_featured' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.category_id' => ['required', 'integer', 'exists:App\Models\Category,id'],
            'data.brand_id' => ['required', 'integer', 'exists:App\Models\Brand,id'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.pictures' => ['array'],
            'data.pictures.*.name' => ['string', 'max:255'],
            'data.pictures.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],

            // 'data.attributes' => ['required', 'array', 'min:1'],
            // 'data.attributes.*.size' => ['required', 'string', 'max:255', 'distinct'],
            // 'data.attributes.*.sku' => ['required', 'string', 'max:255', 'distinct', 'unique:products_attributes,sku'],
            // 'data.attributes.*.price' => ['required', 'numeric'],
            // 'data.attributes.*.stock' => ['required', 'numeric'],
            // 'data.attributes.*.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Product,id'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product)],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.details' => ['nullable', 'string', 'max:255'],
            'data.price' => ['required', 'numeric'],
            'data.stock' => ['required', 'numeric'],
            'data.discount' => ['required'],
            'data.fabric' => ['required'],
            'data.pattern' => ['required'],
            'data.sleeve' => ['required'],
            'data.fit' => ['required'],
            'data.occassion' => ['required'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.is_featured' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.category_id' => ['required', 'integer', 'exists:App\Models\Category,id'],
            'data.brand_id' => ['required', 'integer', 'exists:App\Models\Brand,id'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.pictures' => ['array'],
            'data.pictures.*.name' => ['string', 'max:255'],
            'data.pictures.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],

            // 'data.attributes' => ['required', 'array', 'min:1'],
            // 'data.attributes.*.size' => ['required', 'string', 'max:255', 'distinct'],
            // 'data.attributes.*.sku' => ['required', 'string', 'max:255', 'distinct', Rule::unique('products_attributes', 'sku')->ignore($this->product->id, 'product_id')],
            // 'data.attributes.*.price' => ['required', 'numeric'],
            // 'data.attributes.*.stock' => ['required', 'numeric'],
            // 'data.attributes.*.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:App\Models\Product,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.sku' => 'sku',
            'data.description' => 'description',
            'data.details' => 'details',
            'data.price' => 'price',
            'data.discount' => 'discount',
            'data.fabric' => 'fabric',
            'data.pattern' => 'pattern',
            'data.sleeve' => 'sleeve',
            'data.fit' => 'fit',
            'data.occassion' => 'occassion',
            'data.meta_title' => 'meta_title',
            'data.meta_description' => 'meta_description',
            'data.meta_keywords' => 'meta_keywords',
            'data.is_featured' => 'is_featured',
            'data.category_id' => 'category_id',
            'data.brand_id' => 'brand_id',
            'data.status' => 'status',
            'data.pictures' => 'pictures',
            'data.attributes' => 'attributes',
            'data.attributes.*.sku' => 'sku',
        ];
    }
}
