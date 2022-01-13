<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class CartRequest extends BaseFormRequest
{
    public function view()
    {
        return [];
    }

    public function store()
    {
        return [
            'data.product.id' => ['required', 'integer'],
            'data.quantity' => ['required', 'numeric'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])]
        ];
    }

    public function update()
    {
        return [
            'data.product.id' => ['required', 'integer'],
            'data.quantity' => ['required', 'numeric'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])]
        ];
    }

    public function destroy()
    {
        return [
            'data.product.id' => ['required', 'integer'],
            'instance' => ['required', Rule::in(['cart', 'wishlist', 'compare'])]
        ];
    }

    public function attributes()
    {
        return [
            'data.product.id' => 'id',
            'data.quantity' => 'quantity',
            'instance' => 'instance',
        ];
    }
}
