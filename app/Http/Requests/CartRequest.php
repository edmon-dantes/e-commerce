<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CartRequest extends BaseFormRequest
{
    public function view()
    {
        return [
            'token' => ['required', 'string'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])],
        ];
    }

    public function store()
    {
        return [
            'token' => ['required', 'string'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])],
            'data.product.id' => ['required', 'integer'],
            'data.quantity' => ['required_if:instance,cart', 'numeric'],
        ];
    }

    public function update()
    {
        return [
            'token' => ['required', 'string'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])],
            'data.id' => ['required', 'integer'],
            'data.quantity' => ['required_if:instance,cart', 'numeric'],
        ];
    }

    public function destroy()
    {
        return [
            'token' => ['required', 'string'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])],
        ];
    }

    public function attributes()
    {
        return [
            'token' => 'token',
            'instance' => 'instance',
            'data.id' => 'id',
            'data.product.id' => 'id',
            'data.quantity' => 'quantity',
        ];
    }
}
