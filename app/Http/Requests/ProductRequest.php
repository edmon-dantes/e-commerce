<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseFormRequest
{
    protected function prepareForValidation(): void
    {
        if (!$this->has('data.section_id') && $this->has('data.category_id')) {
            $category = Category::find((int)$this->input('data.category_id'));

            $originalInput = $this->input('data');
            $defaultRequest = ['section_id' => $category->section_id];

            $mergeRequest = array_merge($defaultRequest, $originalInput);

            $this->merge(['data' =>  $mergeRequest]);
        }
    }

    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255'],
            'data.code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'data.color' => ['required', 'string', 'max:255'],
            'data.price' => ['required', 'numeric'],
            'data.discount' => ['required'],
            'data.weight' => ['required'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.wash_care' => ['required'],
            'data.fabric' => ['required'],
            'data.pattern' => ['required'],
            'data.sleeve' => ['required'],
            'data.fit' => ['required'],
            'data.occassion' => ['required'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.is_featured' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.section_id' => ['required', 'integer'],
            'data.category_id' => ['required', 'integer'],
            'data.brand_id' => ['required', 'integer'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photos' => ['required', 'array', 'min:1'],
            'data.photos.*.name' => ['string', 'max:255'],
            'data.photos.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],

            'data.attributes' => ['required', 'array', 'min:1'],
            'data.attributes.*.size' => ['required', 'string', 'max:255', 'distinct'],
            'data.attributes.*.price' => ['required', 'numeric'],
            'data.attributes.*.stock' => ['required', 'numeric'],
            'data.attributes.*.sku' => ['required', 'string', 'max:255', 'distinct', 'unique:products_attributes,sku'],
            'data.attributes.*.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:products,id'],
            'data.name' => ['required', 'string', 'max:255'],
            'data.code' => ['required', 'string', 'max:255', Rule::unique('products', 'code')->ignore($this->product)],
            'data.color' => ['required', 'string', 'max:255'],
            'data.price' => ['required', 'numeric'],
            'data.discount' => ['required'],
            'data.weight' => ['required'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.wash_care' => ['required'],
            'data.fabric' => ['required'],
            'data.pattern' => ['required'],
            'data.sleeve' => ['required'],
            'data.fit' => ['required'],
            'data.occassion' => ['required'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.is_featured' => ['integer', 'min:0', 'digits_between: 0,1'],
            'data.section_id' => ['required', 'integer'],
            'data.category_id' => ['required', 'integer'],
            'data.brand_id' => ['required', 'integer'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photos' => ['required', 'array', 'min:1'],
            'data.photos.*.name' => ['string', 'max:255'],
            'data.photos.*.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],

            'data.attributes' => ['required', 'array', 'min:1'],
            'data.attributes.*.size' => ['required', 'string', 'max:255', 'distinct'],
            'data.attributes.*.price' => ['required', 'numeric'],
            'data.attributes.*.stock' => ['required', 'numeric'],
            'data.attributes.*.sku' => ['required', 'string', 'max:255', 'distinct', Rule::unique('products_attributes', 'sku')->ignore($this->product->id, 'product_id')],
            'data.attributes.*.status' => ['integer', 'min:0', 'digits_between: 0,1'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:products,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.code' => 'code',
            'data.color' => 'color',
            'data.price' => 'price',
            'data.discount' => 'discount',
            'data.weight' => 'weight',
            'data.description' => 'description',
            'data.wash_care' => 'wash_care',
            'data.fabric' => 'fabric',
            'data.pattern' => 'pattern',
            'data.sleeve' => 'sleeve',
            'data.fit' => 'fit',
            'data.occassion' => 'occassion',
            'data.meta_title' => 'meta_title',
            'data.meta_description' => 'meta_description',
            'data.meta_keywords' => 'meta_keywords',
            'data.is_featured' => 'is_featured',
            'data.section_id' => 'section_id',
            'data.category_id' => 'category_id',
            'data.brand_id' => 'brand_id',
            'data.attributes' => 'attributes',
            'data.attributes.*.sku' => 'sku',
            'data.status' => 'status',
            'data.photos' => 'photos',
        ];
    }
}
