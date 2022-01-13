<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CategoryRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.discount' => ['required', 'numeric'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.parent_id' => ['nullable', 'integer'],
            'data.section_id' => ['required', 'integer'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function update()
    {

        return [
            'data.id' => ['required', 'integer', 'exists:categories,id'],
            'data.name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->category)],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.discount' => ['required', 'numeric'],
            'data.meta_title' => ['required', 'string', 'max:255'],
            'data.meta_description' => ['required', 'string', 'max:255'],
            'data.meta_keywords' => ['required', 'string', 'max:255'],
            'data.parent_id' => ['nullable', 'integer'],
            'data.section_id' => ['required', 'integer'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.description' => 'description',
            'data.discount' => 'discount',
            'data.meta_title' => 'meta_title',
            'data.meta_description' => 'meta_description',
            'data.meta_keywords' => 'meta_keywords',
            'data.parent_id' => 'parent_id',
            'data.section_id' => 'section_id',
            'data.status' => 'status',
            'data.photo' => 'photo',
        ];
    }
}
