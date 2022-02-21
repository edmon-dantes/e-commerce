<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BannerRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.name' => ['required', 'string', 'max:255', 'unique:banners,name'],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.url' => ['required', 'string', 'max:255', 'unique:banners,url'],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.picture.name' => ['string', 'max:255'],
            'data.picture.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function update()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:banners,id'],
            'data.name' => ['required', 'string', 'max:255',  Rule::unique('banners', 'name')->ignore($this->banner)],
            'data.description' => ['nullable', 'string', 'max:255'],
            'data.url' => ['required', 'string', 'max:255', Rule::unique('banners', 'url')->ignore($this->banner)],
            'data.status' => ['integer', 'min:0', 'digits_between: 0,1'],

            'data.picture.name' => ['string', 'max:255'],
            'data.picture.file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function destroy()
    {
        return [
            'data.id' => ['required', 'integer', 'exists:banners,id'],
        ];
    }

    public function attributes()
    {
        return [
            'data.id' => 'id',
            'data.name' => 'name',
            'data.description' => 'description',
            'data.url' => 'url',
            'data.status' => 'status',
            'data.picture' => 'picture',
        ];
    }
}
