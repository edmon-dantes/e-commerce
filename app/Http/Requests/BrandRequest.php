<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BrandRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255', 'unique:brands,name'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:brands,id'],
            'data.name' => ['required', 'string', 'max:255',  Rule::unique('brands', 'name')->ignore($this->brand)],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.photo.name' => ['string', 'max:255'],
            'data.photo.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:brands,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.description' => 'description',
            'data.status' => 'status',
            'data.photo' => 'photo',
        ];
    }
}
