<?php

namespace App\Http\Requests\Ecommerce;

use App\Http\Controllers\Ecommerce\CartController;
use App\Http\Requests\BaseFormRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $cart = (new CartController)->refresh();
        $cartContent = $cart->getContent();

        $this->merge([
            'user_id' => auth()->id(),
            'grand_total' => $cart->getTotal(),
            'item_count' => $cartContent->count()
        ]);

        if (!$this->has('billing_firstname')) {
            $this->merge([
                'billing_firstname' => $this->shipping_firstname,
                'billing_lastname' => $this->shipping_lastname,
                'billing_email' => $this->shipping_email,
                'billing_phone' => $this->shipping_phone,
                'billing_country' => $this->shipping_country,
                'billing_address' => $this->shipping_address,
                'billing_city' => $this->shipping_city,
                'billing_state' => $this->shipping_state,
                'billing_zipcode' => $this->shipping_zipcode,
            ]);
        }

        switch ($this->method()) {
            case 'POST':
                $this->merge([
                    'number' => IdGenerator::generate(['table' => 'orders', 'length' => 20, 'prefix' => date('Ym'), 'field' => 'number'])
                ]);
                break;
        }
    }

    public function rules()
    {
        $rules = [];
        $rules['user_id'] = 'nullable';
        $rules['payment_method'] = 'nullable|in:cash_on_delivery,paypal,stripe,card';

        $rules['grand_total'] = 'required|numeric|min:1';
        $rules['item_count'] = 'required|numeric|min:1';
        $rules['notes'] = 'nullable|string|max:255';

        switch ($this->method()) {
            case 'POST':
                $rules['number'] = 'required|string|max:50|unique:orders';
                break;
            case 'PUT':
                $rules['number'] = 'required|string|max:50|unique:orders,number,' . $this->order->id;
                break;
        }

        $rules['shipping_firstname'] = 'required|string|max:255';
        $rules['shipping_lastname'] = 'required|string|max:255';
        $rules['shipping_email'] = 'required|string|max:255';
        $rules['shipping_phone'] = 'required|string|max:50';
        $rules['shipping_country'] = 'required|string|max:50';
        $rules['shipping_address'] = 'required|string|max:255';
        $rules['shipping_city'] = 'required|string|max:50';
        $rules['shipping_state'] = 'required|string|max:50';
        $rules['shipping_zipcode'] = 'required|string|max:50';

        $rules['billing_firstname'] = 'required|string|max:255';
        $rules['billing_lastname'] = 'required|string|max:255';
        $rules['billing_email'] = 'required|string|max:255';
        $rules['billing_phone'] = 'required|string|max:50';
        $rules['billing_country'] = 'required|string|max:50';
        $rules['billing_address'] = 'required|string|max:255';
        $rules['billing_city'] = 'required|string|max:50';
        $rules['billing_state'] = 'required|string|max:50';
        $rules['billing_zipcode'] = 'required|string|max:50';

        return $rules;
    }
}
