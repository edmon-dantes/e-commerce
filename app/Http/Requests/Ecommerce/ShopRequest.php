<?php

namespace App\Http\Requests\Ecommerce;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Str;

class ShopRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['user_id' => auth()->id()]);

        if (!$this->has('slug')) {
            $this->merge(['slug' => Str::slug($this->input('name'), '-')]);
        }
    }

    public function rules()
    {
        $rules = [];
        $rules['description'] = 'nullable|string|max:255';
        $rules['rating'] = 'numeric';
        $rules['active'] = 'boolean';
        $rules['user_id'] = 'required';

        switch ($this->method()) {
            case 'POST':
                $rules['name'] = 'required|string|max:50|unique:shops';
                $rules['slug'] = 'required|string|max:50|unique:shops';
                break;
            case 'PUT':
                $rules['name'] = 'required|string|max:50|unique:shops,name,' . $this->shop->id;
                $rules['slug'] = 'required|string|max:50|unique:shops,slug,' . $this->shop->id;
                break;
        }

        return $rules;
    }
}
